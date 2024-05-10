<?php
session_start();
include 'content-security-policy.php';
include 'db.php'; // Include database connection

// Check if email and password are set in $_POST
if (isset($_POST['email'], $_POST['password'])) {
    // Get user input   
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user from database
    $stmt = $mysqli->prepare("SELECT id, email, password FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row was returnedfetchStudentData()
    if ($result->num_rows == 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_password'] = $user['password'];
            $_SESSION['role_id'] = $user['role_id'];

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            echo($_SESSION['csrf_token']);

            
            $stmt = $mysqli->prepare("SELECT id FROM students WHERE login_id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $student_id = $result->fetch_assoc()['id'];
                $_SESSION['student_id'] = $student_id; // Assuming you want to store student ID in session as well
                header("Location: student_details.php?id=$student_id");
                exit();
            } else {
                header("Location: form.php");
            //     exit();
            }
            // Display user ID for testing purposes
            echo "User ID: " . $_SESSION['user_id'];
            echo "Student ID: " . $_SESSION['student_id'];
            


            if(($_SESSION['student_id']) == null){
                header("Location: form.php");
                exit();
            } else {
                header("Location: student_details.php?id=$student_id");
                exit();
            }

            header("Location: student_details.php?id=$student_id");
            exit();
        } else {
            // Invalid password
            $errorMessage = "Invalid email or password.";
        }
    } else {
        // No user found with the given email
        $errorMessage = "Please register for an account.";
    }
} else {
    // Email or password not provided
    $errorMessage = "No email and password provided.";
}

// If authentication fails or no credentials provided, redirect back to login page with error message
echo "<script>alert('$errorMessage'); window.history.back();</script>";
exit();

?>
