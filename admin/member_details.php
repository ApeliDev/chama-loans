<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .member-details-table, .transaction-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .member-details-table thead, .transaction-history-table thead {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .member-details-table th, .member-details-table td, 
        .transaction-history-table th, .transaction-history-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .member-details-table th, .transaction-history-table th {
            font-size: 16px;
            text-transform: uppercase;
        }

        .member-details-table tbody tr:hover, .transaction-history-table tbody tr:hover {
            background-color: #f0f2f5;
        }

        .member-details-container {
            margin: 20px auto;
            padding: 20px;
        }

        h2, h3 {
            color: #34495e;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <div class="content">
                <div class="member-details-container">
                    <h2>Member Details</h2>
                    <table class="member-details-table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody id="member-details">
                            <tr>
                                <td colspan="2">Loading details...</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3>Transaction History</h3>
                    <table class="transaction-history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-history">
                            <tr>
                                <td colspan="5">Loading transactions...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const memberId = new URLSearchParams(window.location.search).get('member_id');

        if (memberId) {
            // Fetch member details
            fetch(`fetch_member_details.php?member_id=${memberId}`)
                .then(response => response.json())
                .then(data => {
                    const detailsContainer = document.getElementById('member-details');
                    if (data.status === 'success') {
                        const member = data.data;
                        detailsContainer.innerHTML = `
                            <tr><td>Full Name</td><td>${member.full_name}</td></tr>
                            <tr><td>Email</td><td>${member.email}</td></tr>
                            <tr><td>Phone Number</td><td>${member.phone_number}</td></tr>
                            <tr><td>National ID</td><td>${member.national_id}</td></tr>
                            <tr><td>Savings Balance</td><td>${member.savings_balance}</td></tr>
                            <tr><td>Loan Balance</td><td>${member.loan_balance}</td></tr>
                            <tr><td>Total Contributions</td><td>${member.total_contributions}</td></tr>
                            <tr><td>Created At</td><td>${member.created_at}</td></tr>
                        `;

                        // Display transaction history
                        const transactionHistoryContainer = document.getElementById('transaction-history');
                        if (member.transactions.length > 0) {
                            transactionHistoryContainer.innerHTML = member.transactions.map((transaction, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${transaction.transaction_date}</td>
                                    <td>${transaction.activity_type}</td>
                                    <td>${transaction.amount}</td>
                                    <td>${transaction.balance_after}</td>
                                </tr>
                            `).join('');
                        } else {
                            transactionHistoryContainer.innerHTML = `<tr><td colspan="5">No transactions available.</td></tr>`;
                        }
                    } else {
                        detailsContainer.innerHTML = `<tr><td colspan="2">${data.message}</td></tr>`;
                    }
                })
                .catch(() => {
                    document.getElementById('member-details').innerHTML = `<tr><td colspan="2">Error loading details.</td></tr>`;
                    document.getElementById('transaction-history').innerHTML = `<tr><td colspan="5">Error loading transactions.</td></tr>`;
                });
        } else {
            document.getElementById('member-details').innerHTML = `<tr><td colspan="2">Invalid member ID.</td></tr>`;
        }

    </script>
</body>
</html>
