<?php
// Start the session to store messages
session_start();

// Include the database configuration file
include 'config.php';

// Check if the 'id' parameter is set in the URL
if (!isset($_GET['id'])) {
    // Redirect to the officer records page if 'id' is missing
    $_SESSION['error_message'] = "Invalid request. Officer ID is missing.";
    header("Location: ofc_record.php");
    exit();
}

// Sanitize the officer ID to prevent SQL injection
$officer_id = intval($_GET['id']); // Ensure the ID is an integer

// Prepare the delete query
$query = "DELETE FROM officer_record WHERE NGO_id = ?";

// Use prepared statements to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $officer_id);

// Execute the query
if (mysqli_stmt_execute($stmt)) {
    // Check if any row was affected
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['success_message'] = "Officer successfully deleted.";
    } else {
        $_SESSION['error_message'] = "No officer found with the provided ID.";
    }
} else {
    $_SESSION['error_message'] = "Error deleting record: " . mysqli_error($conn);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect to the officer records page
header("Location: ofc_record.php");
exit();
?>