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

// Update approval status
if (isset($_GET["approve"])) {
    // Retrieve the username from the query parameter
    $username = $_GET["approve"];

    // Update the 'approved' column to 1 for the specific user
    $updateSql = "UPDATE users SET approved = 1 WHERE username = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

// Remove the row
if (isset($_GET["disapprove"])) {
    // Retrieve the username from the query parameter
    $username = $_GET["disapprove"];

    // Delete the row from the table for the specific user
    $deleteSql = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
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
        <div id="dc-managers">
            <?php
            if (isset($_GET["dc-managers"])) {
                // Retrieve users with role 'dc_manager' and approved value of 0
                $sql = "SELECT name, phone_no, email, username FROM users WHERE role = 'dc_manager' AND approved = 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<h3>DC Managers to Approve</h3>';
                    echo '<table id="data_table" class="table table-striped">';
                    echo '<thead><tr><th>Name</th><th>Phone No</th><th>Email</th><th>Action</th></tr></thead>';
                    echo '<tbody>';

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['phone_no'] . '</td>';
                        echo '<td>' . $row['email'] . '</td>';
                        echo '<td>';
                        echo '<a class="btn btn-success" href="dashboard.php?approve=' . $row['username'] . '"><i class="fas fa-check"></i> Approve</a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-danger" href="dashboard.php?disapprove=' . $row['username'] . '"><i class="fas fa-times"></i> Disapprove</a>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p>No DC Managers to approve.</p>';
                }
                
                $result->close();
            }
            ?>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
