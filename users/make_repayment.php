<?php
include('../include/db_connect.php');
include('include/auth.php');
include('maintenance_check.php');
// Check if form is submitted
if (isset($_POST['submit_repayment'])) {
    $member_id = $_SESSION['user_id']; 
    $loan_id = $_POST['loan_id']; 
    $repayment_amount = $_POST['amount'];
    
    // Get current loan details
    $sqlLoan = "SELECT * FROM Loans WHERE loan_id = ? AND member_id = ? AND loan_status = 'active'";
    $stmtLoan = $conn->prepare($sqlLoan);
    $stmtLoan->bind_param("ii", $loan_id, $member_id);
    $stmtLoan->execute();
    $resultLoan = $stmtLoan->get_result();
    
    if ($resultLoan->num_rows > 0) {
        $loan = $resultLoan->fetch_assoc();
        
        // Check if repayment amount is valid
        if ($repayment_amount > 0 && $repayment_amount <= $loan['outstanding_balance']) {
            
            // Calculate the remaining balance after repayment
            $new_balance = $loan['outstanding_balance'] - $repayment_amount;
            
            // Determine loan status based on the new balance
            $loan_status = $new_balance <= 0 ? 'paid' : 'active';
            
            // Insert repayment record into LoanRepayments 
            $repayment_date = date("Y-m-d");
            $status = 'completed'; 
            
            $sqlRepayment = "INSERT INTO LoanRepayments (loan_id, member_id, repayment_amount, repayment_date, remaining_balance, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtRepayment = $conn->prepare($sqlRepayment);
            $stmtRepayment->bind_param("iiisss", $loan_id, $member_id, $repayment_amount, $repayment_date, $new_balance, $status);
            $stmtRepayment->execute();
            
            // Update outstanding balance in the Loans table
            $sqlUpdateLoan = "UPDATE Loans SET outstanding_balance = ?, loan_status = ? WHERE loan_id = ?";
            $stmtUpdateLoan = $conn->prepare($sqlUpdateLoan);
            $stmtUpdateLoan->bind_param("dsi", $new_balance, $loan_status, $loan_id);
            $stmtUpdateLoan->execute();
            
            // Insert the repayment into the transactions table
            $activity_type = 'loan repayment';  
            $transaction_date = date("Y-m-d H:i:s");
            $remarks = "Loan repayment of KSh $repayment_amount for loan ID: $loan_id"; 
            
            // Assuming the new balance is the member's balance after the repayment
            $balance_after = $new_balance;  
            
            // Insert transaction record
            $sqlTransaction = "INSERT INTO transactions (member_id, activity_type, amount, balance_after, transaction_date, remarks, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtTransaction = $conn->prepare($sqlTransaction);
            $stmtTransaction->bind_param("isddsss", $member_id, $activity_type, $repayment_amount, $balance_after, $transaction_date, $remarks, $status);
            $stmtTransaction->execute();
            
            // Check if the transaction insertion was successful
            if ($stmtUpdateLoan->affected_rows > 0 && $stmtTransaction->affected_rows > 0) {
                echo "<script>
                        alert('Loan repayment of KSh $repayment_amount was successful!');
                        window.location.href = 'repay-loan.php';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Error updating the loan balance or adding the transaction.');
                        window.location.href = 'repay-loan.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Repayment amount exceeds outstanding balance.');
                    window.location.href = 'repay-loan.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Loan not found or not active.');
                window.location.href = 'repay-loan.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'repay-loan.php';
          </script>";
    exit();
}
?>
