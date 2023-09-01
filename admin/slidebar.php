<style>
        body {
            padding-top: 50px;
        }

        .sidebar {
            width: 250px;
            background-color: #f1f1f1;
            height: 100%;
            position: fixed;
            padding-top: 20px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .logout-btn {
            margin-top: 10px;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            display: inline;
            margin-right: 10px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
        }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li><li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>







<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="./dashboard.php?dc-managers">Approve DC Manager</a></li>
        <li class="nav-item"><a class="nav-link" href="./diagnostic_center.php">Diagnostic Center</a></li>
        <li class="nav-item"><a class="nav-link" href="./patient_detail.php">View Patient</a></li>
        <li class="nav-item"><a class="nav-link" href="./test_categories.php">Test Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="./test_detail.php">Test Details</a></li>
        <li class="nav-item"><a class="nav-link" href="./test_requests.php">Test Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="./reports.php">View Patient's Reports/Billing Invoices</a></li>
        <li class="nav-item"><a class="nav-link" href="./patients_massages.php">Patient's Messages</a></li>
    </ul>
</div>




<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#data_table').DataTable();
        });
    </script>