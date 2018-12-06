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
	folder varchar(200) PRIMARY KEY,
	title varchar(200) NOT NULL,
	start_date date  NOT NULL, 
	end_date date  NOT NULL
);

CREATE TABLE IF NOT EXISTS Language (
	name varchar(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS Homework_Language (
	folder varchar(200),
	name varchar(255),
	PRIMARY KEY (folder, name),
	FOREIGN KEY (folder) REFERENCES Homework(folder),
	FOREIGN KEY (name) REFERENCES Language(name)
);

CREATE TABLE IF NOT EXISTS User_Homework (
	folder varchar(200),
	user_name varchar(255),
	score int NOT NULL,
	PRIMARY KEY (folder, user_name),
	FOREIGN KEY (folder) REFERENCES Homework(folder),
	FOREIGN KEY (user_name) REFERENCES User(user_name)
);

show tables;


# Inserting users


# Inserting roles
