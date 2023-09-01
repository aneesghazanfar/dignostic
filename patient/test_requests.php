<?php
require_once "../db_connection.php";
$user_role = $_SESSION["role"];

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_rating"])) {

    $rating = $_POST["rating"];
    $center_id = $_POST["center_id"];

    $sql = "SELECT rating , rate_no FROM diagnostic_center WHERE id = $center_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $new_rating = ($row['rating'] + $rating);
    $new_rate_no = $row['rate_no'] + 1;
    $new_rating = $new_rating / $new_rate_no;
    $sql = "INSERT INTO patient_rating (center_id, user_id, rating) VALUES ('$center_id', '".$_SESSION["user_id"]."', '$rating')";
    mysqli_query($conn, $sql);
    // Insert the data into the test_requests table
    $sql = "UPDATE diagnostic_center SET rating = $new_rating , rate_no = $new_rate_no WHERE id = $center_id";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful insertion
        header("Location: test_requests.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }



}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_test_rating"])) {

    $rating = $_POST["rating"];
    $test_id = $_POST["test_id"];
    $sql = "UPDATE test_requests SET rating = $rating WHERE id = $test_id";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful insertion
        header("Location: test_requests.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }



}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_feedback"])) {
    $feedback = $_POST["feedback"];
    $center_id = $_POST["center"];
    $patient_id = $_SESSION["user_id"];
    $sql = "SELECT user_id FROM diagnostic_center WHERE id = $center_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $admin_id = $row['user_id'];
        $sql = "INSERT INTO feedback (sender_id, receiver_id, message, timestamp)
            VALUES ('$patient_id', '$admin_id', '$feedback', NOW())";
    $conn->query($sql);
    header("Location: test_requests.php");
}



// Retrieve test requests data from the database
$sql = "SELECT * FROM test_requests WHERE user_id = ".$_SESSION["user_id"]."";
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
                        <th>Center Rating</th>
                        <th>Test Rating</th>
                        <th>Feedback</th>
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
                        $result2 = $conn->query($sql);
                        if($result2){
                        $row2 = $result2->fetch_assoc();
                        echo "<td>" . $row2['name'] . "</td>";
                        }
                        else
                        echo "<td>" . $row['center'] . "</td>";
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
                        if($row['status'] == "Completed")
                        {
                            echo "<td>";
                            echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#rating" onclick="rating(' . $row['center'] . ')"><i class="fa fa-star"></i></a>';
                            echo "</td>";
                            echo "<td>";
                            echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#testrating" onclick="testrating(' . $row['id'] . ')"><i class="fa fa-star"></i></a>';
                            echo "</td>";
                        }
                        else
                        {
                            echo "<td>Not Completed</td>";
                            echo "<td>Not Completed</td>";
                        }
                        echo "<td>";
                        echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#feedback" onclick="feedback(' . $row['center'] . ')"><i class="fa fa-comments"></i></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Feedback Diagnostic Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <input type="hidden" name="center" id="center">
                        <?php
                        $sql = "SELECT COUNT(*) FROM feedback WHERE sender_id = " . $_SESSION["user_id"] . "";
                        $result = $conn->query($sql);
                        $row = $result->fetch_row();
                        $count = $row[0];
                        
                        if (!($count > 0)) {
                            ?>
                            <label for="rating">Feedback:</label>
                            <textarea class="form-control" name="feedback" id="feedback" required></textarea>
                        <?php
                        }
                        else{
                        $sql = "SELECT f.message FROM feedback f, diagnostic_center dc WHERE (f.receiver_id = " . $_SESSION["user_id"] . " AND f.sender_id = dc.user_id)";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo "Reply: ";
                            echo $row['message'];
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_feedback">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade" id="rating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rate Diagnostic Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <input type="hidden" name="center_id" id="center_id">
                        <label for="rating">Rating:</label>
                        <select class="form-control" name="rating" id="rating" required>
                            <option value="1">1 Star</option>
                            <option value="2">2 Star</option>
                            <option value="3">3 Star</option>
                            <option value="4">4 Star</option>
                            <option value="5">5 Star</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_rating">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="testrating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rate Diagnostic Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <input type="hidden" name="test_id" id="test_id">
                        <label for="rating">Rating:</label>
                        <select class="form-control" name="rating" id="rating" required>
                            <option value="1">1 Star</option>
                            <option value="2">2 Star</option>
                            <option value="3">3 Star</option>
                            <option value="4">4 Star</option>
                            <option value="5">5 Star</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_test_rating">Submit</button>
                </form>
            </div>
        </div>
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
<script>
       function rating(center_id) {
        $("#center_id").val(center_id);
       }
       
       function testrating(center_id) {
        $("#test_id").val(center_id);
       }
       function feedback(center_id) {
        $("#center").val(center_id);
       }
</script>


    