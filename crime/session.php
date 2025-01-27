<?php
// ✅ session_start() is the FIRST line


// Rest of your code
if (!isset($_SESSION['login_user'])) {
    header("Location: login1.php");
    exit();
}
?>