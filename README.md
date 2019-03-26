# Team POGO

## Project Description
A website that will facilitate  communication, planning, creating, storing of content related to Dungeons and Dragons

## Features
- [x] Message Boards
- [x] Character creation and storage
- [x] Character dashboard
- [ ] Party dashboard
- [ ] Calendar integration
- [ ] Notifications

# Message Boards
Forums for discussion

## Database
* Tables
  * forums
  * threads
  * replies
* Stored Procedures
  * getForums()
  * getThreads(ForumID)
  * getReplies(ThreadID)
  * createThread(ForumID, Name, Content, User)
  * createReply(ThreadID, Content, User)

## Frontend
* Forums Page
* Threads Page 
* Replies Page
* Create Thread Page

## Backend
* Retrieves message board data
* Creates message board data
* Retrieves character data
* Creates character data

