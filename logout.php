<?php
// Start the session
include 'content-security-policy.php';
session_start();

session_unset(); // remove all stored values in session variables
session_destroy(); // Destroys all data registered to a session
session_write_close(); // End the current session and store session data.
setcookie(session_name(),'',0,'/'); // Send Cookie to client web browser.
// session_regenerate_id(true); // Update the current session id with a newly generated one

// Redirect to the login page
header("Location: index.html");
exit();
?>
