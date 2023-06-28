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

        // Delete the corresponding record from the followers table
        $unfollowQuery = "DELETE FROM followers WHERE follower_id = :follower_id AND following_id = :following_id";
        $unfollowStmt = $conn->prepare($unfollowQuery);
        $unfollowStmt->bindParam(':follower_id', $activeUserId, PDO::PARAM_INT);
        $unfollowStmt->bindParam(':following_id', $unfollowedUserId, PDO::PARAM_INT);
        $unfollowStmt->execute();
        $unfollowStmt->closeCursor();

        // Redirect the user back to the profile page of the user they unfollowed
        header("Location: profile.php?username=$unfollowedUsername");
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
