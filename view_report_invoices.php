<?php
require_once "./db_connection.php";

if(isset($_GET['report_id'])){
    $sql = "SELECT reports_path	FROM reports  WHERE test_id = " . $_GET['report_id']." AND user_id = ".$_GET['user_id'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo '<iframe src="./reports/'.$row['reports_path'].'" width="100%" height="100%"></iframe>';
    // header("Location: reports.php");
}
elseif(isset($_GET['invoice_id'])){
    var_dump($_GET['user_id']);
    $sql = "SELECT invoices_path FROM invoice  WHERE test_id = " . $_GET['invoice_id']." AND user_id = ".$_GET['user_id'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    // var_dump($row['invoices_path']);
    echo '<iframe src="./reports/'.$row['invoices_path'].'" width="100%" height="100%"></iframe>';
}
?>