<?php
session_start();
include 'session.php';
include 'config.php';

if (isset($_GET['id'])) {
    $crime_id = mysqli_real_escape_string($conn, $_GET['id']);

    // First get the image path to delete the image file
    $query = "SELECT img FROM crime_register WHERE Crime_Id = '$crime_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Delete the image file if it exists
    if ($row && $row['img'] && file_exists($row['img'])) {
        unlink($row['img']);
    }

    // Delete the record
    $delete_query = "DELETE FROM crime_register WHERE Crime_Id = '$crime_id'";

    if (mysqli_query($conn, $delete_query)) {
        header("Location: search.php?msg=deleted");
    } else {
        header("Location: search.php?msg=error");
    }
} else {
    header("Location: search.php");
}
exit();
?>