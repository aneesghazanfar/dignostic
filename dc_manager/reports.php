<?php
require_once "../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["report"])) {
    $id = $_GET["report"];
    // Retrieve the test data from the database
    $sql = "SELECT * FROM test_requests WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // Store the data in variables for pre-filling the edit form
    $user_id = $row["user_id"];
}

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

        <table id="data_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Test Type</th>
                    <th>Center Name</th>
                    <th>Booked By</th>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Invoice</th>
                    <th>Report</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iterate over the fetched data and render table rows
                $sql = "SELECT * FROM test_requests";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['test_name'] . "</td>";
                    echo "<td>" . $row['test_type'] . "</td>";
                    echo "<td>" . $row['center'] . "</td>";
                    $sql = "SELECT name FROM users WHERE id = " . $row['book_by'];
                    $result2 = $conn->query($sql);
                    $row2 = $result2->fetch_assoc();
                    echo "<td>" . $row2['name'] . "</td>";
                    $sql = "SELECT name FROM users WHERE id = " . $row['user_id'];
                    $result2 = $conn->query($sql);
                    $row2 = $result2->fetch_assoc();
                    echo "<td>" . $row2['name'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo '<td>';
                    echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#upload_invoice_modal" onclick="openInvoiceUploadModal(' . $row['id'] . ',' . $row['user_id'] . ')"><i class="fa fa-file"></i></a>';
                    echo '&nbsp;';
                    echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#edit_invoice_modal" onclick="openInvoiceEditModal(' . $row['id'] . ',' . $row['user_id'] . ')"><i class="fa fa-edit"></i></a>';
                    echo '&nbsp;';
                    echo '<a href="../view_report_invoices.php?invoice_id=' . $row['id'] . '&user_id=' . $row['user_id'] . '" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
                    echo '</td>';
                    echo '<td>';
                    echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#upload_report_modal" onclick="openUploadModal(' . $row['id'] . ',' . $row['user_id'] . ')"><i class="fa fa-file"></i></a>';
                    echo '&nbsp;';
                    echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#edit_report_modal" onclick="openEditModal(' . $row['id'] . ',' . $row['user_id'] . ')"><i class="fa fa-edit"></i></a>';
                    echo '&nbsp;';
                    echo '<a href="../view_report_invoices.php?report_id=' . $row['id'] . '&user_id=' . $row['user_id'] . '" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
                    echo '</td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Report Modal -->
<div class="modal fade" id="upload_report_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../process_report.php?report" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="pdf_file">PDF File:</label>
                        <input type="file" class="form-control-file" name="pdf_file" id="pdf_file" required>
                    </div>
                    <input type="hidden" id="test_request_id" name="test_request_id" value="">
                    <input type="hidden" id="user_id" name="user_id" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_report">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_report_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../process_report.php?report" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="pdf_file">PDF File:</label>
                        <input type="file" class="form-control-file" name="pdf_file" id="pdf_file" required>
                    </div>
                    <input type="hidden" id="test_request_id" name="test_request_id" value="">
                    <input type="hidden" id="user_id" name="user_id" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_report">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../process_report.php?invoice" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="pdf_file">PDF File:</label>
                        <input type="file" class="form-control-file" name="pdf_file" id="pdf_file" required>
                    </div>
                    <input type="hidden" id="test_id" name="test_id" value="">
                    <input type="hidden" id="user" name="user" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_report">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../process_report.php?invoice" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="pdf_file">PDF File:</label>
                        <input type="file" class="form-control-file" name="pdf_file" id="pdf_file" required>
                    </div>
                    <input type="hidden" id="test_id" name="test_id" value="">
                    <input type="hidden" id="user" name="user" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="submit_report">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openUploadModal(testRequestId, userId) {
        $("#test_request_id").val(testRequestId);
        $("#user_id").val(userId);
    } 
    
    function openEditModal(testRequestId, userId) {
        $("#test_request_id").val(testRequestId);
        $("#user_id").val(userId);
    }

    function openInvoiceUploadModal(testRequestId, userId) {
        $("#test_id").val(testRequestId);
        $("#user").val(userId);
    }
    
    function openInvoiceEditModal(testRequestId, userId) {
        $("#test_id").val(testRequestId);
        $("#user").val(userId);
    }
</script>
</body>
</html>
