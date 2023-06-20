<!DOCTYPE html>
<html>
<head>
  <style>
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
</head>
<body>
<?php
// Replace the database credentials with your own
$host = 'localhost';
$username = 'ubsmyehs1mgzl';
$password = 'Ekajdf15';
$database = 'dbntos1pjl9uqt';

// Establish a database connection
$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the first poll information from the table
$query = "SELECT p.*, u.username FROM polls p
          INNER JOIN users u ON p.user_id = u.user_id
          LIMIT 1";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
  $poll = mysqli_fetch_assoc($result);

  // Display the poll information
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
} else {
  echo 'No polls found.';
}

// Close the database connection
mysqli_close($connection);
?>
</body>
</html>
