<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_POST['message'])) {
    exit('Unauthorized');
}

$user_id = $_SESSION['admin_id'];
$message = mysqli_real_escape_string($conn, $_POST['message']);

$query = "INSERT INTO messages_chat (user_id, message) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "is", $user_id, $message);
mysqli_stmt_execute($stmt);

echo 'success';
?> 