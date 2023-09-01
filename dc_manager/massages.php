<?php
// Database connection
require_once "../db_connection.php";
// Start the session

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the user is an admin or a patient
$user_role = $_SESSION["role"];

// Send message from patient to admin
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["send_message"])) {
    $message = $_POST["message"];
    $patient_id = $_GET['patient_id']; // Replace with the actual patient ID
    if(@$_GET['manager'])
        $admin_id = $_GET['manager'];
    else
        $admin_id = 1; // Replace with the actual admin ID

    // Insert the message into the database
    if($user_role === "patient"){
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, timestamp)
            VALUES ('$patient_id', '$admin_id', '$message', NOW())";
    }
    else{
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, timestamp)
            VALUES ('$admin_id', '$patient_id', '$message', NOW())";
    }
    $conn->query($sql);
}

// Retrieve messages for admin
// if ($user_role === "admin") {
    $patient_id = $_GET['patient_id']; // Replace with the actual patient ID

    if($_GET['manager']){
        $admin_id = $_GET['manager'];
        $sql = "SELECT * FROM messages WHERE (sender_id = '$admin_id' AND receiver_id = '$patient_id')
        OR (sender_id = '$patient_id' AND receiver_id = '$admin_id') AND sender_id !=1
        ORDER BY timestamp ASC";
    }
    else{
        $admin_id = 1;

    // Fetch messages between admin and patient
    $sql = "SELECT * FROM messages WHERE (sender_id = '$admin_id' AND receiver_id = '$patient_id')
            OR (sender_id = '$patient_id' AND receiver_id = '$admin_id')
            ORDER BY timestamp ASC";
    }
    $result = $conn->query($sql);
    $messages = $result->fetch_all(MYSQLI_ASSOC);
// }
?>


<!DOCTYPE html>
<html>
<head>
  <title>Messaging App</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
  margin-top: 50px;
}

.messages {
  margin-bottom: 20px;
}

.message {
  background-color: #fff;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
}

.message .sender {
  font-weight: bold;
}

.message .timestamp {
  color: #6c757d;
  font-size: 12px;
}

.message .content {
  margin-top: 5px;
}

.reply-form {
  margin-top: 20px;
}
</style>
</head>
<body>
  <?php
    include "slidebar.php";
?>
  <div class="container">
    <h1>Messaging</h1>
    <!-- Admin Messaging Interface -->
    <div class="messages" id="message-container" style="height: 300px; overflow-y: auto;">
  <?php foreach ($messages as $message): 
    if ($message["sender_id"] == $_SESSION["user_id"])
        echo '<div class="message" style="text-align: right;">';
    else
        echo '<div class="message">';
  ?>
      <div class="sender">
        <?php
        $sql1 = "SELECT name FROM users WHERE id = ".$message["sender_id"];
        $result1 = $conn->query($sql1);
        $sender = $result1->fetch_assoc();
        echo $sender["name"];
        ?>
      </div>
      <br>
      <div class="content"><?php echo $message["message"]; ?></div>
      <div class="timestamp"><?php echo $message["timestamp"]; ?></div>
    </div>
  <?php endforeach; ?>
</div>



<form method="POST" action="" class="reply-form">
  <div class="form-group">
    <textarea class="form-control" name="message" rows="3" placeholder="Write your reply"></textarea>
  </div>
  <button type="submit" class="btn btn-primary" name="send_message">Send</button>
</form>

</div>
</body>
<script>
  // Scroll to the last message
  var messageContainer = document.getElementById("message-container");
  messageContainer.scrollTop = messageContainer.scrollHeight;
</script>
</html>
