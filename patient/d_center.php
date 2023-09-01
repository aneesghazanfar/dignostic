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
</head>
<body>
    <?php include "slidebar.php"; ?>

    <div class="content">
    <?php
    $sql = "SELECT * FROM diagnostic_center";
    $result = $conn->query($sql);
            echo '<h3>Diagnostic Centers</h3>';
            echo '<table id="data_table" class="table table-striped">';
            echo '<thead>
            <tr>
            <th>Name</th>
            <th>Tests</th>
            <th>Location</th>
            <th>Phone No</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Massages</th>
            </tr></thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                $sql1 = "SELECT * FROM test_detail WHERE center_id = " . $row['id'];
                $result2 = $conn->query($sql1);
                echo '<td>';
                while ($row2 = $result2->fetch_assoc()) {
                    echo $row2['name'] . '<br>';
                }
                echo '</td>';
                echo '<td>' . $row['location'] . '</td>';
                echo '<td>' . $row['phone_no'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['rating'] . '</td>';
                echo '<td>';
                echo '<a href="./massages.php?patient_id=' . $_SESSION['user_id'] . '&manager=' . $row['user_id'] . '"class="btn btn-primary">View Messages</a>';
                echo '</td>';

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        
        ?>
    </div>
    </div>