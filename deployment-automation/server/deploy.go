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
	"os/exec"
	"path/filepath"
	"regexp"
	"sort"
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

	lUsage    = "the path to place the log in (defaults to stdout)"
	backUsage = "name of the backend container if one is present"
)

var packageDir string
var outputDir string
var daemonPort string
var logOutput string
var backendContainer string
var rollbackPackagePath string

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
	// Backend container name
	flag.StringVar(&backendContainer, "backend", "", backUsage)

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

	// Checking packageDir
	_, err := os.Stat(packageDir)
	if err != nil {
		err = os.MkdirAll(packageDir, os.ModePerm)
		if err != nil {
			log.Fatal("Could not make package directory: ", packageDir)
		}
	}

	ln, err := net.Listen("tcp", ":"+daemonPort)
	if err != nil {
		log.Fatal("Cannot listen on port: ", daemonPort, err)
	}
	_ = ln.Close()

	rollbackPackagePath = filepath.Join(packageDir, "rollback.pkg")
}

// checkArguments validates all set arguments.
func checkArguments(f *flag.Flag) {
	if f.Value.String() == "" {
		log.Fatal("Passed paths must not be empty")
	}
	switch f.Name {
	case "backend":
		return
	case "port":
		return
	default:
		checkPath(string(f.Value.String()))
	}
}

// checkPath validates if a given path exists.
// If the path does not exist this function attempts to create that path
func checkPath(path string) {
	fi, err := os.Stat(path)
	if err != nil {
		log.Warn("Path: ", path, " does not exist.")
		log.Info("Attempting to create path...")
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
	packageFile, packageMetaData, err := request.FormFile("package")
	if err != nil {
		newDeployError("Could not retrieve form file", internalError, err).handleError(response)
		return
	}
	defer packageFile.Close()
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
	packageBytes, err := ioutil.ReadAll(packageFile)
	if err != nil {
		newDeployError("Could not read bytes from uploaded file", internalError, err).handleError(response)
		return
	}

	// Write byte array to file
	packagePath := filepath.Join(packageDir, packageMetaData.Filename)
	err = ioutil.WriteFile(packagePath, packageBytes, os.ModePerm)
	if err != nil {
		newDeployError("Could not write upload to file", internalError, err).handleError(response)
		return
	}

	// Create rollback snapshot
	log.Info("Creating rollback")
	err = createRollbackPackage(packagePath)
	if err != nil {
		newDeployError("Could not create rollback package", internalError, err).handleError(response)
		return
	}
	log.Info("Rollback creation complete")

	// Extract code changes for deployment
	if derr = unzip(packagePath, outputDir); derr != nil {
		derr.handleError(response)
		return
	}

	restartBackend()

	response.Write([]byte("Package upload and deployment were successful"))
	log.Info("Upload request by ", requestor, " was successful")
}

// restartBackend restarts the backend container to apply changes
func restartBackend() {
	if backendContainer != "" {
		err := exec.Command("docker", "stop", backendContainer).Run()
		if err != nil {
			log.Error("Could not stop backend container", err)
		}
		err = exec.Command("docker", "start", backendContainer).Run()
		if err != nil {
			log.Error("Could not start backend container", err)
		}
	}
}

// rollBack is the handler for requests to /rollback
// Deletes the currently deployed package and deploys the next most recent package
func rollBack(response http.ResponseWriter, request *http.Request) {
	log.Info("/rollback accessed")

	files, err := ioutil.ReadDir(packageDir)
	if err != nil {
		newDeployError("Package directory not found", internalError, err).
			handleError(response)
		return
	}
	sort.Slice(files, func(i, j int) bool {
		return files[i].ModTime().Unix() < files[j].ModTime().Unix()
	})

	numberOfPackages := len(files)
	if numberOfPackages < 2 {
		log.Info()
		newDeployError(
			"Insufficient number of packages to process rollback",
			internalError,
			nil,
		).handleError(response)
		return
	}

	currentPackage := files[numberOfPackages-2].Name()
	rollbackPackage := "rollback.pkg"

	err = os.Remove(filepath.Join(packageDir, currentPackage))
	if err != nil {
		newDeployError(
			"Could not remove currently deployed package",
			internalError,
			err,
		).handleError(response)
	}

	log.Info("Rolling back to: ", rollbackPackage)
	derr := unzip(filepath.Join(packageDir, rollbackPackage), outputDir)
	if derr != nil {
		derr.handleError(response)
		return
	}

	restartBackend()
	response.Write([]byte("Rolled back successfully"))
	log.Info("Rollback successful")
}

func createRollbackPackage(incomingPackage string) error {
	zipReader, err := zip.OpenReader(incomingPackage)
	if err != nil {
		return err
	}
	defer zipReader.Close()

	rollbackPackage, err := os.Create(rollbackPackagePath)
	if err != nil {
		return err
	}
	defer rollbackPackage.Close()

	zipWriter := zip.NewWriter(rollbackPackage)
	defer zipWriter.Close()

	for _, f := range zipReader.File {
		rollbackFile := f.Name
		finalPath := filepath.Join(outputDir, rollbackFile)
		// If the file in the package is a new file create an empty file for rollback
		info, err := os.Stat(finalPath)
		if err != nil {
			if filepath.Ext(rollbackFile) == "" {
				err = os.MkdirAll(finalPath, os.ModePerm)
				if err != nil {
					return err
				}
				continue
			}
			emptyFile, err := os.Create(finalPath)
			if err != nil {
				return err
			}
			emptyFile.Close()
		}
		//append file to archive
		if info.IsDir() {
			continue
		}
		if err = addFileToZip(zipWriter, finalPath, rollbackFile); err != nil {
			return err
		}
	}
	return nil
}

func addFileToZip(zipWriter *zip.Writer, file string, pathInZip string) error {
	fileToZip, err := os.Open(file)
	if err != nil {
		return err
	}
	defer fileToZip.Close()

	// Get the file information
	info, err := fileToZip.Stat()
	if err != nil {
		return err
	}

	header, err := zip.FileInfoHeader(info)
	if err != nil {
		return err
	}

	// Using FileInfoHeader() above only uses the basename of the file. If we want
	// to preserve the folder structure we can overwrite this with the full path.
	header.Name = pathInZip

	// Change to deflate to gain better compression
	// see http://golang.org/pkg/archive/zip/#pkg-constants
	header.Method = zip.Deflate

	writer, err := zipWriter.CreateHeader(header)
	if err != nil {
		return err
	}
	_, err = io.Copy(writer, fileToZip)
	return err
}

// unzip decompresses a zip archive, moving all files and folders
// within the zip file (src) to an output directory (dest).
func unzip(src string, dest string) *deployError {
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

// setupRoutes initializes the web server and binds the handler functions for
// the routes /upload and /rollback
func setupRoutes() {
	http.HandleFunc("/upload", uploadPackage)
	http.HandleFunc("/rollback", rollBack)
	log.Fatal(http.ListenAndServe(":"+daemonPort, nil))
}

func main() {
	setupRoutes()
}
