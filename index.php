<?php
require_once "db_connection.php";

if (isset($_SESSION["username"])) {
    // Redirect to the login page
    if($_SESSION["role"] == "admin")
    header("Location: ./admin/dashboard.php?dc-managers");
    else if($_SESSION["role"] == "patient")
    header("Location: ./patient/dashboard.php");
    else if($_SESSION["role"] == "dc_manager")
    header("Location: ./dc_manager/dashboard.php");
    else
    header("Location: ./login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li><li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
    <?php
    require_once "./db_connection.php";
    $sql = "SELECT * FROM diagnostic_center";
    $result = $conn->query($sql);
            echo '<h3>Diagnostic Centers</h3>';
            echo '<table id="data_table" class="table table-striped">';
            echo '<thead><tr><th>Name</th><th>Tests</th><th>Location</th><th>Phone No</th><th>Email</th><th>Rating</th></tr></thead>';
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
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        
        ?>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#data_table').DataTable();
        });
    </script>
</body>
</html>
