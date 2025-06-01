<?php
include('include/auth.php');


$pendingLoans = 125;  
$totalSavings = 50000;  
$totalContributions = 120000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>

        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>

            <!-- Content Area -->
            <div class="content">
                <h2>Dashboard Overview</h2>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <h3>Pending Loans</h3>
                            <div class="stat-value"><?php echo $pendingLoans; ?></div>
                            <div class="stat-label">Total Pending Loans</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <h3>Total Savings</h3>
                            <div class="stat-value">Ksh.<?php echo number_format($totalSavings, 2); ?></div>
                            <div class="stat-label">Total Savings Amount</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <h3>Total Contributions</h3>
                            <div class="stat-value">Ksh.<?php echo number_format($totalContributions, 2); ?></div>
                            <div class="stat-label">Total Contribution Amount</div>
                        </div>
                    </div>
                </div>

                <!-- Additional Section -->
                <div class="additional-section">
                    <h3>Recent Activities</h3>
                    <div class="recent-activity">
                        <div class="activity-item">
                            <span>Loan #1253 issued</span>
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-item">
                            <span>New savings account created by Livingstone Apeli</span>
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-item">
                            <span>Contribution of $500 added to account #5678</span>
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="activity-item">
                            <span>Loan #1289 closed</span>
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
