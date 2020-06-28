package main

import (
	"fmt"
	"log"
	"net/http"
	"time"
)

// healthHandler logs the date and time that the health route is accessed
// and returns a 200 response
func healthHandler(w http.ResponseWriter, r *http.Request) {
	timeOfAccess := time.Now()

	// Log the time that this route was accessed
	fmt.Println(
		timeOfAccess.Format("Jan-02-2006 15:04:05.000") +
			" /health accessed",
	)

	// Return a success response code to the http request
	w.WriteHeader(http.StatusOK)
}

func main() {
	mux := http.NewServeMux()
	mux.Handle("/health", http.HandlerFunc(healthHandler))

	s := &http.Server{
		Addr:         ":80",
		Handler:      mux,
		ReadTimeout:  3 * time.Second,
		WriteTimeout: 3 * time.Second,
	}
	s.SetKeepAlivesEnabled(false)

	log.Fatal(s.ListenAndServe())
}
