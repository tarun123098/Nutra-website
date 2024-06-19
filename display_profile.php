<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'nutricare';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch user profile from database
$stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user_profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Start HTML document
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>User Profile</title>";
echo "<style>";
// CSS styles
echo ".profile-container {
        padding: 20px;
        max-width: 600px;
        margin: 0;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-container h2 {
        text-align: center;
        color: #4CAF50;
    }

    .profile-container ul {
        list-style-type: none;
        padding: 0;
    }

    .profile-container ul li {
        margin-bottom:0;
    }

    .profile-container ul li strong {
        font-weight: bold;
    }

    .edit-button {
        display: block;
        width: 25%;
        padding: 10px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }";
echo "</style>";
echo "</head>";
echo "<body>";

// Display user profile
if ($user_profile) {
    echo "<div class='profile-container'>";
    echo "<h2>User Profile</h2>";
    echo "<ul>";
    foreach ($user_profile as $key => $value) {
        // Exclude 'id' and 'user_id' from printing
        if ($key != 'id' && $key != 'user_id') {
            echo "<li><strong>$key:</strong> $value</li>";
        }
    }
    echo "</ul>";
    
    // Add Edit button
    echo "<form action='edit_profile.php' method='post'>";
    echo "<button type='submit' class='edit-button'>Edit</button>";
    echo "</form>";

    echo "</div>";
} else {
    echo "User profile not found.";
}

echo "</body>";
echo "</html>";
?>