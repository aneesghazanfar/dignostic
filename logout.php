<?php
// Perform any necessary cleanup or session management tasks

// Destroy the session and redirect to the login page

session_destroy();
header("Location: index.php"); // Redirect to the login page
exit;
?>
