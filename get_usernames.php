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
    $sql = "SELECT username FROM users WHERE username LIKE '$search%' ORDER BY num_followers DESC";
    $result = $conn->query($sql);
    $usernames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row['username'];
        }
    }
    echo json_encode($usernames);
}
?>
