<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Establish a database connection (replace with your database credentials)
$servername = "localhost";
$username = "ubsmyehs1mgzl";
$password = "Ekajdf15";
$dbname = "dbntos1pjl9uqt";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search for usernames in the database
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT username FROM users WHERE username LIKE '$search%' ORDER BY num_followers DESC";
    $result = $conn->query($sql);
    $usernames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row['username'];
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Search - BackYourself</title>
    <style>
        .search-bar {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .dropdown-menu.search-results {
            top: 100%;
            left: 0;
            position: absolute;
            width: 100%;
        }
    </style>
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

            <!-- Search bar -->
            <form action="search_results.php" method="GET" class="search-bar">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search usernames" name="search" oninput="updateDropdown(this.value)">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
                <div id="username-dropdown" class="dropdown-menu search-results"></div> <!-- Add class "dropdown-menu" and "search-results" -->
            </form>

        </div>
    </div>
</div>
<script>
let xhr; // Declare xhr variable outside the function

function updateDropdown(search) {
  const dropdown = document.getElementById('username-dropdown');
  dropdown.innerHTML = '';

  if (search.length === 0) {
    dropdown.style.display = 'none'; // Hide the dropdown if the search query is empty
    return;
  }

  // Abort any ongoing XMLHttpRequest
  if (xhr && xhr.readyState !== 4) {
    xhr.abort();
  }

  xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const usernames = JSON.parse(xhr.responseText);
      if (usernames.length > 0) {
        dropdown.style.display = 'block'; // Show the dropdown if there are matching usernames
        usernames.forEach(function (username) {
          const option = document.createElement('div');
          option.textContent = username;
          dropdown.appendChild(option);
        });
      } else {
        dropdown.style.display = 'none'; // Hide the dropdown if no matching usernames found
      }
    }
  };
  xhr.open('GET', 'get_usernames.php?search=' + encodeURIComponent(search), true);
  xhr.send();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
