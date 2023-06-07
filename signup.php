<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - BackYourself</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bahnschrift+SemiBold&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
      .signup-title {
        font-family: "Bahnschrift SemiBold", Arial, sans-serif;
        font-size: 36px;
        margin-bottom: 20px;
        text-align: center;
      }
      
      .signup-button {
        margin-top: 10px;
        margin-bottom: 20px;
      }
      
      .signup-box {
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
          <div class="signup-box custom-form-col justify-content-start text-center">
            <h1 class="signup-title">BackYourself</h1>

            <?php
              if (isset($_GET['error'])) {
                $errorMessage = $_GET['error'];
                echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
              }
            ?>

            <form action="signup_form.php" method="POST" onsubmit="return validateForm()">
              <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <div id="emailError" class="error-message"></div>
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" id="full-name" name="full-name" placeholder="Full Name" required>
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <div id="passwordError" class="error-message"></div>
              </div>
              <button type="submit" class="btn btn-primary signup-button">Sign Up</button>
              <hr class="divider">
              <div class="mb-3">
                <p class="login-text">Already have an account? <a href="login.html">Log in</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
  function validateForm() {
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var emailError = document.getElementById('emailError');
    var passwordError = document.getElementById('passwordError');
    var valid = true;

    emailError.innerHTML = '';
    passwordError.innerHTML = '';

    if (!validateEmail(emailInput.value)) {
      emailError.innerHTML = 'Please enter a valid email address.';
      valid = false;
    }

    if (passwordInput.value.length < 8) {
      passwordError.innerHTML = 'Password must be at least 8 characters long.';
      valid = false;
    }

    return valid;
  }

  function validateEmail(email) {
    // Use a regular expression to validate the email format
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>