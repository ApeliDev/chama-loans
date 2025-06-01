<?php
include('../include/db_connect.php');

// Fetch pending loan applications
$sql = "SELECT lr.request_id, lr.loan_amount_requested, lr.loan_purpose, lr.requested_at, lr.status, m.full_name 
        FROM loanrequests lr 
        JOIN members m ON lr.member_id = m.member_id 
        WHERE lr.status = 'pending'";
$result = $conn->query($sql);

// Display applications as HTML
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
            <td>{$row['request_id']}</td>
            <td>{$row['full_name']}</td>
            <td>{$row['loan_amount_requested']}</td>
            <td>{$row['loan_purpose']}</td>
            <td>{$row['requested_at']}</td>
            <td>{$row['status']}</td>
            <td>
                <div class='action-buttons'>
                    <form method='post' action='update_loan_status.php' style='display:inline;'>
                        <input type='hidden' name='loan_id' value='{$row['request_id']}'>
                        <input type='hidden' name='action' value='approve'>
                        <button type='submit' class='approve-btn'>Approve</button>
                    </form>
                    <form method='post' action='update_loan_status.php' style='display:inline;'>
                        <input type='hidden' name='loan_id' value='{$row['request_id']}'>
                        <input type='hidden' name='action' value='reject'>
                        <button type='submit' class='reject-btn'>Reject</button>
                    </form>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No applications found.</td></tr>";
}
?>
