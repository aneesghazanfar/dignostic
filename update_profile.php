<?php
require_once "./db_connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    
    // Update the user data in the database
    $user_id = $_SESSION["user_id"];
    $sql = "UPDATE users SET name = '$name', email = '$email', phone_no = '$phone' WHERE id = $user_id";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to the profile page after successful update
        // redirect back
        
        if(isset($_GET['p']))
            header("Location: ./patient/dashboard.php");
        else
            header("Location: ./dc_manager/dashboard.php");
        exit();
    } else {
        // Handle any errors that occur during the SQL query execution
        echo "Error: " . mysqli_error($conn);
    }
}
?>
