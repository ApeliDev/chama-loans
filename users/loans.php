<?php 
include('include/auth.php');
include('../include/db_connect.php'); 
include('maintenance_check.php');
// Fetch member_id from the session
$member_id = $_SESSION['user_id'];

// Fetch current loan overview details
$overview_query = "
    SELECT 
        LoanRequests.loan_amount_requested, 
        LoanRequests.status, 
        LoanRequests.monthly_repayment, 
        LoanRequests.loan_term, 
        (LoanRequests.loan_amount_requested - IFNULL(SUM(LoanRepayments.repayment_amount), 0)) AS outstanding_balance,
        DATE_ADD(LoanRequests.requested_at, INTERVAL 1 MONTH) AS next_payment_date
    FROM LoanRequests
    LEFT JOIN LoanRepayments ON LoanRequests.request_id = LoanRepayments.loan_id
    WHERE LoanRequests.member_id = ? AND LoanRequests.status IN ('approved', 'disbursed')
    GROUP BY LoanRequests.request_id
    ORDER BY LoanRequests.requested_at DESC LIMIT 1
";
$overview_stmt = $conn->prepare($overview_query);
$overview_stmt->bind_param("i", $member_id);
$overview_stmt->execute();
$overview_result = $overview_stmt->get_result();
$current_loan = $overview_result->fetch_assoc();

// Fetch loan history
$history_query = "
    SELECT 
        LoanRequests.requested_at AS loan_date, 
        LoanRequests.loan_amount_requested, 
        LoanRequests.status, 
        (LoanRequests.loan_amount_requested - IFNULL(SUM(LoanRepayments.repayment_amount), 0)) AS balance
    FROM LoanRequests
    LEFT JOIN LoanRepayments ON LoanRequests.request_id = LoanRepayments.loan_id
    WHERE LoanRequests.member_id = ?
    GROUP BY LoanRequests.request_id
    ORDER BY LoanRequests.requested_at DESC
";
$history_stmt = $conn->prepare($history_query);
$history_stmt->bind_param("i", $member_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Loan Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">      
    <style>
        .loan-overview, .loan-history {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .loan-overview h3, .loan-history h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .loan-overview p, .loan-history td, .loan-history th {
            font-size: 1.2em;
        }
        .loan-history table {
            width: 100%;
            border-collapse: collapse;
        }
        .loan-history th, .loan-history td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .loan-history th {
            background-color: #f2f2f2;
            color: #333;
        }
        .loan-history tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="juliet-akoth-container">
        <h2>Kenai Chama - Loan Management</h2>
        <p>Manage your loans, apply for new loans, and track loan history</p>
        
        <!-- Current Loan Overview Section -->
        <div class="loan-overview">
            <h3>Current Loan Overview</h3>
            <p>Loan Amount: KSh <?php echo $current_loan ? number_format($current_loan['loan_amount_requested'], 2) : '0.00'; ?></p>
            <p>Outstanding Balance: KSh <?php echo $current_loan ? number_format($current_loan['outstanding_balance'], 2) : '0.00'; ?></p>
            <p>Next Payment Date: <?php echo $current_loan ? date("Y-m-d", strtotime($current_loan['next_payment_date'])) : 'N/A'; ?></p>
            <p>Loan Status: <?php echo $current_loan ? ucfirst($current_loan['status']) : 'None'; ?></p>
        </div>

        <!-- Loan History Section -->
        <div class="loan-history">
            <h3>Loan History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Loan Date</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($history_result->num_rows > 0): ?>
                        <?php while ($loan = $history_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date("Y-m-d", strtotime($loan['loan_date'])); ?></td>
                                <td>KSh <?php echo number_format($loan['loan_amount_requested'], 2); ?></td>
                                <td><?php echo ucfirst($loan['status']); ?></td>
                                <td>KSh <?php echo number_format($loan['balance'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No loan history available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
