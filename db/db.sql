-- Server version       8.0.15

SET NAMES utf8mb4;

--
-- Create the `users` database
--
DROP DATABASE IF EXISTS `users`;
CREATE DATABASE `users`;
USE `users`;

--
-- Table structure for table `account`
--
DROP TABLE IF EXISTS `accounts`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `accounts` (
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping routines for database 'users'
--
DROP PROCEDURE IF EXISTS `getPassword`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `getPassword`(IN `ID` VARCHAR(50))
BEGIN
SELECT `password` FROM `accounts` WHERE `username` = `ID`;
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `createUser`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `createUser`(IN `userid` VARCHAR(50), IN `pass` VARCHAR(100))
BEGIN
INSERT INTO `accounts` (`username`, `password`) VALUES (`userid`,`pass`);
END ;;
DELIMITER ;


DROP USER IF EXISTS `auth-client`;
CREATE USER 'auth-client'@'%' IDENTIFIED BY 'pass';

GRANT EXECUTE ON PROCEDURE users.createUser to 'auth-client'@'%';
GRANT EXECUTE ON PROCEDURE users.getPassword to 'auth-client'@'%';



--
-- Create `messageBoard` database
--
DROP DATABASE IF EXISTS `messageBoard`;
CREATE DATABASE `messageBoard`;
USE `messageBoard`;

--
-- Table structure for table `forums`
--
DROP TABLE IF EXISTS `forums`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `forums` (
  `ForumID` INT(8) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(80) NOT NULL,
  `Description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ForumID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `threads` (
  `ThreadID` INT(8) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(80) NOT NULL,
  `Content` TEXT NOT NULL,
  `User` VARCHAR(80) NOT NULL,
  `Timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ForumID` INT(8) NOT NULL,
  PRIMARY KEY (`ThreadID`),
  KEY `ForumID` (`ForumID`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`ForumID`) REFERENCES `forums` (`ForumID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `replies` (
  `ReplyID` INT(8) NOT NULL AUTO_INCREMENT,
  `Content` TEXT NOT NULL,
  `User` VARCHAR(80) NOT NULL,
  `Timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ThreadID` INT(8) NOT NULL,
  PRIMARY KEY (`ReplyID`),
  KEY `ThreadID` (`ThreadID`),
  CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`ThreadID`) REFERENCES `threads` (`ThreadID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Dumping routines for database 'messageBoard'
--
DROP PROCEDURE IF EXISTS `getForums`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `getForums`()
BEGIN
SELECT * FROM `forums`;
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `getThreads`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `getThreads`(IN ID INT(8))
BEGIN
SELECT * FROM `threads` WHERE `ForumID` = `ID`;
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `getThread`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `getThread`(IN ID INT(8))
BEGIN
SELECT * FROM `threads` WHERE `ThreadID` = `ID`;
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `getReplies`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `getReplies`(IN ID INT(8))
BEGIN
SELECT * FROM `replies` WHERE `ThreadID` = `ID`;
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `createThread`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `createThread`(IN `ID` INT(8), `nam` VARCHAR(80), `cont` TEXT, `usr` VARCHAR(80))
BEGIN
INSERT INTO `threads` (
    `ForumID`,
    `Name`,
    `Content`,
    `User`
) VALUES (
    `ID`,
    `nam`,
    `cont`,
    `usr`
);
END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `createReply`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `createReply`(IN `ID` INT(8), `cont` TEXT, `usr` VARCHAR(80))
BEGIN
INSERT INTO `replies` (
    `ThreadID`,
    `Content`,
    `User`
) VALUES (
    `ID`,
    `cont`,
    `usr`
);
END ;;
DELIMITER ;

DROP USER IF EXISTS `forum-client`;
CREATE USER 'forum-client'@'%' IDENTIFIED BY 'pass';

GRANT EXECUTE ON PROCEDURE messageBoard.getForums TO 'forum-client'@'%';
GRANT EXECUTE ON PROCEDURE messageBoard.getThreads TO 'forum-client'@'%';
GRANT EXECUTE ON PROCEDURE messageBoard.getThread TO 'forum-client'@'%';
GRANT EXECUTE ON PROCEDURE messageBoard.getReplies TO 'forum-client'@'%';
GRANT EXECUTE ON PROCEDURE messageBoard.createThread TO 'forum-client'@'%';
GRANT EXECUTE ON PROCEDURE messageBoard.createReply TO 'forum-client'@'%';

ALTER USER 'root' IDENTIFIED WITH mysql_native_password BY 'pass';

INSERT INTO `forums` (`Name`, `Description`) VALUES (
  'Looking For Players',
  'Gathering players for your next session? Ask here!'
);

INSERT INTO `forums` (`Name`, `Description`) VALUES (
  "Adventurer's Log",
  'Use this forum to let everyone know about the exploits of your characters.'
);

INSERT INTO `forums` (`Name`, `Description`) VALUES (
  'Meta',
  'A place to discuss the layout and features of the site. Suggestions welcome.'
);
