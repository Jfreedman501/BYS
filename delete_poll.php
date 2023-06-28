<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Database connection configuration
$servername = "localhost";
$username = "ubsmyehs1mgzl";
$password = "Ekajdf15";
$dbname = "dbntos1pjl9uqt";

try {
    // Create a PDO instance and establish the database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle any errors that occur during the database connection
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the poll_id is set and not empty
    if (isset($_POST['poll_id']) && !empty($_POST['poll_id'])) {
        $pollId = $_POST['poll_id'];

        // Get the user_id of the active user
        $userId = $_SESSION['user_id'];

        // Delete the poll from the database
        $query = "DELETE FROM polls WHERE poll_id = :poll_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':poll_id', $pollId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Poll deletion successful

            // Update the num_polls_posted variable for the active user
            $updateQuery = "UPDATE users SET num_polls_posted = num_polls_posted - 1 WHERE user_id = :user_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $updateStmt->execute();

            header("Location: profile.php");
            exit();
        } else {
            // Poll deletion failed
            echo "Failed to delete the poll.";
        }
    } else {
        // Invalid request, poll_id not provided
        echo "Invalid request.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
?>
