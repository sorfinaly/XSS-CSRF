<?php
include 'content-security-policy.php';
session_start();


if (isset($_SESSION['user_email']) && isset($_SESSION['user_password'])) {
    // Include database connection
    include 'db.php';

    // Get the email from the session
    $email = $_SESSION['user_email'];
    $password = $_SESSION['user_password'];

    $stmt = $mysqli->prepare("SELECT * FROM students WHERE email = ? OR id = ?");
    $stmt->bind_param("si", $email, $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row was returned
    if ($result->num_rows == 1  ) {
        // Fetch the student's data
        $student = $result->fetch_assoc();
    } elseif ($result->num_rows == 0){
        // No student found with the given email
        // echo "<script>alert('No student found with the given email'); </script>";
        header("Location: form.html");
        exit();
        
    } 
} else {
    // Session email not set, redirect to login page
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Details</title>

<style>
.response-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 60vh;
  margin:30px;
  border: 2px solid #000;
}
.details-container {
    display: flex;
    flex-wrap: wrap;
}

.detail-item {
    flex: 1 0 50%; /* Two items per row */
    padding: 10px;
}

.detail-label {
    font-weight: bold;
}

.detail-value {
    margin-left: 10px;
}
.response-content {
  font-family: "Source Sans Pro", sans-serif;
  font-size: 18px;
  font-weight: 600;
  text-align: left;
  padding: 20px;
  border-radius: 10px;
  background-color: rgba(255, 255, 255, 0.8); /* Transparent background */
}

.response-content table {
  border-collapse: collapse;
  width: 100%;
  font-size: 1.2em;
}

.response-content td {
  border: 1px solid transparent; /* Transparent border */
  padding: 8px;
  text-align: left;
}

.response-content p.error-message {
  color: red;
}

.form__btn {
  font-family: "Source Sans Pro", sans-serif;
  font-weight: 600;
  font-size: 1.1em;
  padding: 10px 16px;
  margin: 10px auto; /* Center the button horizontally */

  color: #ffffff;
  background: #14b64a;
  border: 2px solid #0fa942;
  border-radius: 5px;

  cursor: pointer;
  outline: none;
}

.form__btn:active {
  background: #0fa942;
}

</style>

</head>
<body >

<div id="student-details-container" class="response-container">
  <div class="response-content">
    <h1>Student Details</h1>
    
    <!-- Display student details here -->
    <div id="student-details" class="details-container">
      <?php if (isset($student) && $student): ?>
        <table>
          <tr><td class="detail-label">Name:</td><td class="detail-value"><?php echo $student['name']; ?></td></tr>
          <tr><td class="detail-label">Matric No:</td><td class="detail-value"><?php echo $student['matricno']; ?></td></tr>
          <tr></tr><td class="detail-label">Email:</td><td class="detail-value"><?php echo $student['email']; ?></td></tr>
          <tr><td class="detail-label">Current Address:</td><td class="detail-value"><?php echo $student['curraddress']; ?></td></tr>
          <tr><td class="detail-label">Home Address:</td><td class="detail-value"><?php echo $student['homeaddress']; ?></td></tr>
          <tr><td class="detail-label">Mobile Phone:</td><td class="detail-value"><?php echo $student['mobilephone']; ?></td></tr>
          <tr><td class="detail-label">Home Phone:</td><td class="detail-value"><?php echo $student['homephone']; ?></td></tr>
        </table>


      <?php else: ?>
        <p class="error-message">No student details found.</p>
      <?php endif; ?>
    </div>

    <!-- Buttons for Update and Delete -->
    <div class="form__item">
      <button id="update-btn" class="form__btn">Update</button>
      <button id="delete-btn" class="form__btn">Delete</button>
      <button id="back-btn" class="form__btn">Logout</button>
    </div>
  </div> 
</div>


<script>
var csrfToken = "<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>";

document.getElementById('update-btn').addEventListener('click', function() {
    // Redirect to the form.php page with the ID parameter
    var id = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
    if (id !== null) {
        window.location.href = 'form.php?id=' + id;
    } else {
        alert("No student ID found.");
    }
});

document.getElementById('delete-btn').addEventListener('click', function() {
        // Perform AJAX request to delete student
        fetch("crud.php?id=<?php echo $student['id']; ?>&csrf_token=" + csrfToken, {
            method: "DELETE",
         
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                // Redirect to login page or do something else
                window.location.href = "index.html";
            } else {
                // Deletion failed, log error or show message
                alert("Deletion failed: Unable to delete student record.");
            }
        })
        .catch(error => {
            // AJAX request failed, log error or show message
            alert("AJAX request failed: " + error);
        });
    
  });

    // Function to handle back button click event
    document.getElementById('back-btn').addEventListener('click', function() {
    // Clear session data
    fetch('logout.php', {
        method: 'POST',
        credentials: 'same-origin' // Include cookies in the request
    })
    .then(response => {
        if (response.ok) {
            // Session cleared successfully
            window.location.href = "index.html"; // Redirect to login page
        } else {
            // Error handling
            console.error('Failed to clear session data');
        }
    })
    .catch(error => {
        // Error handling
        console.error('Failed to clear session data:', error);
    });
});

  
</script>

</body>
</html>
