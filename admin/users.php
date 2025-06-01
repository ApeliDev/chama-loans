<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .members-table thead {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .members-table th, .members-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .members-table th {
            font-size: 16px;
            text-transform: uppercase;
        }

        .members-table tbody tr:hover {
            background-color: #f0f2f5;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 5px 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-view {
            background-color: #3498db;
            color: #fff;
        }

        .btn-view:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <div class="content">
                <h2>Members List</h2>
                <p>Here are the details of all registered members:</p>
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Savings Balance</th>
                            <th>Loan Balance</th>
                            <th>Total Contributions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include('fetch_members.php'); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
