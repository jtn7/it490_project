# MongoDB Replication Setup

## Pre-requisites
* Docker
* Latest MongoDB Docker Image

To pull latest MongoDB Docker Image, run the following command:
`docker pull mongo`

## Step 1: Create Keyfile on Virtual Machine
#### Steps:
1. Create keyfile by using openssl's rand function and save it in desired path: `openssl rand -base64 755 > /path/to/keyfile`
2. Set the key to Read Only for the owner: `chmod 400 /path/to/keyfile`
3. Change the owner of the file so MongoDB can access the key for internal authentication: `chown 999:999 /path/to/keyfile`

## Step 2: Start Docker Containers
```
docker run
-p <hostPort>:<containerPort>
-v </VMpath/to/keyfile>:</path/to/keyfile/on/container>
--name <containerName>
-d
mongo --auth --replSet <replicaSetName> --keyFile </path/to/keyfile/on/container>
```

The command above is the basis of what we used to start our containers:
* **docker run** - runs command in a new container
* **-p <hostPort>:<containerPort>** - publish the container's port to the host
* **-v </VMpath/to/keyfile>:</path/to/keyfile/on/container>** - bind the keyfile to a volume
* **--name <containerName>** - give the container a name
* **-d** - run the container in background and print its ID
* **mongo --auth --replSet <replicaSetName> --keyFile </path/to/keyfile/on/container>** - starting command: mongo with auth, the replica set, and the keyfile

#### Example
Running our first container:

`docker run -p 37017:27017 -v /path/to/keyfile/fromVM:/opt/mongo/mongo-keyfile --name mongo-local01 -d mongo --auth --replSet rslocal01 --keyFile /opt/mongo/mongo-keyfile`

Running our second container using different port and name:

`docker run -p 37018:27017 -v /path/to/keyfile/fromVM:/opt/mongo/mongo-keyfile --name mongo-local02 -d mongo --auth --replSet rslocal01 --keyFile /opt/mongo/mongo-keyfile`

* To check if the containers are started, run the command: `docker ps`

## Step 3: For each replica set, configure hosts and mongod.conf file
To configure the files run the command `docker exec -it <container-name> bash`.

The hosts and mongod.conf files can be found from the /etc directory
### Configure Hosts File
Add the following lines in `/etc/hosts`:
```
ip-address1 container-name1
ip-address2 container-name2
```

* Each member of the replica set must have these lines so that it identifies each other as a members of the set.
### Configure mongod.conf
Modify the following lines in `/etc/mongod.conf`:
```
net:
    port: 27017
    bindIP: 127.0.0.1,IPofCurrentContainer

security:
    keyFile: /path/to/keyfile/on/container
```

1. Add the IP address of the container you are on in the bindIP under net
2. Uncomment security and add keyFile with its path on the container

## Step 4: Connect to Mongo
To connect to Mongo, run the command: `docker exec -it container-name mongo `

### Create an administrative user and log in as one
#### Steps
1. Connect to the admin DB: `use admin`
2. Create an administrative user with root priviledges: `db.createUser({user: "user", pwd: "password", roles:[{role: "root", db: "admin"}]})`
3. Log in as admin: `db.auth('user','password')`

## Step 5: Initiate Replica Set
Once log in as an admin user, run the following command: 
`rs.initiate({
     "_id" : "rslocal01",
     "members" : [
         {
             "_id" : 0,
             "host" : "mongo-local01:27017"
         },
         {
             "_id" : 1,
             "host" : "mongo-local02:27017"
         }
     ]
 });`

The above command will initiate the replica set with the following hosts as its members.

You can check the configuration of the replica set by running the command: `rs.config()`

You can also check the status of the replica set by running the command: `rs.status()`

## Step 6: Test Replication
To test replication, you can create test data on the primary member of your replica set. Make sure that you are logged in as an admin user.

```
> use test
> db.createCollection('customers')
> db.customers.insert({first_name:"umar", last_name:"jutt"})
> db.customers.find().pretty()
```

* `use test` - to create and/or switch to test database
* `db.createCollection('customers')` - create a collection named customers
* `db.customers.insert({first_name:"umar", last_name:"jutt"})` - add json document into the "customers" collection
* `db.customers.find().pretty()` - use this command to view the documents made in the "customers" collection. find() returns all documents in the collection. pretty() displays results in an easy-to-read format

After creating data on the primary replica set, check the data in the secondary replica set. 

1. Log in as an admin user on the secondary replica set:

```
use admin
db.auth('user','password')
```

2. Run the command: `db.getMongo().setSlaveOk()`

The above command enables read operations on the secondary replica set. After running this command, you will now be able to view the test data created from the primary replica set.

3. Run the following commands

```
> show dbs
> use test
> show collections
> db.customers.find().pretty()
```

* `show dbs` - if you want to display all databases created. The secondary member should be able to see the table created by the primary member
* `use test` - switch to the test database
* `show collections` - if you want to display all the collections under the database. Just like the databases, the secondary member should be able to see all collections created by primary member
* `db.customers.find().pretty()` - view the documents made in the "customers" collection. Secondary member should be able to view the json documents that the primary member created

## Note
If you are able to view the documents made by the primary member on the secondary member, you have set up MongoDB Replication successfuly.

## References
* ShellFu: [Setting Up a MongoDB Replica Set on Docker](http://shellfu.com/setting-up-a-mongodb-replica-set-on-docker/)
* Linode: [Create a MongoDB Replica Set](https://www.linode.com/docs/databases/mongodb/create-a-mongodb-replica-set/)
