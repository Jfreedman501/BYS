<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the selected option and poll ID from the form
  $option = $_POST['option'];
  $pollId = $_POST['poll_id'];

  // Redirect to poll_select.php with the selected option and poll ID as query parameters
  header("Location: poll_select.php?option=$option&poll_id=$pollId");
  exit();
}

// Establish a database connection (replace the placeholders with your own database credentials)
$host = 'localhost';
$db = 'dbntos1pjl9uqt';
$user = 'ubsmyehs1mgzl';
$password = 'Ekajdf15';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Failed to connect to the database: " . $e->getMessage();
    exit();
}

// Fetch the username, number of polls posted, number of followers, bio, and profile picture path for the active user
try {
    // Prepare the query
    $query = "SELECT username, num_polls_posted, num_followers, bio, profile_picture FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);

    // Bind the user_id parameter
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the username, num_polls_posted, num_followers, bio, and profile_picture
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $activeUsername = $result['username'];

    // Close the cursor (no need to close the connection)
    $stmt->closeCursor();
} catch (PDOException $e) {
    // Handle query execution errors
    echo "Failed to fetch user data: " . $e->getMessage();
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
  <title>Poll Feed - BackYourself</title>  
  <style>
    .main-content {
      padding-top: 45px; /* Adjust the top padding to accommodate the sticky nav */
    }

    .profile-details {
      display: flex;
      align-items: center;
      justify-content: center; /* Center horizontally */
      flex-direction: column; /* Align items vertically */
      margin-bottom: 20px;
    }

    .profile-picture-container {
      width: 100px;
      height: 100px;
      margin-right: 10px;
      overflow: hidden;
      border-radius: 0.5rem;
    }

    .profile-picture {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 0.5rem;
    }

    .user-details {
      display: flex;
      flex-direction: column;
      text-align: center;
      margin-top: 10px;
    }

    .user-name {
      font-family: 'Bahnschrift SemiBold', sans-serif;
    }

    .profile-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Bahnschrift SemiBold', sans-serif;
  }

  .vertical-line {
    width: 1px;
    height: 40px;
    background-color: #ccc;
    margin: 0 10px;
  }


    /* CSS styles from display_poll.php */
    * {
      box-sizing: border-box;
    }

    body {
      background-color: #f9f9f9;
      font-family: Arial, sans-serif;
    }

    .poll-container {
      max-width: 475px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .poll-header {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .poll-title {
      font-size: 24px;
      color: #333;
      margin: 0;
    }

    .poll-user {
      font-size: 18px;
      color: #777;
      margin: 0;
    }

    .poll-divider {
      height: 2px;
      background-color: #eee;
      margin-bottom: 20px;
    }

    .poll-images {
      display: flex;
      justify-content: center; /* Center align the images */
      flex-wrap: wrap;
      margin-bottom: 20px; /* Reduce the margin between images and description */
    }

    .poll-images img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      object-position: center;
      border-radius: 5px;
      margin: 5px; /* Adjust the margin between images */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .poll-images img:hover {
      transform: scale(1.05);
    }

    .poll-info {
      text-align: left; /* Align description to the left */
    }

    .poll-info p {
      color: #555;
      margin-bottom: 10px;
    }

    .poll-votes {
      font-size: 14px; /* Smaller font size */
      color: #777;
      margin: 0;
    }

    .sticky-nav {
      position: sticky;
      top: 0;
    }
    .vertical-rule {
      position: sticky;
      top: 0;
      height: 100vh; /* Adjust the height as needed */
      background-color: #f9f9f9;
      z-index: 1;
    }
    .feed-header {
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Bahnschrift SemiBold', sans-serif;
      font-size: 20px;
      margin-bottom: 5px;
    }

    .subcategory-list {
      text-align: center;
      font-size: 14px;
      color: #777;
      margin-bottom: 20px;
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
      <div class="col-8 main-content">
      <div class="feed-header">
      <h1 style="font-family: 'Bahnschrift SemiBold', sans-serif;">Feed</h1>
    </div>

    <?php
// Retrieve the category and subcategories from the query string
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategories = isset($_GET['subcategory']) ? explode(',', $_GET['subcategory']) : array();

// Display the category and subcategories
echo '<div class="subcategory-list">';
echo '<p>Category: ' . $category . '</p>';
echo '<p>Subcategories: ';
echo implode(', ', $subcategories);
echo '</p>';
echo '</div>';
?>

        <hr class="my-4">

        <div class="polls-section">

          <?php
// Retrieve the subcategories from the query string
$subcategories = isset($_GET['subcategory']) ? explode(',', $_GET['subcategory']) : array();

// Prepare the subcategory placeholders for the query
$subcategoryPlaceholders = implode(',', array_fill(0, count($subcategories), '?'));

// Create an array of category and subcategory values for binding
$bindValues = array($_GET['category']);
$bindValues = array_merge($bindValues, $subcategories);

// Construct the SQL query with the appropriate number of subcategory placeholders and sort by posed_timestamp in descending order
$query = "SELECT p.*, u.username FROM polls p
          INNER JOIN users u ON p.user_id = u.user_id
          WHERE p.category = ? AND p.subcategory IN ($subcategoryPlaceholders)
          ORDER BY p.posted_timestamp DESC";
$stmt = $conn->prepare($query);
$stmt->execute($bindValues);
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (!empty($polls)) {
    foreach ($polls as $poll) {
                // Check if the user has already answered the poll
                $query = "SELECT * FROM user_polls WHERE user_id = :user_id AND poll_id = :poll_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->bindParam(':poll_id', $poll['poll_id'], PDO::PARAM_INT);
                $stmt->execute();
                $response = $stmt->fetch(PDO::FETCH_ASSOC);
                $userHasAnswered = !empty($response);

                // Display each poll
                echo '<div class="poll-container">';
                echo '<div class="poll-header">';
                echo '<h2 class="poll-title">' . $poll['poll_title'] . '</h2>';
                echo '<h3 class="poll-user">By: <a href="profile.php?username=' . urlencode($poll['username']) . '" class="btn btn-primary btn-sm">' . $poll['username'] . '</a></h3>';
                echo '</div>';

                // Display the dividing line
                echo '<div class="poll-divider"></div>';

                // Display the poll images
                echo '<div class="poll-images">';
                echo '<form action="" method="post">';
                echo '<input type="hidden" name="poll_id" value="' . $poll['poll_id'] . '">';
                for ($i = 1; $i <= $poll['num_options']; $i++) {
                    $image = 'poll_pictures/' . $poll['image' . $i];
                    echo '<button type="submit" name="option" value="' . $i . '">';
                    // Check if the user has answered the poll
                    if ($userHasAnswered) {
                        // Apply styling for the gray cover and display the percentage of responses
                        echo '<div style="position: relative;">';
                        echo '<img src="' . $image . '" alt="Option ' . $i . '">';
                        echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); text-align: center; color: #fff; font-weight: bold; display: flex; align-items: center; justify-content: center;">';
                        if ($poll['total_responses'] > 0) {
                          $optionPercentage = ($poll['option' . $i . '_responses'] / $poll['total_responses']) * 100;
                          echo round($optionPercentage, 2) . '%';
                        } else {
                          echo '0%';
                        }

                        echo '</div>';
                        echo '</div>';
                    } else {
                        // Display the image without any styling
                        echo '<img src="' . $image . '" alt="Option ' . $i . '">';
                    }
                    echo '</button>';
                }
                echo '</form>';
                echo '</div>';

                // Display the dividing line
                echo '<div class="poll-divider"></div>';

                // Display the poll information
                echo '<div class="poll-info">';
                echo '<p>' . $poll['poll_description'] . '</p>';
                echo '</div>';

                // Display the total votes
                echo '<p class="poll-votes">Total Votes: ' . $poll['total_responses'] . '</p>';

                echo '</div>'; // Close poll-container
            }
} else {
    echo 'There are no posted polls for this subcategory. Click "Create New" to make the first one!';
}
?>

        </div>

        <hr class="my-4">

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>