<?php
include('../include/db_connect.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['admin_id']) && is_numeric($_POST['admin_id'])) {
        $admin_id = $_POST['admin_id'];

        // Prepare the SQL query to delete the record
        $stmt = $conn->prepare("DELETE FROM adminusers WHERE admin_id = ?");
        $stmt->bind_param("i", $admin_id);

        // Execute the query and check if the deletion was successful
        if ($stmt->execute()) {
            // Redirect with a success message
            header("Location: admin.php?status=success&message=Admin deleted successfully.");
        } else {
            // Redirect with an error message
            header("Location: admin.php?status=error&message=Failed to delete admin.");
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Redirect if admin_id is not valid
        header("Location: admin_list.php?status=error&message=Invalid admin ID.");
    }
} else {
    // Redirect if accessed directly
    header("Location: admin.php?status=error&message=Invalid request.");
}

// Close the database connection
$conn->close();
