<?php

include 'config.php';

session_start();

// Check for remember me cookie
if (!isset($_SESSION['login_user']) && isset($_COOKIE['user_login'])) {
    $token = mysqli_real_escape_string($conn, $_COOKIE['user_login']);

    // Check if token exists and is not expired
    $check_token = "SELECT u.user_id, u.username, u.role 
                    FROM remember_me_tokens t 
                    JOIN users u ON t.user_id = u.user_id 
                    WHERE t.token = '$token' 
                    AND t.expires_at > NOW()";

    $result = mysqli_query($conn, $check_token);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['login_user'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        $_SESSION['expire_time'] = 30 * 60;

        // Update online status
        $user_id = $user['user_id'];
        $update_status = "UPDATE users SET is_online = 1, last_activity = NOW() WHERE user_id = '$user_id'";
        mysqli_query($conn, $update_status);
    }
}

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
        // username and password sent from form 
        $myusername = mysqli_real_escape_string($conn, $_POST['uname']);
        $mypassword = mysqli_real_escape_string($conn, $_POST['pass']);

        $sql = "SELECT user_id, username, role FROM users WHERE username = '$myusername' AND password = '$mypassword'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            // Set user as online and update last activity
            $user_id = $row['user_id'];
            $update_status = "UPDATE users SET is_online = 1, last_activity = NOW() WHERE user_id = '$user_id'";
            mysqli_query($conn, $update_status);

            $_SESSION['login_user'] = $myusername;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $row['role'];

            // Handle Remember Me
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                // Generate a secure random token
                $token = bin2hex(random_bytes(32));

                // Set expiration date (30 days from now)
                $expires = date('Y-m-d H:i:s', time() + (86400 * 5));

                // Store token in database
                $store_token = "INSERT INTO remember_me_tokens (user_id, token, expires_at) 
                               VALUES ('$user_id', '$token', '$expires')";
                mysqli_query($conn, $store_token);

                // Set cookie with the token
                setcookie("user_login", $token, time() + (86400 * 30), "/");
            }

            // Set session timeout to 30 minutes
            $_SESSION['last_activity'] = time();
            $_SESSION['expire_time'] = 30 * 60; // 30 minutes in seconds

            switch ($row['role']) {
                case 'Crime Management':
                    header("location: home.php");
                    break;
                case 'Police Management':
                    header("location: login1.php");
                    break;
                case 'Admin':
                    header("location: login1.php");
                    break;
                default:
                    header("location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid Username or Password')</script>";
        }
    }
}

// Check if session is expired
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $_SESSION['expire_time']) {
    // Set user as offline
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $update_status = "UPDATE users SET is_online = 0 WHERE user_id = '$user_id'";
        mysqli_query($conn, $update_status);
    }

    // Set all users with expired sessions to offline
    $timeout = time() - (30 * 60); // 30 minutes ago
    $update_all_expired = "UPDATE users SET is_online = 0 
                          WHERE last_activity < NOW() - INTERVAL 30 MINUTE";
    mysqli_query($conn, $update_all_expired);

    // Destroy the session
    session_unset();
    session_destroy();
    header("location: index.php");
    exit();
}

// Update last activity time if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $update_activity = "UPDATE users SET last_activity = NOW() WHERE user_id = '$user_id'";
    mysqli_query($conn, $update_activity);
    $_SESSION['last_activity'] = time();
}

// Add this to your logout logic to remove the token

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Department Login</title>
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

        .btn-primary {
            background: var(--police-blue);
            color: white;
        }

        .btn-primary:hover {
            background: #234aa8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
        }

        .back-link:hover {
            color: var(--police-gold);
            transform: translateX(-5px);
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="badge-police">Secure Login</div>
                        <div class="text-center mb-4">
                            <h1 class="system-title">Criminal Records System</h1>
                            <p class="text-muted">Please enter your credentials</p>
                        </div>
                        <form method="post">
                            <div class="form-group mb-3">
                                <label for="uname" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user" style="color: var(--police-gold);"></i>
                                    </span>
                                    <input type="text" class="form-control" id="uname" name="uname" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="pass" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock" style="color: var(--police-gold);"></i>
                                    </span>
                                    <input type="password" class="form-control" id="pass" name="pass" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                    <label class="form-check-label" for="remember_me">Remember Me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>