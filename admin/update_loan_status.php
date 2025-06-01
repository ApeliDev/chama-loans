<?php
include('../include/db_connect.php');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $loan_id = isset($_POST['loan_id']) ? (int)$_POST['loan_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Check if loan request exists
    $query = "SELECT * FROM LoanRequests WHERE request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $loan_request = $result->fetch_assoc();

    if (!$loan_request) {
        echo "Loan request not found.";
        exit;
    }

    if ($action === 'approve') {
        // Approve loan
        $member_id = $loan_request['member_id'];
        $loan_amount = $loan_request['loan_amount_requested'];
        $loan_term = $loan_request['loan_term'];
        $interest_rate = $loan_request['interest_rate'];
        $monthly_repayment = $loan_request['monthly_repayment'];

        // Insert into Loans table
        $insertLoanQuery = "
            INSERT INTO Loans (member_id, loan_amount, loan_term, interest_rate, monthly_repayment, outstanding_balance, loan_status)
            VALUES (?, ?, ?, ?, ?, ?, 'active')";
        $stmtInsert = $conn->prepare($insertLoanQuery);
        $stmtInsert->bind_param("idddds", $member_id, $loan_amount, $loan_term, $interest_rate, $monthly_repayment, $loan_amount);

        if ($stmtInsert->execute()) {
            // Update LoanRequests table
            $updateQuery = "UPDATE LoanRequests SET status = 'approved', approved_at = NOW() WHERE request_id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("i", $loan_id);
            $stmtUpdate->execute();
            echo "Loan approved successfully.";
        } else {
            echo "Failed to approve loan.";
        }
    } elseif ($action === 'reject') {
        // Reject loan
        $updateQuery = "UPDATE LoanRequests SET status = 'rejected', approved_at = NOW() WHERE request_id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("i", $loan_id);
        if ($stmtUpdate->execute()) {
            echo "Loan rejected successfully.";
        } else {
            echo "Failed to reject loan.";
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid request method.";
}

// Close connection
$conn->close();
?>
