package main

import (
	"archive/zip"
	"fmt"
	"io"
	"io/ioutil"
	"net/http"
	"os"
	"path/filepath"
	"regexp"
	"strings"

	log "github.com/sirupsen/logrus"
)

const (
	internalError = http.StatusInternalServerError
	badRequest    = http.StatusBadRequest
)

type deployError struct {
	message    string
	origin     error
	httpStatus int
}

func (derr *deployError) Error() string {
	return fmt.Sprintf("%s: %s", derr.message, derr.origin.Error())
}

func (derr *deployError) handleError(resp http.ResponseWriter) {
	switch derr.httpStatus {
	case http.StatusBadRequest:
		log.Info(derr.Error())
		http.Error(resp, derr.message, derr.httpStatus)
	case http.StatusInternalServerError:
		log.Errorf(derr.Error())
		http.Error(resp, http.StatusText(derr.httpStatus), derr.httpStatus)
	}
}

func newDeployError(msg string, status int, orig error) *deployError {
	return &deployError{
		message:    msg,
		httpStatus: status,
		origin:     orig,
	}
}

func validatePackageName(fileName string) *deployError {
	isMatch, err := regexp.MatchString(`^dndProj-([0-9]+\.){3}pkg$`, fileName)
	if err != nil {
		return newDeployError("Could not perform regex matching", internalError, err)
	}
	if !isMatch {
		return newDeployError("Package name is incorrectly formatted", badRequest, nil)
	}
	return nil
}

func isDuplicatePackageVersion(fileName string) *deployError {
	files, err := ioutil.ReadDir("./packages/")
	if err != nil {
		return newDeployError("Could not locate ./packages", internalError, err)
	}

	for _, f := range files {
		if fileName == f.Name() {
			return newDeployError("Package "+fileName+" already exists", badRequest, nil)
		}
	}

	return nil
}

func respondWithClient(resp http.ResponseWriter) *deployError {
	clientPage, err := os.Open("../client/client.html")
	if err != nil {
		return newDeployError("Could not open client page file", internalError, err)
	}
	contents, err := ioutil.ReadAll(clientPage)
	if err != nil {
		return newDeployError("Could not read client file", internalError, err)
	}
	resp.Write(contents)

	return nil
}

func uploadPackage(response http.ResponseWriter, request *http.Request) {
	log.Info("/upload accessed")

	if request.Method == http.MethodGet {
		log.Info("Processing GET request")
		derr := respondWithClient(response)
		if derr != nil {
			derr.handleError(response)
		}
		return
	}

	log.Info("Processing POST request")
	requestor := request.FormValue("requestor")
	if requestor == "" {
		newDeployError("Requestor is not set", badRequest, nil).handleError(response)
		return
	}
	log.Info("Requestor: ", requestor)

	// FormFile returns the file for the given key `package`
	// it also returns the FileHeader so we can get the Filename,
	// the Header and the size of the file
	file, packageMetaData, err := request.FormFile("package")
	if err != nil {
		newDeployError("Could not retrieve form file", internalError, err).handleError(response)
		return
	}
	defer file.Close()
	log.Info("Uploaded Package: ", packageMetaData.Filename)
	log.Infof("File Size: %.2f KB", float32(packageMetaData.Size)/float32(1024))

	derr := validatePackageName(packageMetaData.Filename)
	if derr != nil {
		derr.handleError(response)
		return
	}

	derr = isDuplicatePackageVersion(packageMetaData.Filename)
	if derr != nil {
		derr.handleError(response)
		return
	}

	// read all of the contents of our uploaded file into a byte array
	fileBytes, err := ioutil.ReadAll(file)
	if err != nil {
		newDeployError("Could not read bytes from uploaded file", internalError, err).handleError(response)
		return
	}

	// Write byte array to file
	name := filepath.Join("packages", packageMetaData.Filename)
	err = ioutil.WriteFile(name, fileBytes, os.ModePerm)
	if err != nil {
		newDeployError("Could not write upload to file", internalError, err).handleError(response)
		return
	}

	// Extract code changes for deployment
	if derr = unzip(name, "extracted"); derr != nil {
		derr.handleError(response)
		return
	}

	response.Write([]byte("Package upload and deployment were successful"))
	log.Info("Request was Successful")
}

func rollBack(response http.ResponseWriter, request *http.Request) {
	log.Info("/rollback accessed")
}

// Unzip will decompress a zip archive, moving all files and folders
// within the zip file (src) to an output directory (dest).
func unzip(src string, dest string) *deployError {
	var fileNames []string

	zipReader, err := zip.OpenReader(src)
	if err != nil {
		return newDeployError("Could not open the package", internalError, err)
	}
	defer zipReader.Close()

	for _, f := range zipReader.File {

		// Store filename/path for returning and using later on
		fpath := filepath.Join(dest, f.Name)

		// Check for ZipSlip. More Info: http://bit.ly/2MsjAWE
		if !strings.HasPrefix(fpath, filepath.Clean(dest)+string(os.PathSeparator)) {
			return newDeployError("Illegal filepath: "+fpath, badRequest, nil)
		}

		fileNames = append(fileNames, fpath)

		if f.FileInfo().IsDir() {
			// Make Folder
			os.MkdirAll(fpath, os.ModePerm)
			continue
		}

		// Make File
		if err = os.MkdirAll(filepath.Dir(fpath), os.ModePerm); err != nil {
			return newDeployError("Could not make directory for item in zip", internalError, err)
		}

		// Create empty file with path of the current index
		outputFile, err := os.OpenFile(fpath, os.O_WRONLY|os.O_CREATE|os.O_TRUNC, f.Mode())
		if err != nil {
			return newDeployError("Could not create empty file", internalError, err)
		}

		inputFile, err := f.Open()
		if err != nil {
			return newDeployError("Could not open stream for item in zip", internalError, err)
		}

		_, err = io.Copy(outputFile, inputFile)

		outputFile.Close()
		inputFile.Close()

		if err != nil {
			return newDeployError("Could not copy item into empty file", internalError, err)
		}
	}
	return nil
}

func setupRoutes() {
	http.HandleFunc("/upload", uploadPackage)
	http.HandleFunc("/rollback", rollBack)
	log.Fatal(http.ListenAndServe(":80", nil))
}

func main() {
	// file, err := os.OpenFile("logrus.log", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
	// if err == nil {
	log.SetOutput(os.Stdout)
	// } else {
	// 	log.Info("Failed to log to file, using default stderr")
	// }

	log.SetFormatter(&log.TextFormatter{
		ForceColors:   true,
		FullTimestamp: true,
	})
	setupRoutes()
}
