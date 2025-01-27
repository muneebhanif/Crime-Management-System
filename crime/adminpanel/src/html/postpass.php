<?php
// Add error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $myusername = mysqli_real_escape_string($conn, $_POST['uname']);
   $mypassword = mysqli_real_escape_string($conn, $_POST['pass']);

   // Let's first check if the user exists
   $sql = "SELECT * FROM users WHERE username = '$myusername' AND password = '$mypassword'";
   $result = mysqli_query($conn, $sql);

   if (!$result) {
      echo "Query failed: " . mysqli_error($conn);
      exit();
   }

   $row = mysqli_fetch_array($result);
   $count = mysqli_num_rows($result);

   if ($count == 1) {
      // Debug output
      echo "Login successful! User found.";

      $_SESSION['logged_in'] = true;
      $_SESSION['admin_id'] = $row['user_id'];
      $_SESSION['username'] = $myusername;
      $_SESSION['role'] = $row['role'];
      $_SESSION['last_activity'] = time();

      // Debug output
      echo "Session variables set. Role: " . $_SESSION['role'];

      // Clear any existing output
      ob_clean();

      // Use switch statement for role-based routing
      switch ($row['role']) {
         case 'Police Management':
            header("Location: index.php");
            break;
         case 'Admin':
            header("Location: ./index.php");
            break;
         default:
            echo "<script>alert('Access Denied: Admin privileges required');</script>";
            session_destroy();
      }
      exit();
   } else {
      echo "<script>alert('Invalid Username or Password');</script>";
   }
}

// Remove the automatic redirect for debugging
// Comment out or remove the following block temporarily
/*
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $session_timeout = 30 * 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        if (basename($_SERVER['PHP_SELF']) === 'login.php') {
            header("Location: index.php");
            exit();
        }
    }
}
*/
?>