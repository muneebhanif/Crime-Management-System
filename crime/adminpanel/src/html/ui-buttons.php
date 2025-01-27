<?php
include 'config.php';

// Delete officer if requested
if (isset($_GET['delete'])) {
  $id = mysqli_real_escape_string($conn, $_GET['delete']);
  $delete_query = "DELETE FROM officer_record WHERE NGO_id = '$id'";
  if (mysqli_query($conn, $delete_query)) {
    echo "<script>alert('Officer deleted successfully!');</script>";
  } else {
    echo "<script>alert('Error deleting officer: " . mysqli_error($conn) . "');</script>";
  }
}

// Fetch all officers
$query = "SELECT * FROM officer_record ORDER BY Name ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Police Management System - Officers</title>
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --police-blue: #1a3a85;
      --police-gold: #c4a777;
      --light-gray: #f8f9fa;
      --border-color: #e9ecef;
    }

    .page-wrapper {
      display: flex;
      min-height: 100vh;
      background: var(--light-gray);
    }

    .body-wrapper {
      flex-grow: 1;
      margin-left: 260px;
      min-width: 0;
      padding: 30px;
    }

    .page-header {
      margin-bottom: 25px;
    }

    .page-title {
      font-size: 1.5rem;
      color: var(--police-blue);
      margin-bottom: 0;
    }

    .breadcrumb {
      margin-bottom: 0;
      font-size: 0.9rem;
    }

    .card {
      border: none;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      border-radius: 10px;
    }

    .card-header {
      background: white;
      border-bottom: 1px solid var(--border-color);
      padding: 20px;
    }

    .search-box {
      max-width: 300px;
      border-radius: 20px;
      padding: 10px 20px;
      border: 1px solid var(--border-color);
    }

    .search-box:focus {
      box-shadow: none;
      border-color: var(--police-blue);
    }

    .btn-add {
      background: var(--police-blue);
      color: white;
      border-radius: 20px;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }

    .btn-add:hover {
      background: #15306d;
      transform: translateY(-2px);
    }

    .table {
      margin: 0;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      color: #495057;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }

    .table td {
      vertical-align: middle;
      padding: 15px 20px;
      border-color: var(--border-color);
    }

    .officer-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .officer-img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--police-gold);
    }

    .officer-name {
      font-weight: 600;
      margin-bottom: 2px;
      color: var(--police-blue);
    }

    .officer-code {
      font-size: 0.8rem;
      color: #6c757d;
    }

    .badge-qualification {
      background: var(--police-blue);
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .action-btn {
      width: 32px;
      height: 32px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .action-btn:hover {
      transform: translateY(-2px);
    }

    .btn-view {
      background: #e3f2fd;
      color: #1976d2;
    }

    .btn-edit {
      background: #fff8e1;
      color: #ffa000;
    }

    .btn-delete {
      background: #ffebee;
      color: #d32f2f;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-active {
      background: #e8f5e9;
      color: #2e7d32;
    }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="body-wrapper">
      <div class="page-header d-flex justify-content-between align-items-center">
        <div>
          <h4 class="page-title">Police Officers</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Officers</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-3">
          <input type="text" class="form-control search-box" id="searchOfficer" placeholder="Search officers...">
          <a href="addOfficer.php" class="btn btn-add">
            <i class="fas fa-plus me-2"></i>Add Officer
          </a>
        </div>
      </div>

      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Officer Details</th>
                <th>NGO ID</th>
                <th>Contact</th>
                <th>Qualification</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($officer = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td>
                    <div class="officer-info">
                      <img src="../assets/images/profile/user-1.jpg" class="officer-img">
                      <div>
                        <div class="officer-name"><?php echo $officer['Name']; ?></div>
                        <div class="officer-code"><?php echo $officer['NGO_code']; ?></div>
                      </div>
                    </div>
                  </td>
                  <td><?php echo $officer['NGO_id']; ?></td>
                  <td><?php echo $officer['Res_phone']; ?></td>
                  <td>
                    <span class="badge-qualification">
                      <?php echo $officer['Qualification']; ?>
                    </span>
                  </td>
                  <td>
                    <span class="status-badge status-active">Active</span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <button class="btn action-btn btn-view">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn action-btn btn-edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn action-btn btn-delete"
                        onclick="deleteOfficer('<?php echo $officer['NGO_id']; ?>')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>

  <script>
    // Search functionality
    document.getElementById('searchOfficer').addEventListener('keyup', function () {
      let searchText = this.value.toLowerCase();
      let tableRows = document.querySelectorAll('tbody tr');

      tableRows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
      });
    });

    // Delete confirmation
    function deleteOfficer(id) {
      if (confirm('Are you sure you want to delete this officer?')) {
        window.location.href = `?delete=${id}`;
      }
    }
  </script>
</body>

</html>