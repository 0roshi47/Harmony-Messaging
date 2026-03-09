drop database `harmony-messaging-backend_db`;
create database `harmony-messaging-backend_db`;


drop user `harmony-messaging-backend`@'localhost';
CREATE USER `harmony-messaging-backend`@'localhost' IDENTIFIED BY 'tw9<0(M,HBhR=>*';
GRANT ALL PRIVILEGES ON `harmony-messaging-backend_db`.* TO `harmony-messaging-backend`@'localhost';
FLUSH PRIVILEGES;

drop table if exists Message;
drop table if exists Author;
drop table if exists Room;

CREATE TABLE Author (
   authorId int auto_increment,
   password VARCHAR(100) not null,
   email VARCHAR(50) not null,
   username VARCHAR(50) not null,
   PRIMARY KEY(authorId),
   UNIQUE(email)
);

CREATE TABLE Room (
   roomId int auto_increment,
   roomName VARCHAR(50) not null,
   authorId INT not null,
   PRIMARY KEY(roomId),
   FOREIGN KEY(authorId) REFERENCES Author(authorId)
);

CREATE TABLE Message (
   messageId int auto_increment,
   content VARCHAR(500) not null,
   postDate DATETIME not null,
   authorId INT not null,
   roomId INT not null,
   PRIMARY KEY(messageId),
   FOREIGN KEY(authorId) REFERENCES Author(authorId),
   FOREIGN KEY(roomId) REFERENCES Room(roomId)
);
