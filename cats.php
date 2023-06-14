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
          <h2 class="category-text">Select a Category</h2>
          <div class="button-layout">
            <!-- Dynamically generate buttons -->
            <button id="button-1" class="btn btn-primary" onclick="selectButton(1)">Sports</button>
            <button id="button-2" class="btn btn-primary" onclick="selectButton(2)">Music</button>
            <button id="button-3" class="btn btn-primary" onclick="selectButton(3)">Movies</button>
            <button id="button-4" class="btn btn-primary" onclick="selectButton(4)">TV</button>
            <button id="button-5" class="btn btn-primary" onclick="selectButton(5)">Gaming</button>
            <button id="button-6" class="btn btn-primary" onclick="selectButton(6)">Food</button>
            <button id="button-7" class="btn btn-primary" onclick="selectButton(7)">Politics</button>
            <button id="button-8" class="btn btn-primary" onclick="selectButton(8)">Fashion</button>
            <button id="button-9" class="btn btn-primary" onclick="selectButton(9)">Fitness</button>
            <button id="button-10" class="btn btn-primary" onclick="selectButton(10)">Literature</button>
            <button id="button-11" class="btn btn-primary" onclick="selectButton(11)">Travel</button>
            <button id="button-12" class="btn btn-primary" onclick="selectButton(12)">Arts</button>
            <button id="button-13" class="btn btn-primary" onclick="selectButton(13)">History</button>
            <button id="button-14" class="btn btn-primary" onclick="selectButton(14)">Animals</button>
            <button id="button-15" class="btn btn-primary" onclick="selectButton(15)">Business</button>
            <button id="button-16" class="btn btn-primary" onclick="selectButton(16)">Home + Decor</button>
            <button id="button-17" class="btn btn-primary" onclick="selectButton(17)">Random</button>
            <!-- Add more buttons as needed -->
          </div>
          <button class="btn btn-light mt-3" onclick="submitForm()">Submit</button>
        </div>
      </div>
    </div>

    <script>
      let selectedButton = null;

      function selectButton(buttonId) {
        if (selectedButton !== null) {
          // Deselect the previously selected button
          selectedButton.classList.remove("btn-secondary");
          selectedButton.classList.add("btn-primary");
        }

        // Select the clicked button
        selectedButton = document.getElementById(`button-${buttonId}`);
        selectedButton.classList.remove("btn-primary");
        selectedButton.classList.add("btn-secondary");
      }

      function submitForm() {
        if (selectedButton !== null) {
          const buttonId = selectedButton.id.replace("button-", "");
          let categoryName;
          
          switch (buttonId) {
            case "1":
              categoryName = "sports";
              break;
            case "2":
              categoryName = "music";
              break;
            case "3":
              categoryName = "movies";
              break;
            case "4":
              categoryName = "tv";
              break;
            case "5":
              categoryName = "gaming";
              break;
            case "6":
              categoryName = "food";
              break;
            case "7":
              categoryName = "politics";
              break;
            case "8":
              categoryName = "fashion";
              break;
            case "9":
              categoryName = "fitness";
              break;
            case "10":
              categoryName = "literature";
              break;
            case "11":
              categoryName = "travel";
              break;
            case "12":
              categoryName = "arts";
              break;
              case "13":
              categoryName = "history";
              break;
            case "14":
              categoryName = "animals";
              break;
            case "15":
              categoryName = "business";
              break;
            case "16":
              categoryName = "home";
              break;
            case "17":
              categoryName = "random";
              break;
            default:
              categoryName = ""; // Set default category name
          }

          if (categoryName !== "") {
            // Redirect to the subcategory page based on the selected button
            window.location.href = `subcats/${categoryName}_subcat.php`;
          } else {
            alert("Category not found.");
          }
        } else {
          alert("Please select a category.");
        }
      }
    </script>

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
        height: 85vh;
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
      }
    </style>
  </body>
</html>
