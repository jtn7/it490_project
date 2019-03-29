#!/bin/sh

kill $(ps -o pid,comm | grep '[0-9]*\sphp' | awk '{print $1}')