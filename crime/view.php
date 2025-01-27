<?php
session_start();
include 'session.php';
include 'header.php';
include 'config.php';

if (isset($_GET['id'])) {
    $crime_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM crime_register WHERE Crime_Id = '$crime_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header("Location: search.php");
        exit();
    }
} else {
    header("Location: search.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Criminal Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .detail-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .criminal-image {
            max-width: 300px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: bold;
            color: #1a3a85;
        }

        .message-bubble {
            margin-bottom: 15px;
        }

        .message-content {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .message-form textarea {
            resize: none;
            border-radius: 20px;
            padding: 10px 20px;
        }

        .message-form button {
            border-radius: 20px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="detail-card">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h2><i class="fas fa-file-alt"></i> Criminal Record Details</h2>
                    <hr>
                </div>

                <div class="col-md-4 text-center">
                    <img src="<?php echo $row['img']; ?>" class="criminal-image img-fluid" alt="Criminal Image">
                </div>

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p><span class="detail-label">Crime ID:</span> <?php echo $row['Crime_Id']; ?></p>
                            <p><span class="detail-label">Head ID:</span> <?php echo $row['Head_id']; ?></p>
                            <p><span class="detail-label">Date of Offence:</span> <?php echo $row['Date_of_Offence']; ?>
                            </p>
                            <p><span class="detail-label">Under Section:</span> <?php echo $row['Under_Section']; ?></p>
                            <p><span class="detail-label">Date of Report:</span> <?php echo $row['Date_of_Report']; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p><span class="detail-label">Name:</span> <?php echo $row['Name']; ?></p>
                            <p><span class="detail-label">Father's Name:</span> <?php echo $row['Father_name']; ?></p>
                            <p><span class="detail-label">CNIC:</span> <?php echo $row['CNIC']; ?></p>
                            <p><span class="detail-label">Gender:</span> <?php echo $row['Gender']; ?></p>
                            <p><span class="detail-label">Officer ID:</span> <?php echo $row['Officer_id']; ?></p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p><span class="detail-label">Case Status:</span>
                                <span class="badge bg-info"><?php echo $row['Case_status']; ?></span>
                            </p>
                            <p><span class="detail-label">Arrested:</span>
                                <span
                                    class="badge <?php echo $row['Arrested'] == 'Yes' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $row['Arrested']; ?>
                                </span>
                            </p>
                            <p><span class="detail-label">Challan:</span> <?php echo $row['Challan']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="search.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Search
                    </a>
                    <a href="update.php?id=<?php echo $row['Crime_Id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Record
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="detail-card">
            <h4><i class="fas fa-comments"></i> Case Discussion</h4>
            <hr>

            <!-- Chat Messages Area -->
            <div class="chat-messages" id="chatMessages"
                style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php
                $messages_query = "SELECT m.*, u.username, u.role 
                                 FROM messages m 
                                 JOIN users u ON m.user_id = u.user_id 
                                 WHERE m.crime_id = '$crime_id' 
                                 ORDER BY m.created_at ASC";
                $messages_result = mysqli_query($conn, $messages_query);

                while ($message = mysqli_fetch_assoc($messages_result)) {
                    $isCurrentUser = ($message['user_id'] == $_SESSION['user_id']);
                    ?>
                    <div class="message-bubble mb-3 <?php echo $isCurrentUser ? 'text-end' : ''; ?>">
                        <div class="message-content <?php echo $isCurrentUser ? 'bg-primary text-white' : 'bg-light'; ?>"
                            style="display: inline-block; padding: 10px 15px; border-radius: 15px; max-width: 70%;">
                            <div class="message-header"
                                style="font-size: 0.8em; <?php echo $isCurrentUser ? 'color: #fff;' : 'color: #666;'; ?>">
                                <strong><?php echo htmlspecialchars($message['username']); ?></strong>
                                (<?php echo htmlspecialchars($message['role']); ?>)
                                <span
                                    class="ms-2"><?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></span>
                            </div>
                            <div class="message-text mt-1">
                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Message Input Form -->
            <form id="messageForm" class="message-form">
                <div class="input-group">
                    <input type="hidden" name="crime_id" value="<?php echo $crime_id; ?>">
                    <textarea class="form-control" name="message" id="messageInput" rows="2"
                        placeholder="Type your message here..." required></textarea>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Scroll to bottom of chat
            function scrollToBottom() {
                var chatMessages = document.getElementById('chatMessages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            scrollToBottom();

            // Handle message submission
            $('#messageForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'send_message.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            // Clear input and reload messages
                            $('#messageInput').val('');
                            loadMessages();
                        } else {
                            alert('Error sending message: ' + response.message);
                        }
                    },
                    error: function () {
                        alert('Error sending message');
                    }
                });
            });

            // Load messages periodically
            function loadMessages() {
                $.ajax({
                    url: 'get_messages.php',
                    type: 'GET',
                    data: { crime_id: <?php echo $crime_id; ?> },
                    success: function (response) {
                        $('#chatMessages').html(response);
                        scrollToBottom();
                    }
                });
            }

            // Refresh messages every 10 seconds
            setInterval(loadMessages, 10000);
        });
    </script>
</body>

</html>

<?php include 'footer.php'; ?>