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
