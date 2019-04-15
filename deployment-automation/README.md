# Deployment Automation

The deployment server app takes in zip packages of the files with changes and
deploys the changes to QA. From the QA environment the changes in the package
move to the Production environment.

### TODO:
- [x] Create a server executable that accepts a zip file upload
- [x] Make the server extract its contents to the code directory
- [x] Require that packages match a defined naming pattern
- [x] Deny packages of the same version being uploaded
- [x] Overwrite existing source files when package is extracted
- [x] Create Backup of package
	* The deployment package needs to be versioned using the name of the package
	file
- [x] Create client for uploading packages to deployment server
- [x] Implement flag usage for providing package and out dir's
- [ ] Create an endpoint for rolling back production to the previous version
	* The previous version will only be pushed to production in the first place
	if it passed QA

## Argument Flags

 Flag | Description
 --- | -----------
`-p` `--package-dir` | the path for uploaded packages (Defaults to `./packages`)
`-o` `--output` | the path for extracted packages (Defaults to `./output`)
`-c` `--client` | the path to find client.html (Defaults to `./client.html`)
`--port` | the binding port of the daemon (Defaults to `80`)

> Paths can be absolute or relative to the binary