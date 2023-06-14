<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the cancel button is clicked
if (isset($_POST['cancel'])) {
    // Redirect the user to the profile page
    header("Location: profile.php");
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
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        .page-title {
            font-family: "Bahnschrift SemiBold", sans-serif;
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
        <div class="col-8 main-content d-flex flex-column justify-content-center align-items-center">
            <h1 class="page-title">Update Profile:</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="imageField" class="form-label">Image</label>
                    <input type="file" class="form-control" id="imageField" name="image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="bioField" class="form-label">Update Bio</label>
                    <textarea class="form-control" id="bioField" name="bio" rows="5"
                              maxlength="500"></textarea> <!-- Added name attribute -->
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button> <!-- Added name attribute -->
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
