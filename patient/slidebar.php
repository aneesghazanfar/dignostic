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
<div class="navbar">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../about.php">About</a></li>
            <li><a class="logout-btn" href="dashboard.php?logout">Logout</a></li>
            <li><a href="./profile.php">Profile</a></li>

        </ul>
</div>

<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="./d_center.php">Diagnostic Centers</a></li>
        <li class="nav-item"><a class="nav-link" href="./test_request.php">Test Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="./test_requests.php">Test Requests Status</a></li>
        <li class="nav-item"><a class="nav-link" href="./massages.php?patient_id=<?php echo $_SESSION['user_id']; ?>">Admin Massages</a></li>
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