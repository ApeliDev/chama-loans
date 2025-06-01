<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/loans.css">
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <!-- Content Area -->
            <div class="content">
                <div class="loan-management-container">
                    <h2>Approved Loans</h2>
                    <table class="loan-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member Name</th>
                                <th>Loan Amount</th>
                                <th>Interest Rate</th>
                                <th>Loan Term</th>
                                <th>Monthly Repayment</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('../include/db_connect.php');
                            $query = "
                                SELECT l.loan_id, m.full_name, l.loan_amount, l.interest_rate, 
                                       l.loan_term, l.monthly_repayment, l.outstanding_balance, l.loan_status
                                FROM loans l
                                JOIN members m ON l.member_id = m.member_id
                                WHERE l.loan_status = 'active'
                                ORDER BY l.created_at DESC";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $index = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$index}</td>
                                        <td>{$row['full_name']}</td>
                                        <td>{$row['loan_amount']}</td>
                                        <td>{$row['interest_rate']}%</td>
                                        <td>{$row['loan_term']} months</td>
                                        <td>{$row['monthly_repayment']}</td>
                                        <td>{$row['outstanding_balance']}</td>
                                        <td>{$row['loan_status']}</td>
                                        <td>
                                            <button class='btn view-btn' data-id='{$row['loan_id']}'>View Details</button>
                                        </td>
                                    </tr>";
                                    $index++;
                                }
                            } else {
                                echo "<tr><td colspan='9'>No approved loans found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const loanId = button.getAttribute('data-id');
                    window.location.href = `loan_details.php?loan_id=${loanId}`;
                });
            });
        });
    </script>
</body>
</html>
