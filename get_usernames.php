<?php
// Establish a database connection (replace with your database credentials)
$servername = "localhost";
$username = "ubsmyehs1mgzl";
$password = "Ekajdf15";
$dbname = "dbntos1pjl9uqt";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT username, profile_picture FROM users WHERE username LIKE '$search%' ORDER BY num_followers DESC";
    $result = $conn->query($sql);
    $usernames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $profilePicture = $row['profile_picture'];
            // Set the correct path for the profile picture
            $profilePicturePath = "profile_pictures/$profilePicture";
            if (file_exists($profilePicturePath)) {
                $usernames[] = [
                    'username' => $row['username'],
                    'profile_picture' => $profilePicturePath
                ];
            } else {
                // If the profile picture file does not exist, use a default image or display an alternative message
                $usernames[] = [
                    'username' => $row['username'],
                    'profile_picture' => 'default_profile_picture.jpg' // Adjust with the path to the default image
                ];
            }
        }
    }
    echo json_encode($usernames);
}
?>
