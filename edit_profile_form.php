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
    // Establish database connection
    $host = 'localhost';
    $database = 'dbntos1pjl9uqt';
    $username = 'ubsmyehs1mgzl';
    $password = 'Ekajdf15';

    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user ID
    $userId = $_SESSION['user_id'];

    // Retrieve new bio value
    $newBio = $_POST['bio'];

    // Check if a file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Define the target directory
        $targetDir = 'profile_pictures/';

        // Generate a unique file name
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);

        // Define the target path
        $targetPath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Prepare and execute SQL update statement to update both the bio and image
            $stmt = $conn->prepare("UPDATE users SET bio = ?, profile_picture = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $newBio, $fileName, $userId);
            $stmt->execute();

            // Close statement and connection
            $stmt->close();
            $conn->close();

            // Redirect the user to the profile page or display a success message
            header("Location: profile.php");
            exit();
        } else {
            // Error occurred while moving the file
            $error = "Error uploading the image.";
        }
    } else {
        // Prepare and execute SQL update statement to update only the bio
        $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newBio, $userId);
        $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Redirect the user to the profile page or display a success message
        header("Location: profile.php");
        exit();
    }
} else {
    // Redirect the user to the update profile page or display an error message
    header("Location: edit_profile.php");
    exit();
}
?>
