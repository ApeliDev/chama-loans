<?php
include('include/auth.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <div class="content">
                <div class="admin-management-container">
                    <h2>Admin Management</h2>
                    <button id="openModalBtn" class="btn add-btn">Add Admin</button>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('../include/db_connect.php');
                            $query = "SELECT admin_id, username, email, role FROM adminusers";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $index = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$index}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['role']}</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='btn edit-btn'>Edit</button>
                                                <form method='post' action='delete_admin.php' style='display:inline;'>
                                                    <input type='hidden' name='admin_id' value='{$row['admin_id']}'>
                                                    <button type='submit' class='btn delete-btn'>Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>";
                                    $index++;
                                }
                            } else {
                                echo "<tr><td colspan='5'>No admins found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Admin</h3>
                <span id="closeModalBtn" class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form method="post" action="add_admin.php">
                    <input type="text" name="full_name" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="loan_officer">Loan Officer</option>
                        <option value="savings_officer">Savings Officer</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="modal-footer">
                        <button type="submit" class="save-btn">Save</button>
                        <button type="button" class="cancel-btn" id="cancelModalBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Admin Modal -->
        <div id="editAdminModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Admin</h3>
                    <span id="closeEditModalBtn" class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="editAdminForm" method="post" action="edit_admin.php">
                        <input type="hidden" name="admin_id" id="editAdminId">
                        <input type="text" name="username" id="editUsername" placeholder="Username" required>
                        <input type="email" name="email" id="editEmail" placeholder="Email" required>
                        <select name="role" id="editRole" required>
                            <option value="" disabled>Select Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="loan_officer">Loan Officer</option>
                            <option value="savings_officer">Savings Officer</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="modal-footer">
                            <button type="submit" class="save-btn">Save</button>
                            <button type="button" class="cancel-btn" id="cancelEditModalBtn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script src="js/main.css"></script>
    <script>
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const modal = document.getElementById('addAdminModal');

        openModalBtn.addEventListener('click', () => modal.style.display = 'block');
        closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
        cancelModalBtn.addEventListener('click', () => modal.style.display = 'none');

        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
    
</body>
</html>
