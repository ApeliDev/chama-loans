<?php 
include('include/auth.php');
include('../include/db_connect.php');
include('maintenance_check.php');
$member_id = $_SESSION['user_id'];

// Fetch the active loans for the member
$sqlLoans = "SELECT loan_id, loan_amount, interest_rate, loan_term, monthly_repayment, outstanding_balance FROM Loans WHERE member_id = ? AND loan_status = 'active'";
$stmtLoans = $conn->prepare($sqlLoans);
$stmtLoans->bind_param("i", $member_id);
$stmtLoans->execute();
$resultLoans = $stmtLoans->get_result();
$loans = [];
while ($row = $resultLoans->fetch_assoc()) {
    $loans[] = $row;
}

// Calculate total loan balance
$totalLoanBalance = 0;
$sqlLoanBalance = "SELECT SUM(outstanding_balance) AS total_loan_balance FROM Loans WHERE member_id = ? AND loan_status = 'active'";
$stmtLoanBalance = $conn->prepare($sqlLoanBalance);
$stmtLoanBalance->bind_param("i", $member_id);
$stmtLoanBalance->execute();
$resultLoanBalance = $stmtLoanBalance->get_result();
if ($row = $resultLoanBalance->fetch_assoc()) {
    $totalLoanBalance = $row['total_loan_balance'];
}

// Loan repayment history
$loanRepaymentHistory = [];
$sqlRepaymentHistory = "SELECT repayment_date, repayment_amount FROM LoanRepayments WHERE member_id = ? ORDER BY repayment_date DESC";
$stmtRepaymentHistory = $conn->prepare($sqlRepaymentHistory);
$stmtRepaymentHistory->bind_param("i", $member_id);
$stmtRepaymentHistory->execute();
$resultRepaymentHistory = $stmtRepaymentHistory->get_result();
while ($row = $resultRepaymentHistory->fetch_assoc()) {
    $loanRepaymentHistory[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Loan Repayment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/contributions.css">
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="juliet-akoth-container">
        <div class="contribution-section">
            <div class="card">
                <h3>Your Total Loan Balance</h3>
                <p>Total Loan Balance: KSh <?php echo number_format($totalLoanBalance, 2); ?></p>
            </div>

            <div class="card">
                <h3>Make a Repayment</h3>
                <!-- Replace button with a select dropdown -->
                <button id="makeRepaymentBtn" class="btn-primary">Make Repayment</button>
            </div>

            <div class="card">
                <h3>Your Loan Repayment History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount (KSh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loanRepaymentHistory as $repayment): ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i", strtotime($repayment['repayment_date'])); ?></td>
                            <td>KSh <?php echo number_format($repayment['repayment_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for repayment -->
    <div id="repaymentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h3>Make a Loan Repayment</h3>
            <form action="make_repayment.php" method="POST">
                <label for="loan_id">Select Loan: </label>
                <select id="loan_id" name="loan_id" required>
                    <?php foreach ($loans as $loan): ?>
                    <option value="<?php echo $loan['loan_id']; ?>">
                        <?php echo "Loan ID: " . $loan['loan_id'] . " | Amount: KSh " . number_format($loan['loan_amount'], 2) . " | Outstanding Balance: KSh " . number_format($loan['outstanding_balance'], 2); ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <label for="amount">Amount (KSh): </label>
                <input type="number" id="amount" name="amount" required min="1" step="0.01" placeholder="Enter amount">

                <button type="submit" name="submit_repayment" class="btn-primary">Make Repayment</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById("repaymentModal");
        const makeRepaymentBtn = document.getElementById("makeRepaymentBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");

        makeRepaymentBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeModalBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <script src="js/main.js"></script>
</body>
</html>
