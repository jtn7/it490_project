package main

import (
	"net/http"
	"testing"
)

func TestMain(t *testing.T) {
	response, err := http.Get("http://localhost/health")
	if err != nil {
		t.Errorf("GET request encountered an error")
	} else if response.StatusCode != 200 {
		t.Errorf("Non 200 response returned")
	}
}
