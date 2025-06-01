<?php 
include('include/auth.php');
include('../include/db_connect.php');

// Initialize variables
$totalLoans = $pendingApplications = $pendingRepayments = $lastLoan = $totalContributions = $totalSavings = $unreadMessages = 0;

// Fetch data from the database
try {
    // Total Loans Amount
    $totalLoansQuery = $conn->query("SELECT SUM(loan_amount_requested) AS total_loans FROM loanrequests WHERE status = 'disbursed'");
    if ($totalLoansQuery) {
        $result = $totalLoansQuery->fetch_assoc();
        $totalLoans = $result['total_loans'] ?? 0.00;
    } else {
        error_log("Total Loans Query failed: " . $conn->error);
    }

    // Pending Loan Applications
    $pendingApplicationsQuery = $conn->query("SELECT COUNT(*) AS pending_applications FROM loanrequests WHERE status = 'pending'");
    if ($pendingApplicationsQuery) {
        $result = $pendingApplicationsQuery->fetch_assoc();
        $pendingApplications = $result['pending_applications'] ?? 0;
    } else {
        error_log("Pending Applications Query failed: " . $conn->error);
    }

    // Pending Loan Repayments
    $pendingRepaymentsQuery = $conn->query("SELECT SUM(monthly_repayment) AS pending_repayments FROM loanrequests WHERE status = 'disbursed'");
    if ($pendingRepaymentsQuery) {
        $result = $pendingRepaymentsQuery->fetch_assoc();
        $pendingRepayments = $result['pending_repayments'] ?? 0.00;
    } else {
        error_log("Pending Repayments Query failed: " . $conn->error);
    }

    // Last Loan Taken (specific to the logged-in user)
    $user_id = $_SESSION['user_id'] ?? null; 
    $lastLoanQuery = $conn->prepare("SELECT loan_amount_requested FROM loanrequests WHERE member_id = ? ORDER BY requested_at DESC LIMIT 1");
    $lastLoanQuery->bind_param("i", $user_id);
    $lastLoanQuery->execute();
    $lastLoanResult = $lastLoanQuery->get_result();
    if ($lastLoanResult->num_rows > 0) {
        $lastLoan = $lastLoanResult->fetch_assoc()['loan_amount_requested'] ?? 0.00;
    } else {
        error_log("Last Loan Query returned no results for user ID: $user_id");
    }

    // Total Contributions
    $contributionsQuery = $conn->query("SELECT SUM(contribution_amount) AS total_contributions FROM contributions");
    if ($contributionsQuery) {
        $result = $contributionsQuery->fetch_assoc();
        $totalContributions = $result['total_contributions'] ?? 0.00;
    } else {
        error_log("Total Contributions Query failed: " . $conn->error);
    }

    // Total Savings
    $savingsQuery = $conn->query("SELECT SUM(saving_amount) AS total_savings FROM savings WHERE saving_status = 'active'");
    if ($savingsQuery) {
        $result = $savingsQuery->fetch_assoc();
        $totalSavings = $result['total_savings'] ?? 0.00;
    } else {
        error_log("Total Savings Query failed: " . $conn->error);
    }

    // Unread Messages
    $unreadMessagesQuery = $conn->query("SELECT COUNT(*) AS unread_messages FROM messages WHERE status = 'unread'");
    if ($unreadMessagesQuery) {
        $result = $unreadMessagesQuery->fetch_assoc();
        $unreadMessages = $result['unread_messages'] ?? 0;
    } else {
        error_log("Unread Messages Query failed: " . $conn->error);
    }
} catch (Exception $e) {
    error_log("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="juliet-akoth-dashboard-container">
        <div class="juliet-akoth-welcome-section">
            <h2>Welcome to Kenai Chama Dashboard</h2>
        </div>
        <div class="juliet-akoth-dashboard-cards">
            <div class="juliet-akoth-card">
                <i class="fas fa-wallet"></i>
                <h3>Total Loans</h3>
                <p>Total Loans Amount: KSh <?php echo number_format($totalLoans, 2); ?></p>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>Loan Applications</h3>
                <p>Pending Applications: <?php echo $pendingApplications; ?></p>
                <a href="apply-loan.php" class="juliet-akoth-btn-primary">View Applications</a>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-hand-holding-usd"></i>
                <h3>Loan Repayments</h3>
                <p>Pending Repayments: KSh <?php echo number_format($pendingRepayments, 2); ?></p>
                <a href="#loan-repayments" class="juliet-akoth-btn-primary">View Repayments</a>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-history"></i>
                <h3>Your Loan History</h3>
                <p>Last Loan Taken: KSh <?php echo number_format($lastLoan, 2); ?></p>
                <a href="#loan-history" class="juliet-akoth-btn-primary">View History</a>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Total Contributions</h3>
                <p>Total Contributions: KSh <?php echo number_format($totalContributions, 2); ?></p>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-piggy-bank"></i>
                <h3>Total Savings</h3>
                <p>Total Savings: KSh <?php echo number_format($totalSavings, 2); ?></p>
            </div>
            <div class="juliet-akoth-card">
                <i class="fas fa-envelope"></i>
                <h3>Messages</h3>
                <p>You have <?php echo $unreadMessages; ?> unread messages</p>
                <a href="#messages" class="juliet-akoth-btn-primary">View Messages</a>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
