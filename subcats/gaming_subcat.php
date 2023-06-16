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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="../stylesheet.css">
  <title>Gaming Polls - BackYourself</title>
  <style>
    .category-text {
      font-family: "Bahnschrift SemiBold", Arial, sans-serif;
      font-size: 36px;
    }

    .btn-secondary {
      background-color: #6c757d;
    }

    .main-content {
      padding-top: 60px; /* Adjust the top padding to accommodate the sticky nav */
      height: 90vh;
    }

    .main-content p {
      margin-bottom: 20px;
    }

    .button-layout {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-top: 30px;
      width: 100%; /* Adjust the width to accommodate the desired number of lines */
      max-width: 600px; /* Example: Set a maximum width for better responsiveness */
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
            <a class="nav-link" href="../following.php">Following</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../cats.php">Polls by Category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../search.php">Search</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../profile.php">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../create.php">+ Create New</a>
          </li>
        </ul>
        <div class="spacer"></div>
        <button class="btn btn-light" onclick="logout()">Logout</button>
          <script>
            function logout() {
            // Make a GET request to logout.php
            window.location.href = "../logout.php";
            }
          </script>
      <div class="col-1 vertical-rule"></div>
      <div class="col-8 main-content d-flex flex-column justify-content-center align-items-center">
        <h2 class="category-text">Gaming Subcategories:</h2>
        <div class="button-layout">
          <button id="button-1" class="btn btn-primary" onclick="toggleButton(1)">Board Games</button>
          <button id="button-2" class="btn btn-primary" onclick="toggleButton(2)">Card Games</button>
          <button id="button-3" class="btn btn-primary" onclick="toggleButton(3)">Video Games</button>
          <button id="button-4" class="btn btn-primary" onclick="toggleButton(4)">Pokemon</button>
          <button id="button-5" class="btn btn-primary" onclick="toggleButton(5)">Mario</button>
          <button id="button-6" class="btn btn-primary" onclick="toggleButton(6)">Minecraft</button>
          <button id="button-7" class="btn btn-primary" onclick="toggleButton(7)">Consoles</button>
          <button id="button-8" class="btn btn-primary" onclick="toggleButton(8)">Other</button>
          <!-- Add more buttons as needed -->
        </div>
        <button id="select-all-button" class="btn btn-light mt-3" onclick="toggleAllButtons()">Select All</button>
        <button class="btn btn-light mt-3" onclick="submitForm()">Submit</button>
      </div>
    </div>
  </div>

  <script>
    let selectedButtons = [];

    function toggleButton(buttonId) {
      const button = document.getElementById(`button-${buttonId}`);
      if (selectedButtons.includes(buttonId)) {
        button.classList.remove("btn-secondary");
        button.classList.add("btn-primary");
        selectedButtons = selectedButtons.filter(id => id !== buttonId);
      } else {
        button.classList.remove("btn-primary");
        button.classList.add("btn-secondary");
        selectedButtons.push(buttonId);
      }
      updateSelectAllButton();
    }

    function toggleAllButtons() {
      const buttons = document.querySelectorAll(".button-layout button");
      if (selectedButtons.length === buttons.length) {
        buttons.forEach(button => {
          button.classList.remove("btn-secondary");
          button.classList.add("btn-primary");
        });
        selectedButtons = [];
      } else {
        buttons.forEach(button => {
          button.classList.remove("btn-primary");
          button.classList.add("btn-secondary");
        });
        selectedButtons = Array.from(buttons).map(button => parseInt(button.id.replace("button-", "")));
      }
      updateSelectAllButton();
    }

    function updateSelectAllButton() {
      const selectAllButton = document.getElementById("select-all-button");
      if (selectedButtons.length === 0) {
        selectAllButton.textContent = "Select All";
      } else if (selectedButtons.length === document.querySelectorAll(".button-layout button").length) {
        selectAllButton.textContent = "Deselect All";
      }
    }

    function submitForm() {
      window.location.href = "feed.html";
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>
