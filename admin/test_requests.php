<?php
require_once "../db_connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_request"])) {
    // Retrieve form data
    $testName = $_POST["test_name"];
    $testType = $_POST["test_type"];
    $centerName = $_POST["center_name"];
    $bookedBy = $_SESSION["user_id"];
    $patientName = $_POST["patient_name"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    // Insert the data into the test_requests table
    $sql = "INSERT INTO test_requests (test_name, test_type, center,book_by	, user_id, date, time) VALUES ('$testName', '$testType', '$centerName','$bookedBy', '$patientName', '$date', '$time')";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful insertion
        header("Location: test_requests.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["cancel"])) {
    $id = $_GET["cancel"];
    // cancel the test data from the database
    $sql = "UPDATE test_requests SET status='cancelled' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful deletion
        header("Location: test_requests.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}


// Retrieve test requests data from the database
$sql = "SELECT * FROM test_requests";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include "slidebar.php"; ?>
    <div class="content">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_request_modal">
    Add Request
</button>
<table id="data_table" class="table table-striped">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Test Type</th>
                        <th>Center Name</th>
                        <th>Booked By</th>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Iterate over the fetched data and render table rows
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['test_name'] . "</td>";
                        echo "<td>" . $row['test_type'] . "</td>";
                        $sql = "SELECT name FROM diagnostic_center WHERE id = " . $row['center'];
                        $result2 = $conn->query($sql);
                        if($result2){
                        $row2 = $result2->fetch_assoc();
                        echo "<td>" . $row2['name'] . "</td>";
                        }
                        else
                        echo "<td>" . $row['center'] . "</td>";

                        $sql = "SELECT name FROM users WHERE id = " . $row['book_by'];
                        $result2 = $conn->query($sql);
                        $row2 = $result2->fetch_assoc();
                        echo "<td>" . $row2['name'] . "</td>";
                        $sql = "SELECT name FROM users WHERE id = " . $row['user_id'];
                        $result2 = $conn->query($sql);
                        $row2 = $result2->fetch_assoc();
                        echo "<td>" . $row2['name'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo '<td><a href="test_requests.php?cancel=' . $row['id'] . ' "class="btn btn-danger"><i class="fa fa-times"></i></a></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>




<div class="modal fade" id="add_request_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <label for="test_name">Test Name:</label>
                        <select class="form-control" name="test_name" id="test_name" required>
                            <?php
                            $sql = "SELECT * FROM test_detail";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="test_type">Test Type:</label>
                        <select class="form-control" name="test_type" id="test_type" required>
                            <?php
                            $sql = "SELECT * FROM test_categories";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['type'] . '">' . $row['type'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="center_name">Center Name:</label>
                        <select class="form-control" name="center_name" id="center_name" required>
                            <?php
                            $sql = "SELECT * FROM diagnostic_center";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="patient_name">Patient Name:</label>
                        <select class="form-control" name="patient_name" id="patient_name" required>
                            <?php
                            $sql = "SELECT * FROM users WHERE role='patient'";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" class="form-control" name="time" id="time" required>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_request">ADD</button>
                </form>
            </div>
        </div>
    </div>
</div>

    