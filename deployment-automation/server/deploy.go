package main

import (
	"archive/zip"
	"flag"
	"fmt"
	"io"
	"io/ioutil"
	"net"
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

	defaultPackageDir = "./packages"
	pdUsage           = "the path for the uploaded packages."

	defaultOutputDir = "./output"
	odUsage          = "the path for the extracted packages."

	defaultPort = "80"
	pUsage      = "the port for the daemon to listen on."

	lUsage = "the path to place the log in (defaults to stdout)"
)

var packageDir string
var outputDir string
var daemonPort string
var logOutput string

// init sets up cli flags and log settings.
func init() {
	// Uploaded packages path
	flag.StringVar(&packageDir, "package-dir", defaultPackageDir, pdUsage)
	flag.StringVar(&packageDir, "p", defaultPackageDir, pdUsage+" (shorthand)")
	// Extracted contents path
	flag.StringVar(&outputDir, "output", defaultOutputDir, odUsage)
	flag.StringVar(&outputDir, "o", defaultOutputDir, odUsage+" (shorthand)")
	// Log path
	flag.StringVar(&logOutput, "log-dir", "", lUsage)
	flag.StringVar(&logOutput, "l", "", lUsage+" (shorthand)")
	// Daemon port
	flag.StringVar(&daemonPort, "port", defaultPort, pUsage)

	flag.Parse()

	log.SetFormatter(&log.TextFormatter{
		ForceColors:   true,
		FullTimestamp: true,
	})

	// Run validation on set arguments
	flag.Visit(checkArguments)

	if logOutput != "" {
		path := filepath.Join(logOutput, "deploy.log")
		file, err := os.OpenFile(path, os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
		if err != nil {
			log.Error("Could not open log file stream. Using stdout instead:", err)
			log.SetOutput(os.Stdout)
		} else {
			log.SetOutput(file)
		}
	} else {
		log.SetOutput(os.Stdout)
	}

	ln, err := net.Listen("tcp", ":"+daemonPort)
	if err != nil {
		log.Fatal("Cannot listen on port: ", daemonPort, err)
	}
	_ = ln.Close()
}

// checkArguments validates all set arguments.
func checkArguments(f *flag.Flag) {
	if f.Value.String() == "" {
		log.Fatal("Passed paths must not be empty")
	}
	checkPath(string(f.Value.String()))
}

// checkPath validates if a given path exists.
// If the path does not exist this function attempts to create that path
func checkPath(path string) {
	fi, err := os.Stat(path)
	if err != nil {
		log.Warn("Path: ", path, " does not exist. Attempting to create path...")
		// Path does not exist so try to make directory
		err := os.MkdirAll(path, os.ModePerm)
		if err != nil {
			log.Fatal("Could not create path: ", err)
		}
		log.Info("Created path: ", path)
		fi, _ = os.Stat(path)
	}
	if !fi.IsDir() {
		log.Fatal("Argument paths should be to a directory, not a file")
	}
}

// deployError is a struct that implements the error interface
type deployError struct {
	message    string
	origin     error
	httpStatus int
}

// Error returns the message field and the message from the original error, if
// one is set
func (derr *deployError) Error() string {
	if derr.origin != nil {
		return fmt.Sprintf("%s: %s", derr.message, derr.origin.Error())
	}
	return derr.message
}

// handleError logs the error and calls http.Error
func (derr *deployError) handleError(resp http.ResponseWriter) {
	switch derr.httpStatus {
	case http.StatusUnauthorized:
		log.Info(derr.Error())
		resp.Header().Add("WWW-Authenticate", "Basic realm=\"Access to upload portal\"")
		resp.WriteHeader(derr.httpStatus)
	case internalError:
		log.Error(derr.Error())
		http.Error(resp, http.StatusText(derr.httpStatus), derr.httpStatus)
	default:
		log.Info(derr.Error())
		http.Error(resp, derr.message, derr.httpStatus)
	}
}

// newDeployError initializes a deployError and returns a pointer
func newDeployError(msg string, status int, orig error) *deployError {
	return &deployError{
		message:    msg,
		httpStatus: status,
		origin:     orig,
	}
}

// validatePackageName checks if the name of the uploaded package matches
// the required regular expression
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

// isDuplicatePackageVersion checks whether there is a package file with the
// same name as the uploaded package
func isDuplicatePackageVersion(fileName string) *deployError {
	files, err := ioutil.ReadDir(packageDir)
	if err != nil {
		return newDeployError("Could not locate package directory", internalError, err)
	}

	for _, f := range files {
		if fileName == f.Name() {
			return newDeployError("Package "+fileName+" already exists", badRequest, nil)
		}
	}

	return nil
}

// respondWithClient writes an html document to the response body for uploading
// packages to the daemon
func respondWithClient(resp http.ResponseWriter) *deployError {
	resp.Header().Add("Content-Type", "text/html")
	_, err := resp.Write([]byte(client))
	if err != nil {
		return newDeployError("Writing to response interface failed", internalError, err)
	}
	return nil
}

// authenticate verifies the if the passed credentials match an authorized user
func authenticate(user string, pass string) bool {
	if user == "bob" && pass == "pass" {
		return true
	}
	return false
}

// uploadPackage is the handler for requests to /upload
func uploadPackage(response http.ResponseWriter, request *http.Request) {
	log.Info("/upload accessed")

	if request.Method == http.MethodGet {
		log.Info("Processing GET request")
		user, pass, _ := request.BasicAuth()
		if !authenticate(user, pass) {
			newDeployError("Incorrect credentials", http.StatusUnauthorized, nil).handleError(response)
			return
		}
		log.Info("Credentials Validated")
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

// rollBack is the handler for requests to /rollback
func rollBack(response http.ResponseWriter, request *http.Request) {
	log.Info("/rollback accessed")
}

// unzip decompresses a zip archive, moving all files and folders
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
	log.Fatal(http.ListenAndServe(":"+daemonPort, nil))
}

func main() {
	setupRoutes()
}
