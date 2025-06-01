<?php
include('include/auth.php');
include('../include/db_connect.php'); 
include('maintenance_check.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_deposit'])) {
    $member_id = $_SESSION['user_id'];
    $deposit_amount = $_POST['deposit_amount'];

    if ($deposit_amount > 0) {
        // update the existing total_balance
        $sql = "INSERT INTO Savings (member_id, saving_amount, total_balance, saving_status)
                VALUES (?, ?, ?, 'active') 
                ON DUPLICATE KEY UPDATE total_balance = total_balance + VALUES(saving_amount)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idd", $member_id, $deposit_amount, $deposit_amount); 

        if ($stmt->execute()) {
            // Get the sum of all savings for the member after the deposit
            $sqlBalance = "SELECT SUM(saving_amount) AS total_balance FROM Savings WHERE member_id = ?";
            $stmtBalance = $conn->prepare($sqlBalance);
            $stmtBalance->bind_param("i", $member_id);
            $stmtBalance->execute();
            $resultBalance = $stmtBalance->get_result();
            $balance = $resultBalance->fetch_assoc()['total_balance'];

            // Insert the deposit transaction into the transactions table
            $activity_type = 'Savings deposit'; 
            $transaction_date = date("Y-m-d H:i:s");
            $remarks = "Deposit of KSh $deposit_amount"; 
            $status = 'completed';

            // Insert transaction record into transactions table
            $sqlTransaction = "INSERT INTO transactions (member_id, activity_type, amount, balance_after, transaction_date, remarks, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtTransaction = $conn->prepare($sqlTransaction);
            $stmtTransaction->bind_param("isddsss", $member_id, $activity_type, $deposit_amount, $balance, $transaction_date, $remarks, $status);
            $stmtTransaction->execute();

            // Check if the transaction insertion was successful
            if ($stmtTransaction->affected_rows > 0) {
                echo "<script>
                        alert('Deposit of KSh $deposit_amount was successful.');
                        window.location.href = 'saving.php';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Error adding the deposit transaction.');
                        window.history.back();
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('An error occurred while processing the deposit of KSh $deposit_amount. Please try again.');
                    window.history.back();
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Invalid deposit amount. Please enter a valid amount.');
                window.history.back();
              </script>";
        exit();
    }
}
?>
