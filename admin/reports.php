<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/reports.css">
</head>
<body>

    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>

        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            
            <div class="content">
                <div class="report-container">
                    <h2>Reports Dashboard</h2>
                    <div class="row">
                        <div class="col">
                            <h3>Pending Loans</h3>
                            <div class="chart-container">
                                <canvas id="pendingLoansChart"></canvas>
                            </div>
                        </div>
                        <div class="col">
                            <h3>Total Savings</h3>
                            <div class="chart-container">
                                <canvas id="savingsChart"></canvas>
                            </div>
                        </div>
                        <div class="col">
                            <h3>Total Contributions</h3>
                            <div class="chart-container">
                                <canvas id="contributionsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <?php
    // Connect to the database
    include('../include/db_connect.php');

    // Fetch pending loans
    $query_loans = "SELECT COUNT(loan_id) AS total_loans, SUM(loan_amount) AS total_amount 
                    FROM loans 
                    WHERE loan_status = 'active'";
    $result_loans = $conn->query($query_loans);
    $loan_data = $result_loans->fetch_assoc();

    // Fetch total savings
    $query_savings = "SELECT SUM(total_balance) AS total_savings 
                      FROM savings 
                      WHERE saving_status = 'active'";
    $result_savings = $conn->query($query_savings);
    $savings_data = $result_savings->fetch_assoc();

    // Fetch total contributions
    $query_contributions = "SELECT SUM(contribution_amount) AS total_contributions 
                            FROM contributions";
    $result_contributions = $conn->query($query_contributions);
    $contributions_data = $result_contributions->fetch_assoc();
    ?>

    <script>
        // Pending Loans Chart
        var pendingLoansChart = new Chart(document.getElementById('pendingLoansChart'), {
            type: 'pie',
            data: {
                labels: ['Pending Loans'],
                datasets: [{
                    data: [<?php echo $loan_data['total_loans']; ?>],
                    backgroundColor: ['#FF6347'],
                    label: 'Pending Loans'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Total Pending Loans: ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // Total Savings Chart
        var savingsChart = new Chart(document.getElementById('savingsChart'), {
            type: 'bar',
            data: {
                labels: ['Total Savings'],
                datasets: [{
                    label: 'Total Savings Amount',
                    data: [<?php echo $savings_data['total_savings']; ?>],
                    backgroundColor: ['#4CAF50'],
                    borderColor: ['#388E3C'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Total Contributions Chart
        var contributionsChart = new Chart(document.getElementById('contributionsChart'), {
            type: 'line',
            data: {
                labels: ['Total Contributions'],
                datasets: [{
                    label: 'Contributions Amount',
                    data: [<?php echo $contributions_data['total_contributions']; ?>],
                    backgroundColor: '#2196F3',
                    borderColor: '#1976D2',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
