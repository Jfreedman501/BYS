<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
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
    $numPollsPosted = $result['num_polls_posted'];
    $numFollowers = $result['num_followers'];
    $bio = $result['bio'];
    $profilePicture = $result['profile_picture'];

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
  <title>@<?php echo $activeUsername; ?> - BackYourself</title>  
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


  </style>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    var pollsPosted = <?php echo $numPollsPosted; ?>;
    var followers = <?php echo $numFollowers; ?>;

    function formatNumber(number) {
      if (number >= 1000000) {
        return (number / 1000000).toFixed(1) + "M";
      } else if (number >= 1000) {
        return (number / 1000).toFixed(1) + "K";
      } else {
        return number.toString();
      }
    }

    var pollsPostedElement = document.getElementById("polls-posted");
    var followersElement = document.getElementById("followers");

    pollsPostedElement.textContent = formatNumber(pollsPosted) + " Polls Posted";
    followersElement.textContent = formatNumber(followers) + " Followers";
  });
</script>

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

        <div class="profile-details">
          <div class="d-flex align-items-center">
            <div class="profile-picture-container">
              <img class="profile-picture" src="<?php echo $profilePicture ? 'profile_pictures/'.$profilePicture : 'profile_pictures/default.jpg'; ?>" alt="Profile Picture">
            </div>
            <div class="user-details">
              <h2 class="user-name"><?php echo $activeUsername; ?></h2>
              <div class="btn-group" role="group" aria-label="Profile Buttons">
                <a href="edit_profile.php" class="btn btn-light btn-sm me-2 rounded">Edit Profile</a>
                <a href="answered_polls.php" class="btn btn-light btn-sm rounded">Answered Polls</a>
              </div>
            </div>
          </div>
        </div>

        <?php if (!empty($bio)): ?>
          <div class="d-flex justify-content-center">
            <div class="card mb-3" style="width: 300px;">
              <div class="card-body p-2">
                <p class="card-text m-0"><?php echo $bio; ?></p>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div class="profile-stats">
          <?php
            // Fetch the number of polls posted and followers from the database
            $pollsPosted = $result['num_polls_posted'];
            $followers = $result['num_followers'];
          ?>
          <div class="profile-stat">
            <h3 id="polls-posted"><?php echo $pollsPosted; ?> Polls Posted</h3>
          </div>
          <div class="vertical-line"></div>
          <div class="profile-stat">
            <h3 id="followers"><?php echo $followers; ?> Followers</h3>
          </div>
        </div>

        <hr class="my-4">

        <div class="polls-section">
          <?php
            // Establish a database connection
            $connection = mysqli_connect($host, $user, $password, $db);

            if (!$connection) {
              die("Connection failed: " . mysqli_connect_error());
            }

            // Retrieve the polls for the active user from the table
            $query = "SELECT p.*, u.username FROM polls p
              INNER JOIN users u ON p.user_id = u.user_id
              WHERE u.username = :username"; // Assuming the username column is used to match the active user's username
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $activeUsername, PDO::PARAM_STR);
            $stmt->execute();
            $polls = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($polls)) {
              foreach ($polls as $poll) {
              // Display each poll
              echo '<div class="poll-container">';
              echo '<div class="poll-header">';
              echo '<h2 class="poll-title">' . $poll['poll_title'] . '</h2>';
              echo '<h3 class="poll-user">By: ' . $poll['username'] . '</h3>';
              echo '</div>';

              // Display the dividing line
              echo '<div class="poll-divider"></div>';

              // Display the poll images
              echo '<div class="poll-images">';
              for ($i = 1; $i <= $poll['num_options']; $i++) {
                $image = 'poll_pictures/' . $poll['image' . $i];
                echo '<img src="' . $image . '" alt="Option ' . $i . '">';
              }
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
              echo 'No polls found.';
            }

            // Close the database connection
            mysqli_close($connection);
          ?>
        </div>

        <hr class="my-4">

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
