<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Establish a database connection (replace with your database credentials)
$servername = "localhost";
$username = "ubsmyehs1mgzl";
$password = "Ekajdf15";
$dbname = "dbntos1pjl9uqt";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT username, profile_picture FROM users WHERE username LIKE '$searchTerm%' ORDER BY num_followers DESC";
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
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Search - BackYourself</title>
    <style>
        .search-bar {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            margin-bottom: 20px; /* Add margin bottom for spacing */
        }
        .main-content {
            align-items: flex-start; /* Adjust alignment to start */
        }
        .dropdown-menu.search-results {
            top: 100%;
            left: 0;
            position: absolute;
            width: 100%;
        }
        .profile-picture {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
            margin-left: 2px; /* Add left margin for white space */
        }
        .dropdown-menu.search-results {
            max-height: 250px; /* Adjust the max-height as needed */
            overflow-y: auto;
        }
        .username {
            font-size: 15px; /* Adjust the font size as needed */
        }
        .search-bar {
            width: 80%;
            max-width: 600px;
            margin: 40px auto 0; /* Add margin-top for spacing */
            position: relative;
            margin-bottom: 20px;
        }
        .search-results {
            margin-top: 30px; /* Add margin top for spacing */
        }
        .search-results h2 {
            font-size: 20px; /* Adjust the font size as needed */
            margin-bottom: 10px; /* Add margin bottom for spacing */
        }
        .user-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            height: 60px; /* Adjust the height as desired */
            transition: background-color 0.3s ease;
            padding-left: 15px; /* Add left padding */
            border-radius: 5px; /* Add border-radius */
            cursor: pointer; /* Add cursor pointer */
        }

        .user-container:hover {
            background-color: #f2f2f2; /* Add background color for hover state */
        }

        .user-container img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .user-container span {
            font-size: 16px; /* Adjust the font size as needed */
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
        <div class="col-8 main-content">

            <!-- Search bar -->
            <form action="search_results.php" method="GET" class="search-bar">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search usernames" name="search" oninput="updateDropdown(this.value)" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
                <div id="username-dropdown" class="dropdown-menu search-results"></div> <!-- Add class "dropdown-menu" and "search-results" -->
            </form>

            <!-- Search results -->
            <div class="search-results">
                <h2>Search Results</h2>
                <?php if (count($usernames) > 0): ?>
                    <?php foreach ($usernames as $user): ?>
                        <div class="user-container" onclick="goToProfile('<?php echo $user['username']; ?>')">
                            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                            <span><?php echo $user['username']; ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No matching users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
let xhr; // Declare xhr variable outside the function

function updateDropdown(search) {
            const dropdown = document.getElementById('username-dropdown');
            dropdown.innerHTML = '';

            if (search.length === 0) {
                dropdown.style.display = 'none'; // Hide the dropdown if the search query is empty
                return;
            }

            // Abort any ongoing XMLHttpRequest
            if (xhr && xhr.readyState !== 4) {
                xhr.abort();
            }

            xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    if (data.length > 0) {
                        dropdown.style.display = 'block'; // Show the dropdown if there are matching usernames
                        data.forEach(function (user, index) {
                        const option = document.createElement('a'); // Change the element to 'a' for hyperlink
                        option.classList.add('dropdown-item'); // Add the 'dropdown-item' class

                        // Set the href attribute with the profile.php page and the clicked username in the query string
                        option.href = 'profile.php?username=' + encodeURIComponent(user.username);

                        // Create a div element to wrap the profile picture and username
                        const optionContent = document.createElement('div');
                        optionContent.classList.add('d-flex', 'align-items-center'); // Add classes for flex layout

                        // Create an image element for the profile picture
                        const profilePic = document.createElement('img');
                        profilePic.src = user.profile_picture;
                        profilePic.alt = 'Profile Picture';
                        profilePic.classList.add('profile-picture');
                        optionContent.appendChild(profilePic);

                        // Create a span element for the username
                        const usernameSpan = document.createElement('span');
                        usernameSpan.textContent = user.username;
                        usernameSpan.classList.add('username'); // Add the 'username' class
                        optionContent.appendChild(usernameSpan);

                        // Add the optionContent to the option element
                        option.appendChild(optionContent);

                        // Add top border to separate the options
                        if (index > 0) {
                            option.style.borderTop = '1px solid #ccc';
                            option.style.paddingTop = '5px';
                            option.style.marginTop = '5px';
                        }

                        dropdown.appendChild(option);
                        });
                    } else {
                        dropdown.style.display = 'none'; // Hide the dropdown if no matching usernames found
                    }
                }
            };
            xhr.open('GET', 'get_usernames.php?search=' + encodeURIComponent(search), true);
            xhr.send();
}

function goToProfile(username) {
            window.location.href = 'profile.php?username=' + encodeURIComponent(username);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
