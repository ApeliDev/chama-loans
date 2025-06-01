<?php
include('../include/db_connect.php');

// Fetch members with their savings balance, loan balance, and total contributions
$query = "
    SELECT 
        m.member_id, 
        m.full_name, 
        m.email, 
        m.phone_number,
        COALESCE(SUM(s.total_balance), 0) AS savings_balance,
        COALESCE(SUM(l.outstanding_balance), 0) AS loan_balance,
        COALESCE(SUM(c.contribution_amount), 0) AS total_contributions
    FROM members m
    LEFT JOIN savings s ON m.member_id = s.member_id
    LEFT JOIN loans l ON m.member_id = l.member_id
    LEFT JOIN contributions c ON m.member_id = c.member_id
    GROUP BY m.member_id
";

$result = $conn->query($query);

// Check if records exist
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['member_id']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone_number']}</td>
                <td>{$row['savings_balance']}</td>
                <td>{$row['loan_balance']}</td>
                <td>{$row['total_contributions']}</td>
                <td class='action-btns'>
                    <a href='member_details.php?member_id={$row['member_id']}' class='btn btn-view'>View</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No members found</td></tr>";
}

$conn->close();
?>
