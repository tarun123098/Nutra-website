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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $first_name = sanitizeInput($_POST['first_name'], $pdo);
    $middle_name = sanitizeInput($_POST['middle_name'], $pdo);
    $surname = sanitizeInput($_POST['surname'], $pdo);
    $dob = sanitizeInput($_POST['dob'], $pdo);
    $age = sanitizeInput($_POST['age'], $pdo);
    $gender = sanitizeInput($_POST['gender'], $pdo);
    $marital_status = sanitizeInput($_POST['marital_status'], $pdo);
    $height = sanitizeInput($_POST['height'], $pdo);
    $weight = sanitizeInput($_POST['weight'], $pdo);
    $bmi = sanitizeInput($_POST['bmi'], $pdo);
    $skinColor = sanitizeInput($_POST['skinColor'], $pdo);
    $habits = implode(",", $_POST['habits']); // Convert array to string
    
    $occupation = sanitizeInput($_POST['occupation'], $pdo);
    $diseases = sanitizeInput($_POST['diseases'], $pdo);
    $medications = sanitizeInput($_POST['medications'], $pdo);
    $goal = sanitizeInput($_POST['goal'], $pdo);
    $food_allergies = sanitizeInput($_POST['food_allergies'], $pdo);

    // Insert data into the database
    $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, first_name, middle_name, surname, dob, age, gender, marital_status, height, weight, bmi, skinColor, habits, occupation, diseases, medications, goal, food_allergies) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $first_name, $middle_name, $surname, $dob, $age, $gender, $marital_status, $height, $weight, $bmi, $skinColor, $habits, $occupation, $diseases, $medications, $goal, $food_allergies]);
    
    // Redirect to dashboard.html if insertion is successful
    header("Location: dashboard.html");
    exit();
}

function sanitizeInput($data, $pdo) {
    return htmlspecialchars(trim($data));
}
?>
