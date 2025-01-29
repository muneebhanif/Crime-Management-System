<?php
require_once 'config.php';
session_start();

date_default_timezone_set('Asia/Karachi'); // Set to Islamabad, Pakistan

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $user_id = $_SESSION['admin_id'];

    $insert_query = "INSERT INTO messages_chat (user_id, messages, message_time) VALUES ('$user_id', '$message', NOW())";
    mysqli_query($conn, $insert_query);

    // Redirect to the same page to clear POST data and prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get users with their status for both roles
$query = "SELECT user_id as admin_id, username, role, last_activity, is_online,
    CASE 
        WHEN is_online = 1 THEN 'Online'
        ELSE 'Offline'
    END as status 
    FROM users 
    WHERE role IN ('Crime Management', 'Police Management')
    ORDER BY is_online DESC, last_activity DESC";  // Show online users first, then sort by last activity
$result = mysqli_query($conn, $query);

// Fetch messages with usernames
$messages_query = "
    SELECT 
        mc.*, 
        u.username 
    FROM 
        messages_chat mc 
    JOIN 
        users u ON mc.user_id = u.user_id 
    ORDER BY 
        mc.message_time ASC
";
$messages_result = mysqli_query($conn, $messages_query);
$messages = [];
if ($messages_result) {
    while ($row = mysqli_fetch_assoc($messages_result)) {
        $messages[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Active Users - Admin Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <style>
        .status-indicator {
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 500;
        }

        .status-indicator.online {
            background-color: #dcf7dc;
            color: #28a745;
        }

        .status-indicator.offline {
            background-color: #ffe0e0;
            color: #dc3545;
        }

        .message {
            max-width: 60%;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 10px;
            position: relative;
            word-wrap: break-word;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .message.mine {
            margin-left: auto;
            background-color: #d1e7dd;
            border-radius: 10px 0 10px 10px;
            text-align: right;
        }

        .message.theirs {
            margin-right: auto;
            background-color: #ffffff;
            border-radius: 0 10px 10px 10px;
            text-align: left;
        }

        .username {
            font-size: 0.85em;
            color: #0d6efd;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .message-text {
            font-size: 1em;
            line-height: 1.5;
        }

        .message-time {
            font-size: 0.75em;
            color: #6c757d;
            position: absolute;
            bottom: 5px;
            right: 10px;
        }
    </style>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        <?php
        include 'sidebar.php';
        ?>
        <!--  Sidebar End -->

        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                                        class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="./logout.php"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Active Admin Users</h5>
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Username</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Role</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Last Activity</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">
                                                    <?php echo htmlspecialchars($row['username']); ?>
                                                </h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">
                                                    <?php echo htmlspecialchars($row['role']); ?>
                                                </h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="status-indicator <?php echo strtolower($row['status']); ?>">
                                                    <?php echo $row['status']; ?>
                                                </span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    <?php echo date('Y-m-d H:i:s', strtotime($row['last_activity'])); ?>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Chat Box Card -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Admin Chat</h5>
                        <div class="chat-messages p-4" id="chatMessages"
                            style="height: 350px; overflow-y: auto; background-color: #e9ecef; border-radius: 10px;">
                            <?php foreach ($messages as $message) { ?>
                                <div
                                    class="message <?php echo ($message['user_id'] == $_SESSION['admin_id']) ? 'mine' : 'theirs'; ?> mb-3">
                                    <?php if ($message['user_id'] != $_SESSION['admin_id']) { ?>
                                        <div class="username"><?php echo htmlspecialchars($message['username']); ?></div>
                                    <?php } ?>
                                    <div class="message-text"><?php echo htmlspecialchars($message['messages']); ?></div>
                                    <div class="message-time">
                                        <?php
                                        $msg_time = strtotime($message['message_time']);
                                        $today = strtotime('today');

                                        if ($msg_time >= $today) {
                                            echo date('h:i A', $msg_time);
                                        } else if ($msg_time >= strtotime('-1 day')) {
                                            echo 'Yesterday ' . date('h:i A', $msg_time);
                                        } else {
                                            echo date('M d, h:i A', $msg_time);
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <form method="POST" class="mt-3" id="chatForm">
                            <div class="input-group">
                                <input type="text" class="form-control" name="message" id="messageInput"
                                    placeholder="Type your message..." required>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };
    </script>
</body>

</html>