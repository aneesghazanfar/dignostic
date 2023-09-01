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
    $sql = "INSERT INTO test_requests (test_name, test_type, center, book_by, user_id, date, time) VALUES ('$testName', '$testType', '$centerName', '$bookedBy', ".$_SESSION["user_id"].", '$date', '$time')";
if (mysqli_query($conn, $sql)) {
    $_SESSION["success_message"] = "Test request added successfully.";

        // Redirect to the desired page after successful insertion     
        header("Location: test_request.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
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
                <!-- Display success message if available -->
                <?php if (isset($_SESSION["success_message"])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION["success_message"]; ?>
            </div>
            <?php unset($_SESSION["success_message"]); // Remove the success message from the session ?>
        <?php endif; ?>
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
                                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
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