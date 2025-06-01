<?php
include('../include/db_connect.php');

if (isset($_GET['member_id'])) {
    $member_id = intval($_GET['member_id']);

    // Fetch member details
    $query_member = "SELECT * FROM members WHERE member_id = ?";
    $stmt_member = $conn->prepare($query_member);
    $stmt_member->bind_param("i", $member_id);
    $stmt_member->execute();
    $result_member = $stmt_member->get_result();

    if ($result_member->num_rows > 0) {
        $member = $result_member->fetch_assoc();

        // Fetch total contributions for the member
        $query_contributions = "SELECT COALESCE(SUM(contribution_amount), 0) AS total_contributions FROM contributions WHERE member_id = ?";
        $stmt_contributions = $conn->prepare($query_contributions);
        $stmt_contributions->bind_param("i", $member_id);
        $stmt_contributions->execute();
        $result_contributions = $stmt_contributions->get_result();
        $contributions = $result_contributions->fetch_assoc();
        $total_contributions = $contributions['total_contributions'];

        // Fetch total loan balance for the member
        $query_loans = "SELECT COALESCE(SUM(outstanding_balance), 0) AS total_loan_balance FROM loans WHERE member_id = ?";
        $stmt_loans = $conn->prepare($query_loans);
        $stmt_loans->bind_param("i", $member_id);
        $stmt_loans->execute();
        $result_loans = $stmt_loans->get_result();
        $loans = $result_loans->fetch_assoc();
        $total_loan_balance = $loans['total_loan_balance'];

        // Fetch savings balance for the member
        $query_savings = "SELECT COALESCE(SUM(total_balance), 0) AS savings_balance FROM savings WHERE member_id = ?";
        $stmt_savings = $conn->prepare($query_savings);
        $stmt_savings->bind_param("i", $member_id);
        $stmt_savings->execute();
        $result_savings = $stmt_savings->get_result();
        $savings = $result_savings->fetch_assoc();
        $savings_balance = $savings['savings_balance'];

        // Fetch transaction history for the member
        $query_transactions = "SELECT * FROM transactions WHERE member_id = ? ORDER BY transaction_date DESC";
        $stmt_transactions = $conn->prepare($query_transactions);
        $stmt_transactions->bind_param("i", $member_id);
        $stmt_transactions->execute();
        $result_transactions = $stmt_transactions->get_result();
        $transactions = [];
        while ($transaction = $result_transactions->fetch_assoc()) {
            $transactions[] = $transaction;
        }

        // Return all data as JSON
        echo json_encode([
            'status' => 'success',
            'data' => [
                'full_name' => $member['full_name'],
                'email' => $member['email'],
                'phone_number' => $member['phone_number'],
                'national_id' => $member['national_id'],
                'savings_balance' => $savings_balance,
                'loan_balance' => $total_loan_balance,
                'total_contributions' => $total_contributions,
                'created_at' => $member['created_at'],
                'transactions' => $transactions
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Member not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Member ID not provided.']);
}

$conn->close();
?>
