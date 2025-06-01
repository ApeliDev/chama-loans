<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Applications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .loan-applications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .loan-applications-table thead {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .loan-applications-table th, .loan-applications-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .loan-applications-table th {
            font-size: 16px;
            text-transform: uppercase;
        }

        .loan-applications-table tbody tr:hover {
            background-color: #f0f2f5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .approve-btn, .reject-btn {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }

        .approve-btn {
            background-color: #27ae60;
        }

        .reject-btn {
            background-color: #e74c3c;
        }

        .loan-container {
            margin: 20px auto;
            padding: 20px;
        }

        h2 {
            color: #34495e;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <!-- Content Area -->
            <div class="content">
                <div class="loan-container">
                    <h2>Loan Applications</h2>
                    <table class="loan-applications-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Applicant</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="loan-applications">
                            <?php
                            // Connect to the database
                            include('../include/db_connect.php');

                            // Fetch loan applications
                            $sql = "SELECT lr.request_id, lr.loan_amount_requested, lr.loan_purpose, lr.requested_at, lr.status, m.full_name 
                                    FROM loanrequests lr 
                                    JOIN members m ON lr.member_id = m.member_id 
                                    WHERE lr.status = 'pending'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $index = 1;
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $index; ?></td>
                                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['loan_amount_requested']); ?></td>
                                        <td><?php echo htmlspecialchars($row['loan_purpose']); ?></td>
                                        <td><?php echo htmlspecialchars($row['requested_at']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="post" action="update_loan_status.php" style="display:inline;">
                                                    <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($row['request_id']); ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="approve-btn" 
                                                        onclick="confirmAction(event, 'approve')">Approve</button>
                                                </form>
                                                <form method="post" action="update_loan_status.php" style="display:inline;">
                                                    <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($row['request_id']); ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="reject-btn" 
                                                        onclick="confirmAction(event, 'reject')">Reject</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $index++;
                                }
                            } else {
                                echo "<tr><td colspan='7'>No loan applications found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <script>
        function confirmAction(event, actionType) {
            const confirmationMessage = actionType === 'approve' 
                ? "Are you sure you want to approve this loan?" 
                : "Are you sure you want to reject this loan?";

            if (!confirm(confirmationMessage)) {
                // Prevent form submission if the user cancels
                event.preventDefault();
            }
        }
    </script>

</body>
</html>
