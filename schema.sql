create database harmony-messaging-backend_db;

CREATE USER 'harmony-messaging-backend'@'localhost' IDENTIFIED BY 'tw9<0(M,HB\hR=>*';
GRANT ALL PRIVILEGES ON harmony-messaging-backend_db.* TO 'harmony-messaging-backend'@'localhost';
FLUSH PRIVILEGES;

drop table if exists Message;

create TABLE Message (
    idMessage int not null auto_increment,
    content VARCHAR(1000) not null,
    author VARCHAR(32) not null,
    postDate DATETIME not null,
    constraint pk_message PRIMARY KEY(idMessage)
)