<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start or resume the session
session_start();

// Destroy the existing session
session_destroy();


// Initialize the error message variable
$errorMsg = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $email = $_POST['email'];
  $fullName = $_POST['full-name'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form data (you can add more validation if needed)
  if (empty($email) || empty($fullName) || empty($username) || empty($password)) {
    $errorMsg = "Please fill in all the fields.";
  } else {
    // Create a PDO connection to the database (replace the values with your own)
    $dbHost = 'localhost';
    $dbName = 'dbntos1pjl9uqt';
    $dbUser = 'ubsmyehs1mgzl';
    $dbPass = 'Ekajdf15';

    try {
      $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Check if the email already exists
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
      $stmt->execute(['email' => $email]);
      $count = $stmt->fetchColumn();

      if ($count > 0) {
        $errorMsg = "User with the provided email already exists.";
      } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
          $errorMsg = "Username is already taken.";
        } else {
          // Hash the password
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          // Insert the new user into the database
          $stmt = $pdo->prepare("INSERT INTO users (email, full_name, username, password) VALUES (:email, :fullName, :username, :password)");
          $stmt->execute(['email' => $email, 'fullName' => $fullName, 'username' => $username, 'password' => $hashedPassword]);

          // Redirect the user to following.php
          header("Location: edit_profile.php");
          exit();
        }
      }
    } catch (PDOException $e) {
      // Display detailed error message during development
      $errorMsg = "Database Error: " . $e->getMessage();
      // Remove or comment out the line below in production
      // $errorMsg = "An error occurred. Please try again later.";
    }
  }
}

// Redirect back to signup.php with the error message
if (!empty($errorMsg)) {
  header("Location: signup.php?error=" . urlencode($errorMsg));
  exit();
}
