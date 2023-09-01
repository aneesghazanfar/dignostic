<?php
require_once "../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_test"])) {
    // Retrieve form data
    $category = $_POST["category"];
    $test_name = $_POST["test_name"];
    $cost = $_POST["cost"];
    $reporting_time = $_POST["reporting_time"];
    $center_id = $_POST["center_id"];
    
    // Insert the test data into the test_detail table
    $sql = "INSERT INTO test_detail (category, name, cost, reporting_time, center_id) 
            VALUES ('$category', '$test_name', '$cost', '$reporting_time', '$center_id')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful insertion
        header("Location: test_detail.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_test"])) {
    // Retrieve form data
    $test_id = $_POST["test_id"];
    $category = $_POST["category"];
    $test_name = $_POST["test_name"];
    $cost = $_POST["cost"];
    $reporting_time = $_POST["reporting_time"];
    
    // Update the test data in the test_detail table
    $sql = "UPDATE test_detail SET category='$category', name='$test_name', cost='$cost', reporting_time='$reporting_time' WHERE id='$test_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful update
        header("Location: test_detail.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit"])) {
    $id = $_GET["edit"];
    // Retrieve the test data from the database
    $sql = "SELECT * FROM test_detail WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // Store the data in variables for pre-filling the edit form
    $name = $row["name"];
    $category = $row["category"];
    $cost = $row["cost"];
    $reporting_time = $row["reporting_time"];
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $id = $_GET["delete"];
    // Delete the test data from the database
    $sql = "DELETE FROM test_detail WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the desired page after successful deletion
        header("Location: test_detail.php");
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
    <title>Diagnostic Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include "slidebar.php"; ?>
    <div class="content">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_test_modal">
            Add Test
        </button>
        
        <table id="data_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Test Name</th>
                    <th>Center Name</th>
                    <th>Cost</th>
                    <th>Reporting Time (hr)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql1 = "SELECT * FROM test_detail";
                $result1 = $conn->query($sql1);
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        echo '<tr>';
                        $sql = "SELECT type FROM test_categories WHERE id = " . $row['category'];
                        $result = $conn->query($sql);
                        $row1 = $result->fetch_assoc();
                        echo '<td>' . $row1['type'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        $sql = "SELECT name FROM diagnostic_center WHERE id = " . $row['center_id'];
                        $result = $conn->query($sql);
                        $row1 = $result->fetch_assoc();
                        echo '<td>' . $row1['name'] . '</td>';
                        echo '<td>' . $row['cost'] . '</td>';
                        echo '<td>' . $row['reporting_time'] . '</td>';
                        echo '<td>';
                        echo '<a class="btn btn-primary" href="?edit=' . $row['id'] . '"><i class="fa fa-pencil"></i></a>';
                        echo '&nbsp;';
                        echo '<a class="btn btn-danger" href="?delete=' . $row['id'] . '"><i class="fa fa-trash"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Test Modal -->
    <div class="modal fade" id="add_test_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Test</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $sql = "SELECT * FROM test_categories";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="center_id">Center Name:</label>
                            <select class="form-control" name="center_id" required>
                                <?php
                                $sql = "SELECT * FROM diagnostic_center";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="test_name">Test Name:</label>
                            <input type="text" class="form-control" name="test_name" required>
                        </div>
                        <div class="form-group">
                            <label for="cost">Cost:</label>
                            <input type="number" class="form-control" name="cost" required>
                        </div>
                        <div class="form-group">
                            <label for="reporting_time">Reporting Time (hr):</label>
                            <input type="text" class="form-control" name="reporting_time" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_test">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Test Modal -->
    <div class="modal fade" id="edit_test_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Test</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <input type="hidden" id="edit_test_id" name="test_id">
                        <div class="form-group">
                            <label for="edit_category">Category:</label>
                            <select class="form-control" name="category" id="edit_category" required>
                                <?php
                                $sql = "SELECT * FROM test_categories";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_test_name">Test Name:</label>
                            <input type="text" class="form-control" name="test_name" id="edit_test_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_cost">Cost:</label>
                            <input type="number" class="form-control" name="cost" id="edit_cost" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_reporting_time">Reporting Time (hr):</label>
                            <input type="text" class="form-control" name="reporting_time" id="edit_reporting_time" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="update_test">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($id)) : ?>
        <script>
            // Execute the JavaScript function after the DOM is fully loaded
            $(document).ready(function() {
                editTest(<?php echo $id; ?>, "<?php echo $category; ?>", "<?php echo $name; ?>", "<?php echo $cost; ?>", "<?php echo $reporting_time; ?>");
            });

            function editTest(id, category, name, cost, reporting_time) {
                $("#edit_test_id").val(id);
                $("#edit_category").val(category);
                $("#edit_test_name").val(name);
                $("#edit_cost").val(cost);
                $("#edit_reporting_time").val(reporting_time);
                $("#edit_test_modal").modal("show");
            }
        </script>
        
    <?php endif; ?>
</body>
</html>
