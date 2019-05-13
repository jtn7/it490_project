DROP USER IF EXISTS `slave-user`;
CREATE USER 'slave-user'@'%' IDENTIFIED BY 'pass';

GRANT REPLICATION SLAVE ON *.* TO 'slave-user'@'%';