CREATE DATABASE db_commodity_management;

USE db_commodity_management;

CREATE TABLE t_commodity (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(128) NOT NULL,
    stock int(11) NOT NULL DEFAULT '0',
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (`name`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
