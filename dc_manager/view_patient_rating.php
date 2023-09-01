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


// Retrieve user data from the database
$sql = "SELECT u.* FROM users u, patient_rating p, diagnostic_center d WHERE u.id = p.user_id AND d.user_id =". $_SESSION["user_id"];
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "slidebar.php"; ?>

    <div class="content">
    <table id="data_table" class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Rating</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Iterate over the fetched data and render table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone_no'] . "</td>";
            $sql = "SELECT (rating) as rating FROM patient_rating WHERE user_id = " . $row['id'];
            $result2 = $conn->query($sql);
            $row2 = $result2->fetch_assoc();
            echo "<td>" . $row2['rating'] . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>


    </div>