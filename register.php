<?php
session_start();
include 'db.php'; // Include database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $mysqli->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful. You can now <a href='index.html'>login</a>.";
    } else {
        // Registration failed
        echo "Registration failed. Please try again.";
    }
}
?>

