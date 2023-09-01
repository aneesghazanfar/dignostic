<?php
require_once "../db_connection.php";

$Sql = "SELECT * FROM `users` WHERE `role` = 'patient'";
$result = mysqli_query($conn, $Sql);
$user_id = $_SESSION["user_id"];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_feedback"])) {
    $feedback = $_POST["feedback"];
    $patient_id = $_SESSION["user_id"];
    $admin_id = $_POST["center"];
        $sql = "INSERT INTO feedback (sender_id, receiver_id, message, timestamp)
            VALUES ('$patient_id', '$admin_id', '$feedback', NOW())";
    $conn->query($sql);
    header("Location: patients_massages.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include "slidebar.php"; ?>

    <div class="content">
        <table id="data_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_no']; ?></td>
                    <td>
                        <a href="./massages.php?patient_id=<?php echo $row['id']; ?>&manager=<?php echo $user_id; ?>"
                            class="btn btn-sm btn-info">View Messages</a>
                    </td>
                    <?php
                        echo "<td>";
                        echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#feedback" onclick="feedback(' . $row['id'] . ')"><i class="fa fa-comments"></i></a>';
                        echo "</td>";
                        ?>
                </tr>
                <div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="mt-4">
                        <div class="form-group">
                            <input type="hidden" name="center" id="center">
                            <?php
                        // Retrieve the patient_id from the form                        
                        // Perform the SQL query using the patient_id
                        $sql = "SELECT f.message FROM feedback f, diagnostic_center dc WHERE (f.receiver_id = " . $_SESSION["user_id"] . " AND f.sender_id = " .$row['id'] . ")";
                        
                        // Execute the query and fetch the result
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        // Display the retrieved message
                        echo "Reply: ";
                        echo "<br>";
                        if($row)
                            echo $row['message'];
                        else
                            echo "No message yet";
                        echo "<br>";
                        $sql = "SELECT COUNT(*) FROM feedback WHERE sender_id = " . $_SESSION["user_id"] . "";
                        $result = $conn->query($sql);
                        $row = $result->fetch_row();
                        $count = $row[0];
                        
                        if (!($count > 0)) {
                            ?>
                            <label for="rating">Reply:</label>
                            <textarea class="form-control" name="feedback" id="feedback" required></textarea>
                            <?php
                        }
                        ?>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="submit_feedback">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


    

    <script>
    function feedback(center_id) {
        $("#center").val(center_id);
    }
    </script>
</body>

</html>