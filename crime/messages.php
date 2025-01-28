<?php
session_start();

include 'config.php';

// Set the timezone - adjust this to your timezone
date_default_timezone_set('Asia/Kolkata'); // Change this to your timezone

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $message_time = date('Y-m-d H:i:s'); // Current time with correct timezone

    if (!empty($message) && $user_id > 0) {
        $message = mysqli_real_escape_string($conn, $message);
        $insert_sql = "INSERT INTO messages_chat (user_id, messages, message_time) VALUES ('$user_id', '$message', '$message_time')";

        if (!mysqli_query($conn, $insert_sql)) {
            die("Error sending message: " . mysqli_error($conn));
        }
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch messages with usernames - Changed ORDER BY to DESC for newest first
$sql = "
    SELECT 
        messages_chat.*, 
        users.username
    FROM 
        messages_chat 
    JOIN 
        users ON messages_chat.user_id = users.user_id
    ORDER BY 
        messages_chat.message_time DESC
";

$result = mysqli_query($conn, $sql);
$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}

// Reverse the array to display messages in correct order (newest at bottom)
$messages = array_reverse($messages);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Chat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: #343a40;
            color: white;
            padding: 15px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chat-header h1 {
            font-size: 1.5rem;
            margin-left: 10px;
        }

        .go-back-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }

        .message-container {
            flex: 1;
            padding: 70px 15px 80px;
            overflow-y: auto;
            background-color: #e9ecef;
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
        }

        .message.theirs {
            margin-right: auto;
            background-color: #ffffff;
            border-radius: 0 10px 10px 10px;
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
            margin-right: 50px;
        }

        .message-time {
            font-size: 0.75em;
            color: #6c757d;
            position: absolute;
            bottom: 5px;
            right: 10px;
        }

        .message-form {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .message-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 20px;
            background: white;
            font-size: 1em;
            transition: border-color 0.2s;
        }

        .message-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .send-button {
            background-color: #007bff;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .send-button:hover {
            background-color: #0056b3;
        }

        form {
            display: flex;
            gap: 10px;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="chat-header">
        <h1>Admin Chat Panel</h1>
        <a href="home.php" class="go-back-btn">Go Back to Home</a>
    </div>

    <div class="message-container" id="messageContainer">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo $message['user_id'] == $user_id ? 'mine' : 'theirs'; ?>">
                <?php if ($message['user_id'] != $user_id): ?>
                    <div class="username"><?php echo htmlspecialchars($message['username']); ?></div>
                <?php endif; ?>
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
        <?php endforeach; ?>
    </div>

    <div class="message-form">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="message" class="message-input" placeholder="Type a message" autocomplete="off"
                required>
            <button type="submit" class="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
        window.onload = function () {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        };

        setInterval(function () {
            window.location.reload();
        }, 5000);
    </script>
</body>

</html>