<?php
require_once "../db_connection.php";

// Start the session

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page
    header("Location: ../login.php");
    exit();
}
// Handle logout
if (isset($_GET["logout"])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page after logout
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
    <?php include "slidebar.php"; ?>

    <div class="content">

    </div>