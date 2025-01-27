<?php
session_start();
include 'session.php';
include 'header.php';
include 'config.php';

if (!isset($_GET['user_id'])) {
    header('Location: messages.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];
$other_user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

// Get other user's details
$user_query = "SELECT username, role FROM users WHERE user_id = $other_user_id";
$user_result = mysqli_query($conn, $user_query);
$other_user = mysqli_fetch_assoc($user_result);

// Mark messages as read
$mark_read_query = "UPDATE messages 
                   SET is_read = 1 
                   WHERE sender_id = $other_user_id 
                   AND receiver_id = $current_user_id";
mysqli_query($conn, $mark_read_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($other_user['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .chat-container {
            height: 500px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .message-bubble {
            margin-bottom: 15px;
        }
        .message-content {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .sent .message-content {
            background: #007bff;
            color: white;
        }
        .received .message-content {
            background: white;
        }
        .message-time {
            font-size: 0.75em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <a href="messages.php" class="text-white text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>
                        </a>
                        <?php echo htmlspecialchars($other_user['username']); ?>
                        <small>(<?php echo htmlspecialchars($other_user['role']); ?>)</small>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="chat-container" id="chatContainer">
                    <!-- Messages will be loaded here -->
                </div>
                <form id="messageForm" class="mt-3">
                    <div class="input-group">
                        <input type="hidden" name="receiver_id" value="<?php echo $other_user_id; ?>">
                        <textarea class="form-control" name="message" rows="2" 
                                placeholder="Type your message..." required></textarea>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadMessages() {
                $.get('get_chat_messages.php', { user_id: <?php echo $other_user_id; ?> }, function(data) {
                    $('#chatContainer').html(data);
                    scrollToBottom();
                });
            }

            function scrollToBottom() {
                var container = document.getElementById('chatContainer');
                container.scrollTop = container.scrollHeight;
            }

            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'send_chat_message.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.success) {
                            $('textarea[name="message"]').val('');
                            loadMessages();
                        } else {
                            alert('Error sending message');
                        }
                    }
                });
            });

            loadMessages();
            setInterval(loadMessages, 5000);
        });
    </script>
</body>
</html>

<?php include 'footer.php'; ?> 