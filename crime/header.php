<?php
ob_start();
include 'config.php'; // Start output buffering
if (!isset($header_included)) {
  $header_included = true;
} else {
  return;
}

$newMessagesCount = 0; // Replace with actual database query result

$currentUserId = 1;

$sql = "SELECT COUNT(*) as count FROM messages_chat WHERE status = 'new' AND user_id = $currentUserId";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$newMessagesCount = $row['count'];
mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police Case Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --police-blue: #1a3a85;
      --police-gold: #c4a777;
    }

    .navbar {
      background: var(--police-blue) !important;
      padding: 15px 0;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      color: white !important;
      font-weight: 600;
      font-size: 1.4rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar-brand i {
      color: var(--police-gold);
    }

    .navbar-nav {
      margin-left: 20px;
    }

    .nav-item {
      position: relative;
      margin: 0 5px;
    }

    .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      padding: 10px 15px !important;
      border-radius: 5px;
      transition: all 0.3s ease;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .nav-link i {
      color: var(--police-gold);
      font-size: 1.1em;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white !important;
    }

    .nav-item.active .nav-link {
      background: rgba(255, 255, 255, 0.15);
      color: white !important;
    }

    .navbar-toggler {
      border: none;
      padding: 8px;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .logout-btn {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545 !important;
      border: 1px solid rgba(220, 53, 69, 0.3);
      padding: 8px 20px !important;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: #dc3545;
      color: white !important;
      border-color: #dc3545;
    }

    @media (max-width: 991px) {
      .navbar-collapse {
        background: var(--police-blue);
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
      }

      .nav-item {
        margin: 5px 0;
      }

      .logout-btn {
        margin-top: 10px;
        text-align: center;
      }
    }

    .badge-danger {
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 5px;
      font-size: 0.8em;
      margin-left: 5px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="#">
        <i class="fas fa-shield-alt"></i>
        Police Management System
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="home.php">
              <i class="fas fa-file-alt"></i>
              New Case
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="search.php">
              <i class="fas fa-search"></i>
              Records
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="offList.php">
              <i class="fas fa-user-shield"></i>
              Officers List
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="crime_info.php">
              <i class="fas fa-info-circle"></i>
              Crime Information
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="chat.php">
              <i class="fas fa-info-circle"></i>
              Chat
              <?php if ($newMessagesCount > 0): ?>
                <span class="badge badge-danger"><?php echo $newMessagesCount; ?></span>
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link logout-btn" href="logout.php">
              <i class="fas fa-sign-out-alt"></i>
              Log Out
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Add active class to current nav item
    document.addEventListener('DOMContentLoaded', function () {
      const currentLocation = location.pathname;
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentLocation.split('/').pop()) {
          link.parentElement.classList.add('active');
        }
      });
    });
  </script>
</body>

</html>