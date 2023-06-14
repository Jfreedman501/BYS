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

    // Close the cursor and database connection
    $stmt->closeCursor();
    $conn = null;
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
    <style>
      .main-content {
        padding-top: 60px; /* Adjust the top padding to accommodate the sticky nav */
        height: 50vh;
      }
      .profile-section {
        display: flex;
        align-items: center;
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
      }

      .edit-profile a {
        color: #777;
      }

      .user-name {
        font-family: 'Bahnschrift SemiBold', sans-serif;
      }

      .divider {
        border-top: 1px solid #888;
        width: 50%;
        margin: 20px 0;
      }

      .bio-section {
        text-align: center;
      }

      .profile-stats {
        display: flex;
        align-items: center;
        margin-top: 10px;
      }

      .profile-stat {
        flex: 1;
        text-align: center;
      }

      .profile-stat h3 {
        font-size: 18px;
        margin: 0;
        font-family: 'Bahnschrift SemiBold', sans-serif;
      }

      .vertical-line {
        border-left: 1px solid #888;
        height: 30px;
        margin: 0 10px;
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
        <div class="col-8 main-content d-flex flex-column justify-content-center align-items-center">
          <div class="profile-section">
            <div class="profile-picture-container">
              <img class="profile-picture" src="<?php echo $profilePicture ? 'profile_pictures/'.$profilePicture : 'profile_pictures/default.jpg'; ?>" alt="Profile Picture">
            </div>
            <div class="user-details">
              <h2 class="user-name"><?php echo $activeUsername; ?></h2>
              <p class="edit-profile"><a href="edit_profile.php">Edit Profile</a></p>
            </div>
          </div>
          <hr class="divider">
          <?php if (!empty($bio)) : ?>
            <div class="bio-section">
                <p><?php echo $bio; ?></p>
            </div>
            <hr class="divider">
        <?php endif; ?>
          <div class="profile-stats">
            <div class="profile-stat">
              <h3 style="font-size: 24px; white-space: nowrap;" id="polls-posted"></h3>
            </div>
            <div class="vertical-line"></div>
            <div class="profile-stat">
              <h3 style="font-size: 24px; white-space: nowrap;" id="followers"></h3>
            </div>
          </div>
          <hr class="divider">
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
