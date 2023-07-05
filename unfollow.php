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

        // Get the user_id of the user to be unfollowed
        $unfollowedUsername = $_GET['username'];
        $unfollowedUserQuery = "SELECT user_id FROM users WHERE username = :username";
        $unfollowedUserStmt = $conn->prepare($unfollowedUserQuery);
        $unfollowedUserStmt->bindParam(':username', $unfollowedUsername, PDO::PARAM_STR);
        $unfollowedUserStmt->execute();
        $unfollowedUserResult = $unfollowedUserStmt->fetch(PDO::FETCH_ASSOC);
        $unfollowedUserId = $unfollowedUserResult['user_id'];
        $unfollowedUserStmt->closeCursor();

        // Check if the active user is following the user to be unfollowed
        $isFollowingQuery = "SELECT 1 FROM user_follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $isFollowingStmt = $conn->prepare($isFollowingQuery);
        $isFollowingStmt->bindParam(':follower_id', $activeUserId, PDO::PARAM_INT);
        $isFollowingStmt->bindParam(':followed_id', $unfollowedUserId, PDO::PARAM_INT);
        $isFollowingStmt->execute();
        $isFollowing = ($isFollowingStmt->rowCount() > 0);
        $isFollowingStmt->closeCursor();

        // If the active user is following, delete the record from the user_follows table
        if ($isFollowing) {
            $unfollowQuery = "DELETE FROM user_follows WHERE follower_id = :follower_id AND followed_id = :followed_id";
            $unfollowStmt = $conn->prepare($unfollowQuery);
            $unfollowStmt->bindParam(':follower_id', $activeUserId, PDO::PARAM_INT);
            $unfollowStmt->bindParam(':followed_id', $unfollowedUserId, PDO::PARAM_INT);
            $unfollowStmt->execute();
            $unfollowStmt->closeCursor();

            // Decrement the num_followers variable in the users table for the user who has been unfollowed
            $decrementFollowedQuery = "UPDATE users SET num_followers = num_followers - 1 WHERE user_id = :unfollowed_id";
            $decrementFollowedStmt = $conn->prepare($decrementFollowedQuery);
            $decrementFollowedStmt->bindParam(':unfollowed_id', $unfollowedUserId, PDO::PARAM_INT);
            $decrementFollowedStmt->execute();
            $decrementFollowedStmt->closeCursor();
        }

        // Redirect the user back to the profile page of the user they unfollowed
        header("Location: profile.php?username=" . urlencode($unfollowedUsername));
        exit();
    } catch (PDOException $e) {
        // Handle database query errors
        echo "Failed to unfollow user: " . $e->getMessage();
        exit();
    }
} else {
    // Redirect the user to the profile page if the 'username' parameter is missing
    header("Location: profile.php");
    exit();
}
?>
