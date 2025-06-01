<?php 
include('include/auth.php');
include('../include/db_connect.php');
include('maintenance_check.php');
// Initialize variables to avoid undefined warnings
$totalLoans = $pendingApplications = $pendingRepayments = $lastLoan = $totalContributions = $totalSavings = $unreadMessages = 0;

// Fetch data from the database
try {
    // Total Loans Amount
    $totalLoansQuery = $conn->query("SELECT SUM(loan_amount) AS total_loans FROM Loans WHERE loan_status = 'active'");
    if ($totalLoansQuery) {
        $result = $totalLoansQuery->fetch_assoc();
        $totalLoans = $result['total_loans'] ?? 0.00;
    }

    // Pending Loan Applications
    $pendingApplicationsQuery = $conn->query("SELECT COUNT(*) AS pending_applications FROM Loans WHERE loan_status = 'pending'");
    if ($pendingApplicationsQuery) {
        $result = $pendingApplicationsQuery->fetch_assoc();
        $pendingApplications = $result['pending_applications'] ?? 0;
    }

    // Pending Loan Repayments
    $pendingRepaymentsQuery = $conn->query("SELECT SUM(outstanding_balance) AS pending_repayments FROM Loans WHERE loan_status = 'active'");
    if ($pendingRepaymentsQuery) {
        $result = $pendingRepaymentsQuery->fetch_assoc();
        $pendingRepayments = $result['pending_repayments'] ?? 0.00;
    }

    // Last Loan Taken
    $user_id = $_SESSION['user_id'] ?? null; // Assuming the user ID is stored in the session
    $lastLoanQuery = $conn->prepare("SELECT loan_amount FROM Loans WHERE member_id = ? ORDER BY created_at DESC LIMIT 1");
    $lastLoanQuery->bind_param("i", $user_id);
    $lastLoanQuery->execute();
    $lastLoanResult = $lastLoanQuery->get_result();
    if ($lastLoanResult->num_rows > 0) {
        $lastLoan = $lastLoanResult->fetch_assoc()['loan_amount'] ?? 0.00;
    }

    // Total Contributions
    $contributionsQuery = $conn->query("SELECT SUM(amount) AS total_contributions FROM Contributions");
    if ($contributionsQuery) {
        $result = $contributionsQuery->fetch_assoc();
        $totalContributions = $result['total_contributions'] ?? 0.00;
    }

    // Total Savings
    $savingsQuery = $conn->query("SELECT SUM(amount) AS total_savings FROM Savings");
    if ($savingsQuery) {
        $result = $savingsQuery->fetch_assoc();
        $totalSavings = $result['total_savings'] ?? 0.00;
    }

    // Unread Messages
    $unreadMessagesQuery = $conn->query("SELECT COUNT(*) AS unread_messages FROM Messages WHERE status = 'unread'");
    if ($unreadMessagesQuery) {
        $result = $unreadMessagesQuery->fetch_assoc();
        $unreadMessages = $result['unread_messages'] ?? 0;
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
