<?php
require_once "../db_connection.php";



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_test_categories"])) {
    // Retrieve form data
    $type = $_POST["test_category"];
    $sql = "SELECT id FROM diagnostic_center WHERE user_id = ".$_SESSION["user_id"];
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $sql = "INSERT INTO center_test_cat (center_id, test_type) VALUES ('" . $row['id'] . "', '$type')";


    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful insertion
        header("Location: test_categories.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle edit action
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit"])) {
    $id = $_GET["edit"];
    // Retrieve the diagnostic center data from the database
    $sql = "SELECT * FROM test_categories WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // Store the data in variables for pre-filling the edit form
    $type = $row["type"];
}

// Handle delete action
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $id = $_GET["delete"];
    // Delete the test category from the database
    $sql = "DELETE FROM center_test_cat WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful deletion
        header("Location: test_categories.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle update action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_test_categories"])) {
    $id = $_POST["test_request_id"];
    $type = $_POST["test_category"];
    // Update the test category data in the database
    $sql = "UPDATE center_test_cat SET test_type = '$type' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful update
        header("Location: test_categories.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}


$sql = "SELECT * FROM test_categories tc , users u , diagnostic_center dc, center_test_cat ctc WHERE tc.id = ctc.test_type AND ctc.center_id = dc.id AND dc.user_id = u.id AND u.id = ".$_SESSION["user_id"];
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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_test_categories">
            Add Test categories
        </button>
        <?php
            echo '<table id="data_table" class="table table-striped">';
            echo '<thead><tr><th>Type</th><th>Action</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['type'] . '</td>';
                echo '<td>';
                echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#edit_test_categories" onclick="openEditModal(' . $row['id'] .')"><i class="fa fa-edit"></i></a>';

                echo '&nbsp;';
                echo '<a class="btn btn-danger" href="?delete=' . $row['id'] . '"><i class="fa fa-trash"></i></a>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        
        ?>
    </div>

    <div class="modal fade" id="add_test_categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Test categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <label for="test_category">Test Category:</label>
                        <select class="form-control" name="test_category" required>
                            <?php
                            // Retrieve test categories from the database
                            $categorySql = "SELECT * FROM test_categories";
                            $categoryResult = mysqli_query($conn, $categorySql);
                            if (mysqli_num_rows($categoryResult) > 0) {
                                while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                    echo "<option value='" . $categoryRow['id'] . "'>" . $categoryRow['type'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add_test_categories">Add</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_test_categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Test categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                    <div class="form-group">
                        <label for="test_category">Test Category:</label>
                        <select class="form-control" name="test_category" required>
                            <?php
                            // Retrieve test categories from the database
                            $categorySql = "SELECT * FROM test_categories";
                            $categoryResult = mysqli_query($conn, $categorySql);
                            if (mysqli_num_rows($categoryResult) > 0) {
                                while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                    echo "<option value='" . $categoryRow['id'] . "'>" . $categoryRow['type'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <input type="hidden" id="test_request_id" name="test_request_id" value="">

                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="edit_test_categories">Edit</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>



    <script>
    function openEditModal(testRequestId) {
        $("#test_request_id").val(testRequestId);
    }
    </script>

</body>
</html>
