<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crime_id = mysqli_real_escape_string($conn, $_POST['crime_id']);
    $user_id = $_SESSION['user_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO messages (crime_id, user_id, message) VALUES ('$crime_id', '$user_id', '$message')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>