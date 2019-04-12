package main

import (
	"archive/zip"
	"fmt"
	"io"
	"io/ioutil"
	"log"
	"net/http"
	"os"
	"path/filepath"
	"strings"
	"time"
)

func uploadPackage(w http.ResponseWriter, r *http.Request) {
	timeOfAccess := time.Now()

	// Log the time that this route was accessed
	fmt.Println(
		timeOfAccess.Format("Jan-02-2006 15:04:05.000") +
			" /upload request received",
	)

	// FormFile returns the first file for the given key `myFile`
	// it also returns the FileHeader so we can get the Filename,
	// the Header and the size of the file
	file, handler, err := r.FormFile("package")
	if err != nil {
		fmt.Println("error retrieving the file")
		fmt.Println(err)
		w.WriteHeader(http.StatusInternalServerError)
		return
	}
	defer file.Close()
	fmt.Printf("Uploaded File: %+v\n", handler.Filename)
	fmt.Printf("File Size: %+v\n", handler.Size)
	fmt.Printf("MIME Header: %+v\n", handler.Header)

	// read all of the contents of our uploaded file into a
	// byte array
	fileBytes, err := ioutil.ReadAll(file)
	if err != nil {
		fmt.Println("Error reading the file")
		fmt.Println(err)
		w.WriteHeader(http.StatusInternalServerError)
		return
	}

	// Write byte array to file
	name := filepath.Join("packages", handler.Filename)
	fileErr := ioutil.WriteFile(name, fileBytes, os.ModePerm)
	if fileErr != nil {
		w.WriteHeader(http.StatusInternalServerError)
		fmt.Println("Could not write file")
		fmt.Println(fileErr)
		return
	}

	// Extract code changes for deployment
	if _, err := Unzip(name, "extracted"); err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		w.Write([]byte("error occurred while extracting\n"))
		w.Write([]byte(err.Error()))
		return
	}

	fmt.Fprint(w, "Package upload and deplotment were successful")
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
			return fileNames, fmt.Errorf("%s: illegal file path", fpath)
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
	setupRoutes()
}
