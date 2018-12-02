drop database IF EXISTS automation_testing_system;

create database IF NOT EXISTS automation_testing_system;

use automation_testing_system;

# Creating tables
CREATE TABLE IF NOT EXISTS User (
    user_name varchar(255) PRIMARY KEY,
    faculty_number int  NOT NULL,
    password varchar(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Role (
	role_name varchar(25) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS User_Roles (
	user_name varchar(255),
	role_name varchar(25),
	PRIMARY KEY (user_name, role_name),
	FOREIGN KEY (role_name) REFERENCES Role(role_name),
	FOREIGN KEY (user_name) REFERENCES User(user_name)
);

CREATE TABLE IF NOT EXISTS Homework (
	id int PRIMARY KEY AUTO_INCREMENT,
	title varchar(200) NOT NULL,
	folder varchar(200) UNIQUE,
	start_date date  NOT NULL, 
	end_date date  NOT NULL
);

CREATE TABLE IF NOT EXISTS Language (
	name varchar(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS Homework_Language (
	id int,
	name varchar(255),
	PRIMARY KEY (id, name),
	FOREIGN KEY (id) REFERENCES Homework(id),
	FOREIGN KEY (name) REFERENCES Language(name)
);

CREATE TABLE IF NOT EXISTS User_Homework (
	id int,
	user_name varchar(255),
	score int NOT NULL,
	PRIMARY KEY (id, user_name),
	FOREIGN KEY (id) REFERENCES Homework(id),
	FOREIGN KEY (user_name) REFERENCES User(user_name)
);

show tables;


# Inserting users


# Inserting roles
