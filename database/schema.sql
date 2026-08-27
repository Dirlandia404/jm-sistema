/*
id_user BIGINT(20)
name VARCHAR(150)
email VARCHAR(100)
password VARCHAR(45)
created_at DATETIME
update_at DATETIME
ativo TINYINT(1)

service
id_service BIGINT(20)
description VARCHAR(45)
price DECIMAL(11,3)
created_at DATETIME
update_at DATETIME
finished_at DATETIME
commission_user DECIMAL(11,3)
user_id_user BIGINT(20)

relacionamento de 1:N  1 usuario pode ter muitos services  */

CREATE DATABASE  IF NOT EXISTS jm_sistema DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE jm_sistema;

CREATE TABLE user(
    id_user BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL ,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(45) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME,
    ativo TINYINT(1) NOT NULL
);

CREATE TABLE service(
    id_service BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(45) NOT NULL,
    price DECIMAL(11,3) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME,
    finished_at DATETIME,
    commission_user DECIMAL(11,3) NOT NULL,
    user_id_user BIGINT(20) NOT NULL,
    FOREIGN KEY(user_id_user) REFERENCES user(id_user)
);
