<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
session_start();

class Validator
{
   public function validate($params, $validation_rules)
   {
      try {
         $response = '';

         foreach ($validation_rules as $field => $rules) {
            foreach (explode('|', $rules) as $rule) {
               if ($rule == 'required' && array_key_exists($field, $params) == false) {
                  $response .= "The " . $field . " is required.\n";
               }

               if (array_key_exists($field, $params) == true) {
                  if ($rule == 'alphanumeric' && preg_match('/^[a-z0-9 .\-]+$/i', $params[$field]) == false) {
                     $response .= "The value of " . $field . " is not a valid alphanumeric value.\n";
                  }

                  if ($rule == 'phone' && preg_match('/^[0-9 \-\(\)\+]+$/i', $params[$field]) == false) {
                     $response .= "The value of " . $field . " is not a valid phone number.\n";
                  }

                  if ($rule == 'email' && filter_var($params[$field], FILTER_VALIDATE_EMAIL) == false) {
                     $response .= "The value of " . $field . " is not a valid email value.\n";
                  }
               }
            }
         }

         return $response;

      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validator = new Validator();
   $validation_rules = [
      'uname' => 'required|alphanumeric',
      'pass' => 'required'
   ];

   $error = $validator->validate($_POST, $validation_rules);

   if ($error != '') {
      echo "<script>alert('Validation failed: " . str_replace("\n", "\\n", $error) . "')</script>";
   } else {
      $myusername = mysqli_real_escape_string($conn, $_POST['uname']);
      $mypassword = mysqli_real_escape_string($conn, $_POST['pass']);

      $sql = "SELECT * FROM users WHERE username = '$myusername' AND password = '$mypassword'";
      $result = mysqli_query($conn, $sql);

      if (!$result) {
         echo "Query failed: " . mysqli_error($conn);
         exit();
      }

      $row = mysqli_fetch_array($result);
      $count = mysqli_num_rows($result);

      if ($count == 1) {
         $_SESSION['logged_in'] = true;
         $_SESSION['admin_id'] = $row['user_id'];
         $_SESSION['username'] = $myusername;
         $_SESSION['role'] = $row['role'];
         $_SESSION['last_activity'] = time();

         switch ($row['role']) {
            case 'Police Management':
               header("Location: ../../../../index.php");
               break;
            case 'Admin':
               header("Location: ../../../../index.php");
               break;
            default:
               echo "<script>alert('Access Denied: Admin privileges required');</script>";
               session_destroy();
         }
         exit();
      } else {
         echo "<script>alert('Invalid Username or Password')</script>";
      }
   }
}

// Comment out the automatic redirect temporarily

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
?>

<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset='utf-8'>
   <meta http-equiv='X-UA-Compatible' content='IE=edge'>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrator Login</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      :root {
         --police-blue: #1a3a85;
         --police-gold: #c4a777;
      }

      body {
         background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ef 100%);
      }

      .card {
         border: none;
         border-radius: 15px;
         background: rgba(255, 255, 255, 0.95);
      }

      .system-title {
         color: var(--police-blue);
         font-weight: 600;
         font-size: 2rem;
         margin-bottom: 1rem;
      }

      .form-label {
         color: var(--police-blue);
         font-weight: 500;
      }

      .form-control {
         padding: 0.8rem 1rem;
         border: 1px solid #e1e5ea;
         border-radius: 8px;
         transition: all 0.3s ease;
      }

      .form-control:focus {
         border-color: var(--police-blue);
         box-shadow: 0 0 0 0.2rem rgba(26, 58, 133, 0.1);
      }

      .btn {
         padding: 0.8rem 1.5rem;
         font-weight: 500;
         transition: all 0.3s ease;
         border: none;
      }

      .btn-dark {
         background: #1a1a1a;
         color: var(--police-gold);
      }

      .btn-dark:hover {
         background: #2c2c2c;
         transform: translateY(-2px);
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
         color: var(--police-gold);
      }

      .badge-police {
         position: absolute;
         top: -15px;
         left: 50%;
         transform: translateX(-50%);
         background: var(--police-gold);
         color: var(--police-blue);
         padding: 0.5rem 1rem;
         border-radius: 20px;
         font-weight: 600;
         box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      }

      .back-link {
         position: absolute;
         top: 20px;
         left: 20px;
         color: var(--police-blue);
         text-decoration: none;
         display: flex;
         align-items: center;
         gap: 5px;
         font-weight: 500;
         transition: all 0.3s ease;
         z-index: 1000;
      }

      .back-link:hover {
         color: var(--police-gold);
         transform: translateX(-5px);
      }

      .security-notice {
         font-size: 0.8rem;
         color: #dc3545;
         margin-top: 1rem;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 5px;
      }
   </style>
</head>

<body>
   <div class="container">
      <a href="../../../../crime/index.php" class="back-link">
         <i class="fas fa-arrow-left"></i> Back to Home / واپس ہوم پیج پر
      </a>
      <div class="row justify-content-center min-vh-100 align-items-center">
         <div class="col-md-5">
            <div class="card shadow-lg">
               <div class="card-body p-5">
                  <div class="badge-police">Restricted Access</div>
                  <div class="text-center mb-4">
                     <h1 class="system-title">Administrator Login</h1>
                     <p class="text-muted">System Administration Portal</p>
                  </div>
                  <form method="post" action="postpass.php">
                     <div class="form-group mb-3">
                        <label for="uname" class="form-label">Admin Username</label>
                        <div class="input-group">
                           <span class="input-group-text">
                              <i class="fas fa-user-cog" style="color: var(--police-gold);"></i>
                           </span>
                           <input type="text" class="form-control" id="uname" name="uname" required>
                        </div>
                     </div>
                     <div class="form-group mb-4">
                        <label for="pass" class="form-label">Admin Password</label>
                        <div class="input-group">
                           <span class="input-group-text">
                              <i class="fas fa-key" style="color: var(--police-gold);"></i>
                           </span>
                           <input type="password" class="form-control" id="pass" name="pass" required>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-dark w-100">
                        <i class="fas fa-shield-alt me-2"></i> Access Admin Panel
                     </button>
                     <div class="security-notice">
                        <i class="fas fa-exclamation-triangle"></i>
                        Authorized Personnel Only - All actions are logged
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>