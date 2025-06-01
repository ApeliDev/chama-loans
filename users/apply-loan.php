<?php
include('include/auth.php');
include('../include/db_connect.php'); 
include('maintenance_check.php');

if (isset($_POST['submit_loan_application'])) {
    // Get form data
    $member_id = $_SESSION['user_id']; 
    $loan_amount = $_POST['loan_amount'];
    $loan_term = $_POST['loan_term'];
    $repayment_plan = $_POST['repayment_plan'];
    $loan_purpose = $_POST['loan_purpose'];
    $interest_rate = 5.00; 

    // Calculate monthly repayment amount
    $monthly_repayment = ($loan_amount * (1 + ($interest_rate / 100))) / $loan_term;

    // Insert into LoanRequests table
    $query = "INSERT INTO LoanRequests (member_id, loan_amount_requested, loan_purpose, interest_rate, loan_term, monthly_repayment, status)
              VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idsdii", $member_id, $loan_amount, $loan_purpose, $interest_rate, $loan_term, $monthly_repayment);

    if ($stmt->execute()) {
        // Get the last inserted loan request ID
        $loan_request_id = $stmt->insert_id;

        // Insert the loan application as a transaction
        $transaction_query = "INSERT INTO transactions (member_id, activity_type, amount, balance_after, transaction_date, status)
                              VALUES (?, 'Loan Applied', ?, ?, NOW(), 'pending')";
        $transaction_stmt = $conn->prepare($transaction_query);
        
        // Assuming balance_after is the current balance of the member before applying the loan
        $balance_query = "SELECT savings_balance FROM members WHERE member_id = ?";
        $balance_stmt = $conn->prepare($balance_query);
        $balance_stmt->bind_param("i", $member_id);
        $balance_stmt->execute();
        $balance_stmt->bind_result($balance_after);
        $balance_stmt->fetch();
        $balance_stmt->close();

        // Insert transaction for loan application
        $transaction_stmt->bind_param("idi", $member_id, $loan_amount, $balance_after);
        if ($transaction_stmt->execute()) {
            echo "<script>
                    alert('Loan application for KSh $loan_amount was submitted successfully! Wait for review.');
                    window.location.href = 'loans.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error recording transaction. Please try again.');
                    window.history.back();
                  </script>";
        }

        // Close the transaction statement
        $transaction_stmt->close();
    } else {
        echo "<script>
                alert('Error submitting loan application. Please try again.');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Loan Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">    
   <link rel="stylesheet" href="css/apply-loans.css">  
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="loan-application-container">
        <h2>Apply for a Loan</h2>
        <p>Please fill in the form below to apply for a loan</p>

       
        <div class="loan-application-form">
            <form id="loan-form" action="apply-loan.php" method="POST">
                <label for="loan_amount">Loan Amount (KSh): </label>
                <input type="number" id="loan_amount" name="loan_amount" required min="1" step="0.01" placeholder="Enter loan amount">

                <label for="loan_term">Loan Term (Months): </label>
                <input type="number" id="loan_term" name="loan_term" required min="1" step="1" placeholder="Enter loan term in months">

                <label for="repayment_plan">Repayment Plan:</label>
                <select id="repayment_plan" name="repayment_plan" required>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="biannual">Biannual</option>
                </select>

                <label for="loan_purpose">Loan Purpose:</label>
                <textarea id="loan_purpose" name="loan_purpose" rows="4" required placeholder="Enter the purpose for the loan"></textarea>

                <button type="submit" name="submit_loan_application">Submit Application</button>
            </form>
        </div>
    </div>
    <script>
     const timeoutDuration = <?php echo json_encode($timeout_duration * 1000); ?>;
    </script>
    <script src="js/main.js"></script>
</body>
</html>
