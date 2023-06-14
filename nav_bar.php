<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">  
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-2 sticky-nav d-flex flex-column justify-content-center align-items-center">
          <h1 class="nav-title">BackYourself</h1>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="following.php">Following</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cats.php">Polls by Category</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="search.php">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create.php">+ Create New</a>
            </li>
          </ul>
          <div class="spacer"></div>
          <button class="btn btn-light" onclick="logout()">Logout</button>
          <script>
            function logout() {
            // Make a GET request to logout.php
            window.location.href = "logout.php";
            }
          </script>
        </div>
        <div class="col-1 vertical-rule"></div>
        <div class="col-8 main-content d-flex flex-column justify-content-center align-items-center">




          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>





        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
