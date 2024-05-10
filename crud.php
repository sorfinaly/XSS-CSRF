<?php


// include 'db.php';
// session_start();

// // Check if the request method is DELETE
// if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
//     // Check if an ID parameter is provided in the URL
//     if (isset($_GET['id'])) {
//         $id = $_GET['id'];
//         if ($_SESSION['role_id'] == 1 ) {
//             // Only allow deletion if the user is an admin
//             echo json_encode(array("success" => false, "message" => "Unauthorized"));
//             exit();
//         }
//         // Prepare a SQL statement to delete the student record by ID
//         $stmt = $mysqli->prepare("DELETE FROM students WHERE id = ?");
//         $stmt->bind_param("i", $id);

//         // Execute the prepared statement
//         if ($stmt->execute()) {
//             // Deletion successful
//             echo json_encode(array("success" => true));
//             exit();
//         } else {
//             // Deletion failed
//             $error = $stmt->error;
//             echo json_encode(array("success" => false, "message" => "Deletion failed: $error"));
//             exit();
//         }
//     } else {
//         // No ID parameter provided
//         echo json_encode(array("success" => false, "message" => "No ID provided"));
//         exit();
//     }
// } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Check if the request method is POST

//     // Validate input fields
//     $name = validateInput($_POST['name'], "/^[A-Za-z\s]+$/", "Invalid name");
//     $matricno = validateInput($_POST['matricno'], "/^\d{7}$/", "Invalid matric number");
//     $curraddress = validateInput($_POST['curraddress'], "/^[A-Za-z0-9\/\s,\-.]+$/", "Invalid current address");
//     $homeaddress = validateInput($_POST['homeaddress'], "/^[A-Za-z0-9\/\s,\-.]+$/", "Invalid home address");
//     $email = validateInput($_POST['email'], "/[a-z0-9._%+-]+@gmail+\.[a-z]{2,}$/", "Invalid email");
//     $mobilephone = validateInput($_POST['mobilephone'], "/^\d{3}[\-]\d{3}[\-]\d{4}$/", "Invalid mobile phone number");
//     $homephone = validateInput($_POST['homephone'], "/^\d{3}[\-]\d{3}[\-]\d{4}$/", "Invalid home phone number");

//     // Check if an ID parameter is provided
//     if (isset($_POST['id']) && !empty($_POST['id'])) {
//         // Update record

//         $id = $_POST['id'];

//         if ($_SESSION['role_id'] == 1 ) {
//             // Only allow deletion if the user is an admin
//             echo json_encode(array("success" => false, "message" => "Unauthorized"));
//             exit();
//         }

//         // Prepare a SQL statement to update the student record
//         $stmt = $mysqli->prepare("UPDATE students SET name=?, matricno=?, curraddress=?, homeaddress=?, email=?, mobilephone=?, homephone=? WHERE id=?");
//         $stmt->bind_param("sssssssi", $name, $matricno, $curraddress, $homeaddress, $email, $mobilephone, $homephone, $id);

//         // Execute the prepared statement
//         if ($stmt->execute()) {
//             // Insertion successful
//             echo json_encode(array("success" => true));
//             $inserted_id = $mysqli->insert_id;

//             header("Location: student_details.php?id=$inserted_id");
//             exit();
//         } else {
//             // Update failed
//             $error = $stmt->error;
//             echo json_encode(array("success" => false, "message" => "Update failed: $error"));
//             exit();
//         }
//     } else {
//         // Insert record

//         if ($_SESSION['role_id'] == 1 ) {
//             echo json_encode(array("success" => false, "message" => "Unauthorized"));
//             exit();
//         }

//         // Prepare a SQL statement to insert the student record
//         $stmt = $mysqli->prepare("INSERT INTO students (name, matricno, curraddress, homeaddress, email, mobilephone, homephone, login_id, role_id) VALUES (?, ?, ?, ?, ?, ?, ?,?,?)");
//         $stmt->bind_param("sssssssii", $name, $matricno, $curraddress, $homeaddress, $email, $mobilephone, $homephone, $_SESSION['user_id'], $_SESSION['role_id']);

//         // Execute the prepared statement
//         if ($stmt->execute()) {
//             // Insertion successful
//             echo json_encode(array("success" => true));
//             $inserted_id = $mysqli->insert_id;

//             header("Location: student_details.php?id=$inserted_id");
//             exit();
//         } else {
//             // Insertion failed
//             $error = $stmt->error;
//             echo json_encode(array("success" => false, "message" => "Insertion failed: $error"));
//             exit();
//         }
//     }
// } else {
//     // Invalid request method
//     echo json_encode(array("success" => false, "message" => "Invalid request method"));
//     exit();
// }

// // Function to validate input using preg_match
// function validateInput($input, $pattern, $error_message) {
//     $validated_input = htmlspecialchars($input); // Sanitize input
//     if (preg_match($pattern, $validated_input)) {
//         return $validated_input;
//     } else {
//         // Invalid input
//         http_response_code(400); // Set response status code to 400 (Bad Request)
//         echo json_encode(array('error' => $error_message)); // Return error message in JSON format
//         exit(); // Terminate script execution
//     }
// }

include 'db.php';
include 'content-security-policy.php';
session_start();



// Check if the request method is DELETE
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Check if an ID parameter is provided in the URL
    if (isset($_GET['id'])) {
        // Validate CSRF Token

        if ($_GET['csrf_token'] !== $_SESSION['csrf_token']) {
            // Invalid CSRF Token, reject the request
            echo ($csrfToken."\n");
            echo ($_POST['csrf_token']."\n");
            echo ($_SESSION['csrf_token']."\n");
            http_response_code(403); // Set response status code to 403 (Forbidden)
            echo json_encode(array("success" => false, "message" => "CSRF Token Validation Failed"));
            exit();
        }

        $id = $_GET['id'];
        if ($_SESSION['role_id'] == 1 ) {
            // Only allow deletion if the user is an admin
            echo json_encode(array("success" => false, "message" => "Unauthorized"));
            exit();
        }
        // Prepare a SQL statement to delete the student record by ID
        $stmt = $mysqli->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Deletion successful
            echo json_encode(array("success" => true));
            exit();
        } else {
            // Deletion failed
            $error = $stmt->error;
            echo json_encode(array("success" => false, "message" => "Deletion failed: $error"));
            exit();
        }
    } else {
        // No ID parameter provided
        echo json_encode(array("success" => false, "message" => "No ID provided"));
        exit();
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the request method is POST

    // Validate CSRF Token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Invalid CSRF Token, reject the request
        http_response_code(403); // Set response status code to 403 (Forbidden)

        echo ($csrfToken."\n");
        echo ($_POST['csrf_token']."\n");
        echo ($_SESSION['csrf_token']."\n");
        echo json_encode(array("success" => false, "message" => "CSRF Token Validation Failed"));
        exit();
    }

    // Validate input fields
    $name = validateInput($_POST['name'], "/^[A-Za-z\s]+$/", "Invalid name");
    $matricno = validateInput($_POST['matricno'], "/^\d{7}$/", "Invalid matric number");
    $curraddress = validateInput($_POST['curraddress'], "/^[A-Za-z0-9\/\s,\-.]+$/", "Invalid current address");
    $homeaddress = validateInput($_POST['homeaddress'], "/^[A-Za-z0-9\/\s,\-.]+$/", "Invalid home address");
    $email = validateInput($_POST['email'], "/[a-z0-9._%+-]+@gmail+\.[a-z]{2,}$/", "Invalid email");
    $mobilephone = validateInput($_POST['mobilephone'], "/^\d{3}[\-]\d{3}[\-]\d{4}$/", "Invalid mobile phone number");
    $homephone = validateInput($_POST['homephone'], "/^\d{3}[\-]\d{3}[\-]\d{4}$/", "Invalid home phone number");

    // Check if an ID parameter is provided
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update record

        $id = $_POST['id'];

        if ($_SESSION['role_id'] == 1 ) {
            // Only allow deletion if the user is an admin
            echo json_encode(array("success" => false, "message" => "Unauthorized"));
            exit();
        }

        // Prepare a SQL statement to update the student record
        $stmt = $mysqli->prepare("UPDATE students SET name=?, matricno=?, curraddress=?, homeaddress=?, email=?, mobilephone=?, homephone=? WHERE id=?");
        $stmt->bind_param("sssssssi", $name, $matricno, $curraddress, $homeaddress, $email, $mobilephone, $homephone, $id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Insertion successful
            echo json_encode(array("success" => true));
            $inserted_id = $mysqli->insert_id;

            header("Location: student_details.php?id=$inserted_id");
            exit();
        } else {
            // Update failed
            $error = $stmt->error;
            echo json_encode(array("success" => false, "message" => "Update failed: $error"));
            exit();
        }
    } else {
        // Insert record

        if ($_SESSION['role_id'] == 1 ) {
            echo json_encode(array("success" => false, "message" => "Unauthorized"));
            exit();
        }

        // Prepare a SQL statement to insert the student record
        $stmt = $mysqli->prepare("INSERT INTO students (name, matricno, curraddress, homeaddress, email, mobilephone, homephone, login_id, role_id) VALUES (?, ?, ?, ?, ?, ?, ?,?,?)");
        $stmt->bind_param("sssssssii", $name, $matricno, $curraddress, $homeaddress, $email, $mobilephone, $homephone, $_SESSION['user_id'], $_SESSION['role_id']);

        // // Execute the prepared statement
        // if ($stmt->execute()) {
        //     // Insertion successful
        //     echo json_encode(array("success" => true));
        //     $inserted_id = $mysqli->insert_id;

        //     header("Location: student_details.php?id=$70");
        //     exit();
        // } else {
        //     // Insertion failed
        //     $error = $stmt->error;
        //     echo json_encode(array("success" => false, "message" => "Insertion failed: $error"));
        //     exit();
        // }
    }
} else {
    // Invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
    exit();
}

// Function to validate input using preg_match
function validateInput($input, $pattern, $error_message) {
    $validated_input = htmlspecialchars($input); // Sanitize input
    if (preg_match($pattern, $validated_input)) {
        return $validated_input;
    } else {
        // Invalid input
        http_response_code(400); // Set response status code to 400 (Bad Request)
        echo json_encode(array('error' => $error_message)); // Return error message in JSON format
        exit(); // Terminate script execution
    }
}

?>
