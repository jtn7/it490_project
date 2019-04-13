package main

import (
	"archive/zip"
	"errors"
	"io"
	"io/ioutil"
	"net/http"
	"os"
	"path/filepath"
	"regexp"
	"strings"

	log "github.com/sirupsen/logrus"
)

func validatePackageName(fileName string) error {
	isMatch, _ := regexp.MatchString(`^dndProj-([0-9]+\.){3}pkg$`, fileName)
	if !isMatch {
		return errors.New("Package name is incorrectly formatted")
	}
	return nil
}

func isDuplicatePackageVersion(fileName string) (bool, error) {
	files, err := ioutil.ReadDir("./packages/")
	if err != nil {
		return true, err
	}

	for _, f := range files {
		if fileName == f.Name() {
			return true, nil
		}
	}

	return false, nil
}

func errorResponse(response http.ResponseWriter, message string, statusCode int) {
	response.WriteHeader(statusCode)
	response.Write([]byte(message))
	log.Info(message)
	log.Info("Returning: ", statusCode, " response")
}

func respondWithClient(resp http.ResponseWriter) {
	clientPage, _ := os.Open("../client/client.html")
	contents, _ := ioutil.ReadAll(clientPage)
	resp.Write(contents)
}
func uploadPackage(httpResponse http.ResponseWriter, httpRequest *http.Request) {
	log.Info("/upload accessed")

	switch httpRequest.Method {
	case http.MethodGet:
		log.Info("Processing GET request")
		respondWithClient(httpResponse)
		return
	}

	log.Info("Processing POST request")
	requestor := httpRequest.FormValue("requestor")
	if requestor == "" {
		log.Info("Requestor is not set")
		errorResponse(httpResponse, "Requestor is not set", http.StatusBadRequest)
		return
	}
	log.Info("Requestor: ", requestor)

	// FormFile returns the file for the given key `package`
	// it also returns the FileHeader so we can get the Filename,
	// the Header and the size of the file
	file, packageMetaData, err := httpRequest.FormFile("package")
	if err != nil {
		log.Error("error retrieving the file: ", err.Error())
		httpResponse.WriteHeader(http.StatusInternalServerError)
		return
	}
	defer file.Close()
	log.Info("Uploaded Package: ", packageMetaData.Filename)
	log.Infof("File Size: %.2f KB", float32(packageMetaData.Size)/float32(1024))

	if validatePackageName(packageMetaData.Filename) != nil {
		httpResponse.WriteHeader(http.StatusBadRequest)
		httpResponse.Write([]byte("Package file name is incorrectly formated"))
		return
	}

	isDuplicate, err := isDuplicatePackageVersion(packageMetaData.Filename)
	if err != nil {
		log.Error("Checking if package version was duplicate failed: ", err)
		httpResponse.WriteHeader(http.StatusInternalServerError)
		return
	} else if isDuplicate == true {
		errorResponse(httpResponse, "Package version already exists", http.StatusBadRequest)
		return
	}

	// read all of the contents of our uploaded file into a byte array
	fileBytes, err := ioutil.ReadAll(file)
	if err != nil {
		errorResponse(httpResponse, "Could not read file", http.StatusInternalServerError)
		return
	}

	// Write byte array to file
	name := filepath.Join("packages", packageMetaData.Filename)
	err = ioutil.WriteFile(name, fileBytes, os.ModePerm)
	if err != nil {
		httpResponse.WriteHeader(http.StatusInternalServerError)
		log.Error("Could not write file: ", err)
		return
	}

	// Extract code changes for deployment
	if _, err := Unzip(name, "extracted"); err != nil {
		httpResponse.WriteHeader(http.StatusInternalServerError)
		httpResponse.Write([]byte("error occurred while extracting\n"))
		httpResponse.Write([]byte(err.Error()))
		return
	}

	httpResponse.Write([]byte("Package upload and deployment were successful"))
	log.Info("Request was Successful")
}

// Unzip will decompress a zip archive, moving all files and folders
// within the zip file (src) to an output directory (dest).
func Unzip(src string, dest string) ([]string, error) {

	var fileNames []string

	zipReader, err := zip.OpenReader(src)
	if err != nil {
		return fileNames, err
	}
	defer zipReader.Close()

	for _, f := range zipReader.File {

		// Store filename/path for returning and using later on
		fpath := filepath.Join(dest, f.Name)

		// Check for ZipSlip. More Info: http://bit.ly/2MsjAWE
		if !strings.HasPrefix(fpath, filepath.Clean(dest)+string(os.PathSeparator)) {
			return fileNames, errors.New("illegal file path: " + fpath)
		}

		fileNames = append(fileNames, fpath)

		if f.FileInfo().IsDir() {
			// Make Folder
			os.MkdirAll(fpath, os.ModePerm)
			continue
		}

		// Make File
		if err = os.MkdirAll(filepath.Dir(fpath), os.ModePerm); err != nil {
			return fileNames, err
		}

		// Create empty file with path of the current index
		outputFile, err := os.OpenFile(fpath, os.O_WRONLY|os.O_CREATE|os.O_TRUNC, f.Mode())
		if err != nil {
			return fileNames, err
		}

		inputFile, err := f.Open()
		if err != nil {
			return fileNames, err
		}

		_, err = io.Copy(outputFile, inputFile)

		outputFile.Close()
		inputFile.Close()

		if err != nil {
			return fileNames, err
		}
	}
	return fileNames, nil
}

func setupRoutes() {
	http.HandleFunc("/upload", uploadPackage)
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
