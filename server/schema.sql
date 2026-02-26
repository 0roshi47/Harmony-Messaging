create TABLE Message (
    idMessage int not null auto_increment,
    content VARCHAR(1000) not null,
    author VARCHAR(32) not null,
    postDate DATETIME not null,
    constraint pk_message PRIMARY KEY(idMessage)
)