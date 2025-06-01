<?php
include('include/db_connect.php');

// joining with the 'members' table to get the full name, email, and phone number
$loan_requests_query = "
    SELECT LoanRequests.request_id, members.full_name, members.email, members.phone_number, 
           LoanRequests.loan_amount_requested, LoanRequests.status, LoanRequests.requested_at 
    FROM LoanRequests 
    JOIN members ON LoanRequests.member_id = members.member_id
    WHERE LoanRequests.status IN ('pending', 'rejected') 
    ORDER BY LoanRequests.requested_at DESC
";
$loan_requests_result = $conn->query($loan_requests_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $request_id = $_POST['approve'];
        
        // Retrieve loan request details before approving
        $query = "SELECT * FROM LoanRequests WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $loan_request = $result->fetch_assoc();

        if ($loan_request) {
            // Extract loan request details
            $member_id = $loan_request['member_id'];
            $loan_amount = $loan_request['loan_amount_requested'];
            $loan_term = $loan_request['loan_term'];
            $monthly_repayment = $loan_request['monthly_repayment'];
            $interest_rate = $loan_request['interest_rate'];
            $loan_purpose = $loan_request['loan_purpose'];

            // Insert the approved loan into the Loans table
            $insertLoanQuery = "INSERT INTO Loans (member_id, loan_amount, loan_term, interest_rate, monthly_repayment, outstanding_balance, loan_status)
                                VALUES (?, ?, ?, ?, ?, ?, 'active')";
            $stmtInsertLoan = $conn->prepare($insertLoanQuery);
            $stmtInsertLoan->bind_param("idddds", $member_id, $loan_amount, $loan_term, $interest_rate, $monthly_repayment, $loan_amount);

            if ($stmtInsertLoan->execute()) {
                // If loan inserted, update loan request status to 'approved'
                $update_query = "UPDATE LoanRequests SET status = 'approved', approved_at = NOW() WHERE request_id = ?";
                $stmtUpdateRequest = $conn->prepare($update_query);
                $stmtUpdateRequest->bind_param("i", $request_id);
                $stmtUpdateRequest->execute();

                header('Location: admin-test.php');
                exit();
            } else {
                echo "Error approving loan.";
            }
        }

    } elseif (isset($_POST['decline'])) {
        $request_id = $_POST['decline'];
        
        // Update loan request status to 'rejected'
        $update_query = "UPDATE LoanRequests SET status = 'rejected' WHERE request_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();

        header('Location: admin-test.php');
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management - Test</title>
    <style>
        .loan-request {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .loan-request h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .loan-request p {
            font-size: 1.2em;
        }
        .loan-request button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .loan-request button.decline {
            background-color: #dc3545;
        }
        .loan-request table {
            width: 100%;
            border-collapse: collapse;
        }
        .loan-request th, .loan-request td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <h2>Loan Management </h2>
    <p>Approve or Decline Loan Requests</p>
    <div class="loan-request">
        <h3>Pending Loan Requests</h3>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Loan Amount</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($loan_requests_result->num_rows > 0): ?>
                    <?php while ($loan = $loan_requests_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($loan['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($loan['email']); ?></td>
                            <td><?php echo htmlspecialchars($loan['phone_number']); ?></td>
                            <td>KSh <?php echo number_format($loan['loan_amount_requested'], 2); ?></td>
                            <td><?php echo ucfirst($loan['status']); ?></td>
                            <td><?php echo date("Y-m-d", strtotime($loan['requested_at'])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <button type="submit" name="approve" value="<?php echo $loan['request_id']; ?>">Approve</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <button type="submit" name="decline" value="<?php echo $loan['request_id']; ?>" class="decline">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No pending loan requests.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
