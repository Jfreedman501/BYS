<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Establish a database connection
$host = "localhost";
$dbname = "dbntos1pjl9uqt";
$username = "ubsmyehs1mgzl";
$password = "Ekajdf15";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the selected option and poll ID from the form
        $option = $_POST['option'];
        $pollId = $_POST['poll_id'];

        // Process the form submission
        processFormSubmission($conn, $option, $pollId);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['option']) && isset($_GET['poll_id'])) {
        // Get the selected option and poll ID from the query parameters
        $option = $_GET['option'];
        $pollId = $_GET['poll_id'];

        // Process the form submission
        processFormSubmission($conn, $option, $pollId);
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

function processFormSubmission($conn, $option, $pollId) {
    // Check if the user has already selected an option
    $query = "SELECT COUNT(*) FROM user_polls WHERE user_id = :user_id AND poll_id = :poll_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':poll_id', $pollId, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();

    if ($rowCount > 0) {
        // Redirect back to following.php
        header("Location: following.php");
        exit();
    }

    // Increase the total_responses for the poll
    $query = "UPDATE polls SET total_responses = total_responses + 1 WHERE poll_id = :poll_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':poll_id', $pollId, PDO::PARAM_INT);
    $stmt->execute();

    // Increase the option_responses for the chosen option
    $optionColumn = 'option' . $option . '_responses';
    $query = "UPDATE polls SET $optionColumn = $optionColumn + 1 WHERE poll_id = :poll_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':poll_id', $pollId, PDO::PARAM_INT);
    $stmt->execute();

    // Insert a new row into the user_polls table
    $query = "INSERT INTO user_polls (user_id, poll_id) VALUES (:user_id, :poll_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':poll_id', $pollId, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to following.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    exit();
}
?>
