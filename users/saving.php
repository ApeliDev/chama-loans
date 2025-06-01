<?php
include('include/auth.php');
include('../include/db_connect.php');
include('maintenance_check.php');
$member_id = $_SESSION['user_id'];


//total balance
$totalSavings = 0;
$sqlTotal = "SELECT SUM(saving_amount) AS total_savings FROM Savings WHERE member_id = ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("i", $member_id);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
if ($row = $resultTotal->fetch_assoc()) {
    $totalSavings = $row['total_savings'];
}

//saving history
$savingsHistory = [];
$sqlHistory = "SELECT created_at, saving_amount FROM Savings WHERE member_id = ? ORDER BY created_at DESC";
$stmtHistory = $conn->prepare($sqlHistory);
$stmtHistory->bind_param("i", $member_id);
$stmtHistory->execute();
$resultHistory = $stmtHistory->get_result();
while ($row = $resultHistory->fetch_assoc()) {
    $savingsHistory[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Savings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/savings.css">  
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="juliet-akoth-container">
        <div class="savings-section">
            <div class="card">
                <h3>Your Total Savings</h3>
                <p>Total Savings: KSh <?php echo number_format($totalSavings, 2); ?></p>
            </div>
            <div class="card">
                <h3>Make a Deposit</h3>
                <button id="makeDepositBtn" class="btn-primary">Make Deposit</button>
            </div>
            <div class="card">
                <h3>Your Savings History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount (KSh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($savingsHistory as $saving): ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i", strtotime($saving['created_at'])); ?></td>
                            <td>KSh <?php echo number_format($saving['saving_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="depositModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h3>Make a Deposit</h3>
            <form action="make_deposit.php" method="POST">
                <label for="deposit_amount">Amount (KSh): </label>
                <input type="number" id="deposit_amount" name="deposit_amount" required min="1" step="0.01" placeholder="Enter amount">
                <button type="submit" name="submit_deposit" class="btn-primary">Make Deposit</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById("depositModal");
        const makeDepositBtn = document.getElementById("makeDepositBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");

        // Open the modal
        makeDepositBtn.onclick = function() {
            modal.style.display = "block";
        }

        // Close the modal
        closeModalBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal if clicked outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <script src="js/main.js"></script>
</body>
</html>
