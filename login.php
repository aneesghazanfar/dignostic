<?php
// Include the database connection file
require_once "db_connection.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $_SESSION["username"] = $username;
    $sql = "SELECT id ,role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);


    $_SESSION["role"] = $row["role"];
 

    // Retrieve the hashed password from the database
    $sql = "SELECT id, password, role, approved FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row["password"];

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, redirect to the dashboard
            if($row["role"] == "admin"){
                header("Location: admin/dashboard.php");
                $_SESSION["user_id"] = $row["id"];
            }
            elseif($row["role"] == "dc_manager"){
                if($row["approved"] == 0){
                    echo "<script>alert('Your account is not approved yet. Please wait for approval.');</script>";
                    // header("Location: login.php");    
                }
                else{
                    $_SESSION["user_id"] = $row["id"];
                    header("Location: dc_manager/dashboard.php");
                }
            }
            elseif($row["role"] == "patient"){
                $_SESSION["user_id"] = $row["id"];
                header("Location: patient/dashboard.php");
            }
            // exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        <h2 class="mt-4">Login</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="register.php">Create New Account</a></p>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

