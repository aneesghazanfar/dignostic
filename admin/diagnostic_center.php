<?php
require_once "../db_connection.php";

// Handle delete action
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM diagnostic_center WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the diagnostic_center page after successful deletion
        header("Location: diagnostic_center.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle edit action
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["edit"])) {
    $id = $_GET["edit"];
    // Retrieve the diagnostic center data from the database
    $sql = "SELECT * FROM diagnostic_center WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Store the data in variables for pre-filling the edit form
    $editId = $row["id"];
    $editName = $row["name"];
    $editLocation = $row["location"];
    $editPhoneNo = $row["phone_no"];
    $editEmail = $row["email"];
}

// Handle form submission for adding a diagnostic center
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_diagnostic_center"])) {
    // Retrieve form data
    $name = $_POST["Name"];
    $location = $_POST["Location"];
    $phoneNo = $_POST["phone_no"];
    $email = $_POST["email"];
    $sql = "INSERT INTO diagnostic_center (name, location, phone_no, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $location, $phoneNo, $email);

    if ($stmt->execute()) {
        // Redirect to the diagnostic_center page after successful insertion
        header("Location: diagnostic_center.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for edit
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_diagnostic_center"])) {
    // Retrieve form data
    $id = $_POST["id"];
    $editName = $_POST["edit_Name"];
    // var_dump($id);
    // die();
    $editLocation = $_POST["edit_Location"];
    $editPhoneNo = $_POST["edit_phone_no"];
    $editEmail = $_POST["edit_email"];
    $sql = "UPDATE diagnostic_center SET name = ?, location = ?, phone_no = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $editName, $editLocation, $editPhoneNo, $editEmail, $id);

    if ($stmt->execute()) {
        // Redirect to the diagnostic_center page after successful update
        header("Location: diagnostic_center.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$sql = "SELECT * FROM diagnostic_center";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Diagnostic Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include "slidebar.php"; ?>
    <div class="content">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_diagnostic_center">
            Add Diagnostic Center
        </button>
        <table id="data_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['location'] . '</td>';
                    echo '<td>' . $row['phone_no'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['rating'] . '</td>';
                    echo '<td>';
                    // echo '<a class="btn btn-primary" href="?edit=' . $row['id'] . '"><i class="fa fa-pencil"></i>
                    // </a>';
                    echo '&nbsp;';
                    ?>
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#edit_diagnostic_center<?php echo $row['id']; ?>">
                    <i class="fa fa-pencil"></i>
                </button>
                <?php
                    echo '<a class="btn btn-danger" href="?delete=' . $row['id'] . '"><i class="fa fa-trash"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                    ?>
                <div class="modal fade" id="edit_diagnostic_center<?php echo $row['id']; ?>" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Diagnostic Center</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                                    <input type="hidden" name="id" id="edit_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="Name">Center Name:</label>
                                        <input type="text" class="form-control" name="edit_Name" required
                                            value="<?php echo $row['name'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="Name">Center Location:</label>
                                        <input type="text" class="form-control" name="edit_Location" required
                                            value="<?php echo $row['location'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Center Phone No:</label>
                                        <input type="text" class="form-control" name="edit_phone_no" required
                                            value="<?php echo $row['phone_no'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="text">Center Email:</label>
                                        <input type="text" class="form-control" name="edit_email" required
                                            value="<?php echo $row['email'];?>">
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"
                                        name="edit_diagnostic_center">Update</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </tbody>
        </table>

        <div class="modal fade" id="add_diagnostic_center" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Diagnostic Center</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                            <div class="form-group">
                                <label for="Name">Center Name:</label>
                                <input type="text" class="form-control" name="Name" required>
                            </div>
                            <div class="form-group">
                                <label for="Name">Center Location:</label>
                                <input type="text" class="form-control" name="Location" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Center Phone No:</label>
                                <input type="text" class="form-control" name="phone_no" required>
                            </div>
                            <div class="form-group">
                                <label for="text">Center Email:</label>
                                <input type="text" class="form-control" name="email" required>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_diagnostic_center">Add</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


    </div>
</body>

</html>