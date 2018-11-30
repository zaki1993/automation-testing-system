drop database IF EXISTS automation_testing_system;

create database IF NOT EXISTS automation_testing_system;

use automation_testing_system;

# Creating tables
CREATE TABLE IF NOT EXISTS User (
    user_name varchar(255) PRIMARY KEY,
    faculty_number int,
    password varchar(255)
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
	title varchar(200),
	folder varchar(200),
	start_date date, 
	end_date date
);

CREATE TABLE IF NOT EXISTS User_Homework (
	id int,
	user_name varchar(255),
	score int,
	PRIMARY KEY (id, user_name),
	FOREIGN KEY (id) REFERENCES Homework(id),
	FOREIGN KEY (user_name) REFERENCES User(user_name)
);


show tables;


# Inserting users


# Inserting roles
