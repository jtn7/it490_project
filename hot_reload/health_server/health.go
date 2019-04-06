package main

import (
	"fmt"
	"log"
	"net/http"
	"time"
)

func handler(w http.ResponseWriter, r *http.Request) {
	timeOfAccess := time.Now()
	fmt.Println(
		timeOfAccess.Format("Jan-02-2006 15:04:05.123") +
			" /health accessed",
	)
	w.WriteHeader(http.StatusOK)
}

func main() {
	mux := http.NewServeMux()
	mux.Handle("/health", http.HandlerFunc(handler))

	s := &http.Server{
		Addr:         ":80",
		Handler:      mux,
		ReadTimeout:  3 * time.Second,
		WriteTimeout: 3 * time.Second,
	}
	s.SetKeepAlivesEnabled(false)

	log.Fatal(s.ListenAndServe())
}
