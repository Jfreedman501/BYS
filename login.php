<?php
// login.php

// Assuming you have established the database connection
// Replace the placeholders with your actual database credentials
$servername = 'localhost';
$username = 'ubsmyehs1mgzl';
$password = 'Ekajdf15';
$dbname = 'dbntos1pjl9uqt';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the error message variable
$errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $usernameOrEmail = $_POST["username_email"];
    $password = $_POST["password"];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User found, verify the password
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];

        if (password_verify($password, $storedPassword)) {
            // Password is correct, log in the user
            // Perform any additional actions or set session variables

            // Redirect the user to following.php
            header("Location: following.php");
            exit();
        } else {
            // Incorrect password
            $errorMessage = "Incorrect password.";
        }
    } else {
        // User not found
        $errorMessage = "Invalid username or email.";
    }
}

// Close the database connection
$conn->close();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BackYourself</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bahnschrift+SemiBold&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
      .login-title {
        font-family: "Bahnschrift SemiBold", Arial, sans-serif;
        font-size: 36px;
        margin-bottom: 20px;
        text-align: center;
      }
      
      .login-button {
        margin-top: 10px;
        margin-bottom: 20px;
      }
      
      .forgot-password-link {
        margin-top: 10px;
      }
      
      .login-box {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: start;
      }
      
      .img-container {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 100vh;
      }
      
      .custom-image {
        width: 360px;
        height: 500px;
      }

      /* Custom class to adjust the form width */
      .custom-form-col {
        max-width: 70%; /* Adjust the value as needed */
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
          <div class="img-container justify-content-end">
            <img src="images/JB.jpg" alt="Image" class="img-fluid custom-image">
          </div>
        </div>
        <div class="col-md-6">
          <div class="login-box custom-form-col justify-content-start text-center">
            <h1 class="login-title">BackYourself</h1>

             <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>



            <form method="POST" action="login.php">
              <div class="mb-3">
                <input type="text" class="form-control" id="username-email" name="username_email" placeholder="Username or Email">
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
              <button type="submit" class="btn btn-secondary login-button">Log in</button>
              <div class="mb-3">
                <a href="#" class="forgot-password-link">Forgot your password?</a>
              </div>
              <hr class="divider">
              <div class="mb-3">
                <p class="signup-text">New to BackYourself? <a href="signup.html">Sign up</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>