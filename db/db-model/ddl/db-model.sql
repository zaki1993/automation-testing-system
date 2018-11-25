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
	PRIMARY KEY(user_name, role_name)
);

show tables;


# Inserting users


# Inserting roles
