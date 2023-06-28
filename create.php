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
    <title>Create New Poll - BackYourself</title>
    <style>
      .main-content {
        padding-top: 60px; /* Adjust the top padding to accommodate the sticky nav */
        height: 85vh;
      }
      .main-title {
        font-family: "Bahnschrift SemiBold", Arial, sans-serif;
        font-size: 36px;
        margin-bottom: 20px;
      }
      .form-group-column {
        display: flex;
        flex-direction: column;
      }
      .error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
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
        <h1 class="main-title">Create a Poll</h1>
        
        <form action="create_poll_form.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-4 form-group-column">
              <!-- Toggle Button for Number of Choices -->
              <div class="form-group">
                <label for="numberOfChoices">Number of Choices:</label>
                <select class="form-control" id="numberOfChoices" name="numberOfChoices">
                  <option value="2" selected>2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>

              <!-- Image Upload Buttons -->
              <div id="imageUpload">
                <label for="choice1Image">Choice 1 Image:</label>
                <input type="file" id="choice1Image" name="choice1Image" accept="image/*"><br>

                <label for="choice2Image">Choice 2 Image:</label>
                <input type="file" id="choice2Image" name="choice2Image" accept="image/*"><br>

                <!-- Additional Image Upload Buttons will be added dynamically -->
              </div>
            </div>
            <div class="col-8">
              <!-- Poll Title -->
              <div class="form-group">
                <label for="pollTitle">Poll Title (50 characters max):</label>
                <input type="text" class="form-control" id="pollTitle" name="pollTitle" maxlength="50">
                <div id="titleError" class="error"></div>
              </div>

              <!-- Poll Description -->
              <div class="form-group">
                <label for="pollDescription">Poll Description (500 characters max):</label>
                <textarea class="form-control" id="pollDescription" name="pollDescription" rows="3" maxlength="500"></textarea>
              </div>

              <!-- Category -->
              <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category">
                  <option value="" selected disabled>Select a category</option>
                  <option value="Sports">Sports</option>
                  <option value="Music">Music</option>
                  <option value="Movies">Movies</option>
                  <option value="TV">TV</option>
                  <option value="Gaming">Gaming</option>
                  <option value="Food">Food</option>
                  <option value="Politics">Politics</option>
                  <option value="Fashion">Fashion</option>
                  <option value="Fitness">Fitness</option>
                  <option value="Literature">Literature</option>
                  <option value="Travel">Travel</option>
                  <option value="Arts">Arts</option>
                  <option value="History">History</option>
                  <option value="Animals">Animals</option>
                  <option value="Business">Business</option>
                  <option value="Home + Decor">Home + Decor</option>
                  <!-- Add more category options here -->
                </select>
                <div id="categoryError" class="error"></div>
              </div>

              <!-- Subcategory -->
              <div class="form-group">
                <label for="subcategory">Subcategory:</label>
                <select class="form-control" id="subcategory" name="subcategory">
                  <option value="" selected disabled>Select a subcategory</option>
                  <!-- Subcategory options will be added dynamically based on the selected category -->
                </select>
                <div id="subcategoryError" class="error"></div>
              </div>
            </div>
          </div>
          <div id="submitButtonError" class="error"></div>
          <button class="btn btn-primary" id="submitButton">Submit</button>
          </form>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        var numberOfChoicesSelect = document.getElementById("numberOfChoices");
        var imageUploadContainer = document.getElementById("imageUpload");
        var categorySelect = document.getElementById("category");
        var subcategorySelect = document.getElementById("subcategory");
        var titleInput = document.getElementById("pollTitle");
        var submitButton = document.getElementById("submitButton");

        numberOfChoicesSelect.addEventListener("change", function() {
          var numChoices = parseInt(numberOfChoicesSelect.value);

          // Clear existing image upload fields
          imageUploadContainer.innerHTML = "";

          // Add image upload fields based on the selected number of choices
          for (var i = 1; i <= numChoices; i++) {
            var label = document.createElement("label");
            label.textContent = "Choice " + i + " Image:";
            imageUploadContainer.appendChild(label);

            var input = document.createElement("input");
            input.type = "file";
            input.id = "choice" + i + "Image";
            input.name = "choice" + i + "Image";
            input.accept = "image/*";
            imageUploadContainer.appendChild(input);

            var br = document.createElement("br");
            imageUploadContainer.appendChild(br);
          }
        });

        categorySelect.addEventListener("change", function() {
          var selectedCategory = categorySelect.value;

          // Clear existing subcategory options
          subcategorySelect.innerHTML = "";

          // Add subcategory options based on the selected category
          if (selectedCategory === "Sports") {
            addSubcategoryOption("Football");
            addSubcategoryOption("Basketball");
            addSubcategoryOption("Soccer");
            addSubcategoryOption("Baseball");
            addSubcategoryOption("Tennis");
            addSubcategoryOption("Golf");
            addSubcategoryOption("Cricket");
            addSubcategoryOption("Olympics");
            addSubcategoryOption("F1");
            addSubcategoryOption("MMA");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Music") {
            addSubcategoryOption("Pop");
            addSubcategoryOption("Rock");
            addSubcategoryOption("Hip-hop + Rap");
            addSubcategoryOption("Country");
            addSubcategoryOption("R&B + Soul");
            addSubcategoryOption("Classical");
            addSubcategoryOption("Jazz");
            addSubcategoryOption("EDM");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Movies") {
            addSubcategoryOption("Actors");
            addSubcategoryOption("Movies");
            addSubcategoryOption("Characters");
            addSubcategoryOption("Marvel");
            addSubcategoryOption("Star Wars");
            addSubcategoryOption("Pixar");
            addSubcategoryOption("Horror");
            addSubcategoryOption("Disney");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "TV") {
            addSubcategoryOption("Actors");
            addSubcategoryOption("Shows");
            addSubcategoryOption("Characters");
            addSubcategoryOption("Networks");
            addSubcategoryOption("Reality Shows");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Gaming") {
            addSubcategoryOption("Board Games");
            addSubcategoryOption("Card Games");
            addSubcategoryOption("Video Games");
            addSubcategoryOption("Pokemon");
            addSubcategoryOption("Mario");
            addSubcategoryOption("Minecraft");
            addSubcategoryOption("Consoles");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Food") {
            addSubcategoryOption("Desserts");
            addSubcategoryOption("Italian Cuisine");
            addSubcategoryOption("Asian Cuisine");
            addSubcategoryOption("Mexican Cuisine");
            addSubcategoryOption("Vegetarian + Vegan");
            addSubcategoryOption("Healthy Eating");
            addSubcategoryOption("Fast Food");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Politics") {
            addSubcategoryOption("U.S. Politics");
            addSubcategoryOption("International Politics");
            addSubcategoryOption("Political Ideologies");
            addSubcategoryOption("Political Figures");
            addSubcategoryOption("Political Activism");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Fashion") {
            addSubcategoryOption("Cloting Styles");
            addSubcategoryOption("Acessories");
            addSubcategoryOption("Designers");
            addSubcategoryOption("Trends");
            addSubcategoryOption("Beauty + Makeup");
            addSubcategoryOption("Street Style");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Fitness") {
            addSubcategoryOption("Cardio");
            addSubcategoryOption("Strength Training");
            addSubcategoryOption("Hip-hop + Rap");
            addSubcategoryOption("Yoga + Pilates");
            addSubcategoryOption("Workout Equipment");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Literature") {
            addSubcategoryOption("Fiction");
            addSubcategoryOption("Nonfiction");
            addSubcategoryOption("Classic Literature");
            addSubcategoryOption("Poetry");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Travel") {
            addSubcategoryOption("Modes of Transport");
            addSubcategoryOption("All Destinations");
            addSubcategoryOption("Beach Vacations");
            addSubcategoryOption("Budget Travel");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Arts") {
            addSubcategoryOption("Painting");
            addSubcategoryOption("Sculpture");
            addSubcategoryOption("Photography");
            addSubcategoryOption("Digital Art");
            addSubcategoryOption("Architecture");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "History") {
            addSubcategoryOption("Ancient History");
            addSubcategoryOption("Medieval History");
            addSubcategoryOption("World Wars");
            addSubcategoryOption("Historical Figures");
            addSubcategoryOption("Historical Events");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Animals") {
            addSubcategoryOption("Pets");
            addSubcategoryOption("Land Animals");
            addSubcategoryOption("Sea Animals");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Business") {
            addSubcategoryOption("Entrepreneurship");
            addSubcategoryOption("Startups");
            addSubcategoryOption("Marketing + Advertising");
            addSubcategoryOption("Finance");
            addSubcategoryOption("Leadership");
            addSubcategoryOption("Other");
          } else if (selectedCategory === "Home + Decor") {
            addSubcategoryOption("Interior Design");
            addSubcategoryOption("DIY Projects");
            addSubcategoryOption("Home Organization");
            addSubcategoryOption("Home Renovation");
            addSubcategoryOption("Furniture + Accessories");
            addSubcategoryOption("Gardening");
            addSubcategoryOption("Other");
          }
          // Add more category-specific subcategory options here

          function addSubcategoryOption(subcategory) {
            var option = document.createElement("option");
            option.value = subcategory;
            option.textContent = subcategory;
            subcategorySelect.appendChild(option);
          }
        });

    submitButton.addEventListener("click", function(event) {
        event.preventDefault();
        clearErrors();

        var numChoices = parseInt(numberOfChoicesSelect.value);
        var imageInputs = [];
        var title = titleInput.value.trim();
        var category = categorySelect.value;
        var subcategory = subcategorySelect.value;

        // Get the selected image inputs
        for (var i = 1; i <= numChoices; i++) {
          var inputId = "choice" + i + "Image";
          var input = document.getElementById(inputId);
          imageInputs.push(input);
        }

        var hasValidImages = validateImages(imageInputs);
        var hasValidTitle = validateTitle(title);
        var hasValidCategory = validateCategory(category);
        var hasValidSubcategory = validateSubcategory(subcategory);

        if (hasValidImages && hasValidTitle && hasValidCategory && hasValidSubcategory) {
            // Form is valid, proceed with submission
            event.target.closest('form').submit();
        } else {
          // Form is invalid, display error messages
          if (!hasValidImages) {
            displayError("Please upload " + numChoices + " images.");
          }
          if (!hasValidTitle) {
            displayError("Please enter a title.");
          }
          if (!hasValidCategory) {
            displayError("Please select a category.");
          }
          if (!hasValidSubcategory) {
            displayError("Please select a subcategory.");
          }
        }
      });


        function validateImages(inputs) {
          var numChoices = parseInt(numberOfChoicesSelect.value);
          var count = 0;

          for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].files.length > 0) {
              count++;
            }
          }

          return count === numChoices;
        }

        function validateTitle(title) {
          return title.length > 0;
        }

        function validateCategory(category) {
          return category.length > 0;
        }

        function validateSubcategory(subcategory) {
          return subcategory.length > 0;
        }

        function clearErrors() {
          document.getElementById("submitButtonError").textContent = "";
          document.getElementById("titleError").textContent = "";
          document.getElementById("categoryError").textContent = "";
          document.getElementById("subcategoryError").textContent = "";
        }

        function displayError(message) {
          var errorContainer = document.getElementById("submitButtonError");
          var errorElement = document.createElement("div");
          errorElement.classList.add("error-message");
          errorElement.textContent = message;
          errorContainer.appendChild(errorElement);
        }
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-+XOD1UoA3+h8N4Y4hU4UtiMnxSreF4g1IpbAxQTVHs8Vm1sZpVDq3LlMhcr30QlP" crossorigin="anonymous"></script>
  </body>
</html>
