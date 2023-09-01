<?php
require_once "../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_patient"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $phone_no = $_POST["phone_no"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["name"], PASSWORD_DEFAULT); // Using patient name as the password
    $role = "patient";

    // Insert the patient data into the users table
    $sql = "INSERT INTO users (name, phone_no, email, username, password, role) 
            VALUES ('$name', '$phone_no', '$email', '$username', '$password', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful insertion
        header("Location: patient_detail.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_patient"])) {
    $id = $_POST["edit_id"];
    $name = $_POST["edit_name"];
    $phone_no = $_POST["edit_phone_no"];
    $email = $_POST["edit_email"];
    $username = $_POST["edit_username"];

    // Update the patient data in the users table
    $sql = "UPDATE users SET name='$name', phone_no='$phone_no', email='$email', username='$username' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the index page after successful update
        header("Location: patient_detail.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit"])) {
    $id = $_GET["edit"];
    // Retrieve the patient data from the database
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // Store the data in variables for pre-filling the edit form
    $name = $row["name"];
    $phone_no = $row["phone_no"];
    $email = $row["email"];
    $username = $row["username"];
}

$sql = "SELECT * FROM users WHERE role = 'patient'";
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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_patient">
            Add Patient
        </button>
        <?php
            echo '<h3>Patient Details</h3>';
            echo '<table id="data_table" class="table table-striped">';
            echo '<thead><tr><th>Name</th><th>Phone Number</th><th>Email</th><th>Username</th><th>Action</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['phone_no'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>';
                echo '<a class="btn btn-primary edit-btn" href="#" data-id="' . $row['id'] . '"><i class="fa fa-pencil"></i></a>';
                echo '&nbsp;';
                echo '<a class="btn btn-danger" href="?delete=' . $row['id'] . '"><i class="fa fa-trash"></i></a>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        
        ?>
    </div>

    <div class="modal fade" id="add_patient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Phone Number:</label>
                            <input type="text" class="form-control" name="phone_no" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_patient">Add</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_patient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="form-group">
                            <label for="edit_username">Username:</label>
                            <input type="text" class="form-control" name="edit_username" id="edit_username" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_name">Name:</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone_no">Phone Number:</label>
                            <input type="text" class="form-control" name="edit_phone_no" id="edit_phone_no" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email:</label>
                            <input type="email" class="form-control" name="edit_email" id="edit_email" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="update_patient">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).closest('tr').find('td:nth-child(1)').text();
            var phone_no = $(this).closest('tr').find('td:nth-child(2)').text();
            var email = $(this).closest('tr').find('td:nth-child(3)').text();
            var username = $(this).closest('tr').find('td:nth-child(4)').text();

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_phone_no').val(phone_no);
            $('#edit_email').val(email);
            $('#edit_username').val(username);

            $('#edit_patient').modal('show');
        });
    });
    </script>
</body>
</html>



