<?php
require_once "../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_test_categories"])) {
    // Retrieve form data
    $type = $_POST["type"];
    $sql = "INSERT INTO test_categories (type) VALUES ('$type')";
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
    $sql = "DELETE FROM test_categories WHERE id = '$id'";
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
    $id = $_POST["edit_id"];
    $type = $_POST["edit_type"];
    // Update the test category data in the database
    $sql = "UPDATE test_categories SET type = '$type' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful update
        header("Location: test_categories.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}


$sql = "SELECT * FROM test_categories";
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
                echo '<a class="btn btn-primary" href="?edit=' . $row['id'] . '"><i class="fa fa-pencil"></i></i></a>';
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
                            <label for="type">Type:</label>
                            <input type="text" class="form-control" name="type" required>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Test categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <div class="form-group">
                            <label for="edit_type">Type:</label>
                            <input type="text" class="form-control" name="edit_type" id="edit_type" required>
                        </div>
                        <input type="hidden" id="edit_id" name="edit_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_test_categories">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($id)) : ?>
        <script>
        // Execute the JavaScript function after the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            editDiagnosticCenter(<?php echo $id; ?>, "<?php echo $type; ?>");
        });

        function editDiagnosticCenter(id, type) {
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_type").value = type;
            $("#edit_test_categories").modal("show");
        }
        </script>
    <?php endif; ?>
</body>
</html>
