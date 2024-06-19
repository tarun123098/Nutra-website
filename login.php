<?php
session_start();

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
    // Get form data
    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];
    
    // Validate form data (you can add more validation if needed)
    if (empty($login_email) || empty($login_password)) {
        echo "Please fill in all required fields.";
    } else {
        try {
            // Prepare SQL statement
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            // Bind parameter
            $stmt->bindParam(':email', $login_email);
            // Execute statement
            $stmt->execute();
            // Fetch user record
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Verify password
                if (password_verify($login_password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];

                    // Check if the user has filled out their profile
                    $stmt_profile = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = :user_id");
                    $stmt_profile->bindParam(':user_id', $user['id']);
                    $stmt_profile->execute();
                    $user_profile = $stmt_profile->fetch(PDO::FETCH_ASSOC);

                    // Redirect based on profile status
                    if ($user_profile) {
                        // Profile exists, redirect to dashboard
                        header("Location: dashboard.html");
                        exit();
                    } else {
                        // Profile doesn't exist, redirect to profile form
                        header("Location: profile1.html");
                        exit();
                    }
                } else {
                    // Password is incorrect
                    echo "Incorrect password.";
                }
            } else {
                // User not found
                echo "<script>alert('User not found! Please register!'); window.location.replace('index.html');</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // If someone tries to access this page directly without submitting the form
    echo "Access denied.";
}
?>
