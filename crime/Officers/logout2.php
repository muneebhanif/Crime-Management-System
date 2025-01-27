<?php
include('config.php');
session_start();

// Update user status to offline in database
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   $update_status = "UPDATE users SET is_online = 0, last_activity = NOW() WHERE user_id = '$user_id'";
   mysqli_query($conn, $update_status);
}

// Also update any users with expired sessions
$update_all_expired = "UPDATE users SET is_online = 0 
                      WHERE last_activity < NOW() - INTERVAL 30 MINUTE";
mysqli_query($conn, $update_all_expired);

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: login2.php");
exit();
?>