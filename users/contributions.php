<?php 
include('include/auth.php');
include('../include/db_connect.php');
include('maintenance_check.php');
$member_id = $_SESSION['user_id'];

// Calculate total contributions
$totalContributions = 0;
$sqlTotal = "SELECT SUM(contribution_amount) AS total_contributions FROM Contributions WHERE member_id = ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("i", $member_id);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
if ($row = $resultTotal->fetch_assoc()) {
    $totalContributions = $row['total_contributions'];
}

// contribution history
$contributionsHistory = [];
$sqlHistory = "SELECT created_at, contribution_amount FROM Contributions WHERE member_id = ? ORDER BY created_at DESC";
$stmtHistory = $conn->prepare($sqlHistory);
$stmtHistory->bind_param("i", $member_id);
$stmtHistory->execute();
$resultHistory = $stmtHistory->get_result();
while ($row = $resultHistory->fetch_assoc()) {
    $contributionsHistory[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Contributions</title>
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
                <h3>Your Total Contributions</h3>
                <p>Total Contribution: KSh <?php echo number_format($totalContributions, 2); ?></p>
            </div>
            <div class="card">
                <h3>Make a Contribution</h3>
                <button id="makeContributionBtn" class="btn-primary">Make Contribution</button>
            </div>
            <div class="card">
                <h3>Your Contribution History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount (KSh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contributionsHistory as $contribution): ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i", strtotime($contribution['created_at'])); ?></td>
                            <td>KSh <?php echo number_format($contribution['contribution_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="contributionModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h3>Make a Contribution</h3>
            <form action="make_contribution.php" method="POST">
                <label for="amount">Amount (KSh): </label>
                <input type="number" id="amount" name="amount" required min="1" step="0.01" placeholder="Enter amount">
                <button type="submit" name="submit_contribution" class="btn-primary">Make Contribution</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById("contributionModal");
        const makeContributionBtn = document.getElementById("makeContributionBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");

        // Open the modal
        makeContributionBtn.onclick = function() {
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
