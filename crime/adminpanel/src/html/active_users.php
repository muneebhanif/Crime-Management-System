<?php
require_once 'config.php';
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
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

// Update messages query to join with users table without using id column
$messages_query = "SELECT mc.*, u.username 
                  FROM messages_chat mc 
                  JOIN users u ON mc.user_id = u.user_id 
                  ORDER BY mc.user_id DESC 
                  LIMIT 50";
$messages_result = mysqli_query($conn, $messages_query);

// Add auto-refresh meta tag in the head section
?>
<meta http-equiv="refresh" content="30"> <!-- Refresh page every 30 seconds -->
<?php
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
                        <div class="chat-messages p-4" style="height: 350px; overflow-y: auto;">
                            <?php while ($message = mysqli_fetch_assoc($messages_result)) { ?>
                                <div
                                    class="message <?php echo ($message['user_id'] == $_SESSION['admin_id']) ? 'text-end' : ''; ?> mb-3">
                                    <strong><?php echo htmlspecialchars($message['username']); ?></strong>
                                    <p class="mb-1"><?php echo htmlspecialchars($message['messages']); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
                            $user_id = $_SESSION['admin_id'];
                            $message = mysqli_real_escape_string($conn, $_POST['message']);

                            $insert_query = "INSERT INTO messages_chat (user_id, messages) VALUES (?, ?)";
                            $stmt = mysqli_prepare($conn, $insert_query);
                            mysqli_stmt_bind_param($stmt, "is", $user_id, $message);
                            mysqli_stmt_execute($stmt);
                        }
                        ?>
                        <form method="POST" class="mt-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="message"
                                    placeholder="Type your message...">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script>
        $(document).ready(function () {
            $('#chatForm').on('submit', function (e) {
                e.preventDefault();
                const message = $('#messageInput').val();
                if (message.trim()) {
                    $.ajax({
                        url: 'send_message.php',
                        method: 'POST',
                        data: {
                            message: message,
                            user_id: <?php echo $_SESSION['admin_id']; ?>
                        },
                        success: function (response) {
                            $('#messageInput').val('');
                            location.reload(); // Refresh to see new message
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>