# Message Board Database
## ER Diagram
![messageBoard database ERD](https://raw.githubusercontent.com/ufa2/database-auth/master/MessageBoard_ERD.png)

## Stored Procedures

* getForums()
* getThreads(ForumID)
* getThread(ThreadID)
* getReplies(ThreadID)

* createThread(ForumID, Name, Content, User)
* createReply(ThreadID, Content, User)

### User to execute procedures
`forums-client`
<br/><br/>
# Users Database
## ER Diagram
![users database ERD](https://raw.githubusercontent.com/ufa2/database-auth/master/Users_ERD.png)

## Stored Procedures

* getPassword(Username)
* createUser(Username,Password)

### User to execute procedures
`authentication-client`

# Running MySQL Live Data Replication

Make sure to have the most up to date db images on jtn7/db (`docker pull`).

### Steps
1. Create a master container using the image `jtn7/db:master`
2. Run the `db.sql` script on the master container
3. Run the `master.sql` script on the master container
4. Use the MySQL shell to get the master log file name and log position using `show master status;`
5. Create a slave container using the image `jtn7/db:slave`
6. Run the `db.sql` script on the slave container
7. Edit the `slave.sql` inputting the values from **Step 4**
8. Run the `slave.sql` script on the slave container