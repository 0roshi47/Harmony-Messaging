create database harmony-messaging-backend_db;

CREATE USER 'harmony-messaging-backend'@'localhost' IDENTIFIED BY 'tw9<0(M,HB\hR=>*';
GRANT ALL PRIVILEGES ON harmony-messaging-backend_db.* TO 'harmony-messaging-backend'@'localhost';
FLUSH PRIVILEGES;

drop table if exists Message;

CREATE TABLE Author (
   authorId int auto_increment,
   password VARCHAR(50) not null,
   login VARCHAR(50) not null,
   PRIMARY KEY(authorId)
);

CREATE TABLE Room (
   roomId int auto_increment,
   roomName VARCHAR(50) not null,
   PRIMARY KEY(roomId)
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
