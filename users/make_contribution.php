<?php
include('include/auth.php');
include('../include/db_connect.php');
include('maintenance_check.php');
if (isset($_POST['submit_contribution'])) {
    $amount = $_POST['amount'];
    $member_id = $_SESSION['user_id'];

    // Insert the contribution into the Contributions table
    $sql = "INSERT INTO Contributions (member_id, contribution_amount) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $member_id, $amount);

    if ($stmt->execute()) {
        
        // Fetch the current total contribution amount (if needed for any update)
        $sqlContribution = "SELECT SUM(contribution_amount) AS total_contribution FROM Contributions WHERE member_id = ?";
        $stmtContribution = $conn->prepare($sqlContribution);
        $stmtContribution->bind_param("i", $member_id);
        $stmtContribution->execute();
        $resultContribution = $stmtContribution->get_result();
        $total_contribution = $resultContribution->fetch_assoc()['total_contribution'];

        // Log the transaction in the transactions table
        $activity_type = 'contribution';  
        $transaction_date = date("Y-m-d H:i:s");
        $remarks = "Contribution of KSh $amount"; 
        $status = 'completed';  

        // Insert transaction record into transactions table
        $sqlTransaction = "INSERT INTO transactions (member_id, activity_type, amount, balance_after, transaction_date, remarks, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtTransaction = $conn->prepare($sqlTransaction);
        $stmtTransaction->bind_param("isddsss", $member_id, $activity_type, $amount, $total_contribution, $transaction_date, $remarks, $status);
        $stmtTransaction->execute();

        // Check if the transaction insertion was successful
        if ($stmtTransaction->affected_rows > 0) {
            echo "<script>
                    alert('Contribution of KSh $amount added successfully!');
                    window.location.href='contributions.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error adding the contribution transaction.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Failed to add contribution of KSh $amount. Please try again.');
                window.history.back();
              </script>";
    }
}
?>
