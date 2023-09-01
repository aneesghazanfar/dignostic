<?php
require_once "./db_connection.php";
if(isset($_GET['report'])){
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_report"])) {
    $testRequestId = $_POST["test_request_id"];
    $userId = $_POST["user_id"];
    global $filename;
    // Process the uploaded file
    $uploadDir ="./reports/";
    $targetFilePath =  $filename;
    
    // Check if a report already exists for the given test request and user
    $checkSql = "SELECT * FROM reports WHERE test_id = '$testRequestId' AND user_id = '$userId'";
    $checkResult = $conn->query($checkSql);
    
    
    // Update the existing report path
    if ($checkResult->num_rows > 0) {
        $sql1 = "SELECT reports_path FROM reports WHERE test_id = '$testRequestId' AND user_id = '$userId'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();
        $filename = $row1["reports_path"];

    } else {
            $filename = uniqid() . ".pdf";

            // Insert new data into the reports table
            $insertSql = "INSERT INTO reports (test_id, user_id, reports_path) VALUES ('$testRequestId', '$userId', '$filename')";
            if ($conn->query($insertSql) === true) {
                echo "Report uploaded successfully.";
            } else {
                echo "Error inserting report data: " . $conn->error;
            }
        }
        $uploadSuccess = move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $uploadDir . $filename);
        $sql = "UPDATE test_requests SET status='Completed' WHERE id=$testRequestId";
        if (mysqli_query($conn, $sql)) {
            // Redirect to the desired page after successful deletion
        } else {
            // Handle any errors that occur during the SQL query execution
            echo "Error: " . mysqli_error($conn);
        }
        header("Location: reports.php");


}
}
if(isset($_GET['invoice'])){
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_report"])) {
        $testRequestId = $_POST["test_id"];
        $userId = $_POST["user"];
        global $filename;
        // Process the uploaded file
        $uploadDir ="./reports/";
        $targetFilePath =  $filename;
        
        // Check if a report already exists for the given test request and user
        $checkSql = "SELECT * FROM invoice WHERE test_id = '$testRequestId' AND user_id = '$userId'";
        $checkResult = $conn->query($checkSql);
        
        
        // Update the existing report path
        if ($checkResult->num_rows > 0) {
            $sql1 = "SELECT invoices_path FROM invoice WHERE test_id = '$testRequestId' AND user_id = '$userId'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();
            $filename = $row1["reports_path"];
    
        } else {
                $filename = uniqid() . ".pdf";
    
                // Insert new data into the reports table
                $insertSql = "INSERT INTO invoice (test_id, user_id, invoices_path) VALUES ('$testRequestId', '$userId', '$filename')";
                if ($conn->query($insertSql) === true) {
                    echo "Report uploaded successfully.";
                } else {
                    echo "Error inserting report data: " . $conn->error;
                }
            }
            $uploadSuccess = move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $uploadDir . $filename);
            $sql = "UPDATE test_requests SET status='Invoice Upload' WHERE id=$testRequestId";
            if (mysqli_query($conn, $sql)) {
                // Redirect to the desired page after successful deletion
            } else {
                // Handle any errors that occur during the SQL query execution
                echo "Error: " . mysqli_error($conn);
            }
            
            
        }
    }
    if($_SESSION['role'] == 'admin'){
        header("Location: admin/reports.php");
    }elseif($_SESSION['role'] == 'dc_manager'){
        header("Location: ./dc_manager/reports.php");
    }
?>
