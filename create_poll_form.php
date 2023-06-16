<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $userId = $_SESSION['user_id'];
    $numOptions = $_POST['numberOfChoices'];
    $title = $_POST['pollTitle'];
    $description = $_POST['pollDescription'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    // Destination folder for uploaded images
    $destinationFolder = "poll_pictures/";

    // Move the uploaded images to the destination folder
    for ($i = 0; $i < $numOptions; $i++) {
        $imageTempPath = $_FILES['choice' . ($i + 1) . 'Image']['tmp_name'];
        $imageName = $_FILES['choice' . ($i + 1) . 'Image']['name'];
        $imageDestinationPath = $destinationFolder . $imageName;
        
        move_uploaded_file($imageTempPath, $imageDestinationPath);
    
        // Add the image name to the $images array
        $images[] = $imageName;
    }

    $title = $_POST['pollTitle'];
    $description = $_POST['pollDescription'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    // Database connection settings
    $host = 'localhost';
    $username = 'ubsmyehs1mgzl';
    $password = 'Ekajdf15';
    $database = 'dbntos1pjl9uqt';

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO polls (user_id, num_options, image1, image2, image3, image4, poll_title, poll_description, category, subcategory) VALUES (:user_id, :num_options, :image1, :image2, :image3, :image4, :poll_title, :poll_description, :category, :subcategory)");

    // Bind the parameters
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':num_options', $numOptions);
    $stmt->bindParam(':image1', $images[0]);
    $stmt->bindParam(':image2', $images[1]);
    $stmt->bindParam(':image3', $images[2]);
    $stmt->bindParam(':image4', $images[3]);
    $stmt->bindParam(':poll_title', $title);
    $stmt->bindParam(':poll_description', $description);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':subcategory', $subcategory);

    // Execute the statement
    if ($stmt->execute()) {
        // Poll created successfully
        // You can redirect the user to a success page or perform any other desired action
        header("Location: following.php");
        exit();
    } else {
        // Failed to create the poll
        // You can redirect the user to an error page or display an error message
        header("Location: error1.php");
        exit();
    }
} else {
    // Form was not submitted
    // You can redirect the user to an error page or display an error message
    header("Location: error2.php");
    exit();
}
?>
