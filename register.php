<?php
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $referral_code = $_POST['referral_code']; // Assuming referral code is optional
    
    // Validate form data (you can add more validation if needed)
    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill in all required fields.";
    } else {
        // Hash the password before storing it (for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, referral_code) VALUES (:name, :email, :password, :referral_code)");
            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':referral_code', $referral_code);
            // Execute statement
            $stmt->execute();
            // Registration successful, display dialogue box and redirect
            echo "<script>alert('Registration successful!'); window.location.replace('index.html');</script>";
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // If someone tries to access this page directly without submitting the form
    echo "Access denied.";
}
?>
