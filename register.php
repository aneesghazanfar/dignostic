<?php
// Include the database connection file
require_once "db_connection.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $name = $_POST["name"];
    $phone_no = $_POST["phone_no"];

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        echo "Username already exists. Please choose a different username.";
    } else {
        // Encrypt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $insertSql = "INSERT INTO users (username, password, role, email, name, phone_no) VALUES ('$username', '$hashedPassword', '$role', '$email', '$name', '$phone_no')";

        if (mysqli_query($conn, $insertSql)) {
            // Get the inserted user ID
            $userId = mysqli_insert_id($conn);

            // Update the user ID in the diagnostic_center table
            $dcNameId = $_POST["dcName"];
            $updateSql = "UPDATE diagnostic_center SET user_id = '$userId' WHERE id = '$dcNameId'";
            mysqli_query($conn, $updateSql);

            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
        }
    }
}

// Retrieve diagnostic center names
$dcSql = "SELECT id, name FROM diagnostic_center";
$dcResult = mysqli_query($conn, $dcSql);
$dcOptions = "";

if (mysqli_num_rows($dcResult) > 0) {
    while ($dcRow = mysqli_fetch_assoc($dcResult)) {
        $dcOptions .= "<option value='" . $dcRow['id'] . "'>" . $dcRow['name'] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item">
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
        <h2 class="mt-4">Registration</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone_no">Phone Number:</label>
                <input type="text" class="form-control" name="phone_no" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Role:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" value="dc_manager" required>
                    <label class="form-check-label">DC Manager</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" value="patient" required>
                    <label class="form-check-label">Patient</label>
                </div>
            </div>
            <div class="form-group" id="dcNameContainer" style="display: none;">
                <label for="dcName">Diagnostic Center Name:</label>
                <select class="form-control" name="dcName" id="dcName">
                    <?php echo $dcOptions; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Show the Diagnostic Center Name field when DC Manager role is selected
        document.querySelector('input[name="role"]').addEventListener('change', function() {
            var dcNameContainer = document.getElementById('dcNameContainer');
            dcNameContainer.style.display = (this.value === 'dc_manager') ? 'block' : 'none';
        });
    </script>
</body>
</html>
