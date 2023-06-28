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

// Check if the 'username' parameter exists in the query string
if (isset($_GET['username'])) {
    try {
        // Get the user_id of the active user
        $activeUserId = $_SESSION['user_id'];

        // Get the user_id of the user to be followed
        $followedUsername = $_GET['username'];
        $followedUserQuery = "SELECT user_id FROM users WHERE username = :username";
        $followedUserStmt = $conn->prepare($followedUserQuery);
        $followedUserStmt->bindParam(':username', $followedUsername, PDO::PARAM_STR);
        $followedUserStmt->execute();
        $followedUserResult = $followedUserStmt->fetch(PDO::FETCH_ASSOC);
        $followedUserId = $followedUserResult['user_id'];
        $followedUserStmt->closeCursor();

        // Check if the active user is already following the user to be followed
        $isFollowingQuery = "SELECT 1 FROM user_follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $isFollowingStmt = $conn->prepare($isFollowingQuery);
        $isFollowingStmt->bindParam(':follower_id', $activeUserId, PDO::PARAM_INT);
        $isFollowingStmt->bindParam(':followed_id', $followedUserId, PDO::PARAM_INT);
        $isFollowingStmt->execute();
        $isFollowing = ($isFollowingStmt->rowCount() > 0);
        $isFollowingStmt->closeCursor();

        // If the active user is not already following, insert a new record into the user_follows table
        if (!$isFollowing) {
            $followQuery = "INSERT INTO user_follows (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
            $followStmt = $conn->prepare($followQuery);
            $followStmt->bindParam(':follower_id', $activeUserId, PDO::PARAM_INT);
            $followStmt->bindParam(':followed_id', $followedUserId, PDO::PARAM_INT);
            $followStmt->execute();
            $followStmt->closeCursor();

            // Increment the num_followers variable in the users table for the user who has been followed
            $incrementFollowersQuery = "UPDATE users SET num_followers = num_followers + 1 WHERE user_id = :followed_id";
            $incrementFollowersStmt = $conn->prepare($incrementFollowersQuery);
            $incrementFollowersStmt->bindParam(':followed_id', $followedUserId, PDO::PARAM_INT);
            $incrementFollowersStmt->execute();
            $incrementFollowersStmt->closeCursor();
        }

        // Redirect the user back to the profile page of the user they followed
        header("Location: profile.php?username=" . urlencode($followedUsername));
        exit();
    } catch (PDOException $e) {
        // Handle database query errors
        echo "Failed to follow user: " . $e->getMessage();
        exit();
    }
} else {
    // Redirect the user to the profile page if the 'username' parameter is missing
    header("Location: profile.php");
    exit();
}
?>
