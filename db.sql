-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 14, 2023 at 06:45 PM
-- Server version: 5.7.39-42-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbntos1pjl9uqt`
--

-- Table: users
CREATE TABLE users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  num_polls_posted INT DEFAULT 0,
  num_followers INT DEFAULT 0,
  bio VARCHAR(500),
  profile_picture VARCHAR(255)
);

-- Table: polls
CREATE TABLE polls (
  poll_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  num_options INT CHECK(num_options >= 2 AND num_options <= 4),
  image1 VARCHAR(255),
  image2 VARCHAR(255),
  image3 VARCHAR(255),
  image4 VARCHAR(255),
  total_responses INT DEFAULT 0,
  option1_responses INT DEFAULT 0,
  option2_responses INT DEFAULT 0,
  option3_responses INT DEFAULT 0,
  option4_responses INT DEFAULT 0,
  poll_title VARCHAR(50) NOT NULL,
  poll_description VARCHAR(500) NOT NULL,
  category VARCHAR(50),
  subcategory VARCHAR(50),
  posted_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Table: user_polls
CREATE TABLE user_polls (
  user_id INT,
  poll_id INT,
  PRIMARY KEY (user_id, poll_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (poll_id) REFERENCES polls(poll_id)
);

-- Table: user_follows
CREATE TABLE user_follows (
  follower_id INT,
  followed_id INT,
  PRIMARY KEY (follower_id, followed_id),
  FOREIGN KEY (follower_id) REFERENCES users(user_id),
  FOREIGN KEY (followed_id) REFERENCES users(user_id)
);