<?php
session_start();
include 'session.php';
include 'header.php';

// ✅ session_start() is the FIRST line




// Check session timeout
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $_SESSION['expire_time']) {
   // Update user status to offline
   if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      $update_status = "UPDATE users SET is_online = 0 WHERE user_id = '$user_id'";
      mysqli_query($conn, $update_status);
   }

   // Clear all session variables
   session_unset();
   session_destroy();
   header("Location: login1.php?msg=timeout");
   exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Police Case Management System - Search</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      :root {
         --police-blue: #1a3a85;
         --police-gold: #c4a777;
      }

      body {
         background-color: #f0f2f5;
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      .case-header {
         background: var(--police-blue);
         color: white;
         padding: 25px;
         margin-bottom: 30px;
         border-radius: 0;
         box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
         position: relative;
         overflow: hidden;
      }

      .case-header::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background: url('police-badge.png') no-repeat center right;
         opacity: 0.1;
      }

      .search-container {
         background: white;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
         margin-bottom: 30px;
      }

      .search-input {
         border: 2px solid var(--police-blue);
         border-radius: 30px;
         padding: 12px 25px;
         font-size: 1.1em;
         width: 100%;
         max-width: 500px;
         margin-right: 10px;
      }

      .search-btn {
         background: var(--police-blue);
         border: none;
         border-radius: 30px;
         padding: 12px 30px;
         color: white;
         font-weight: 500;
         display: flex;
         align-items: center;
         gap: 10px;
         transition: all 0.3s ease;
      }

      .search-btn:hover {
         background: #152d69;
         transform: translateY(-1px);
      }

      .case-section {
         background: white;
         border-radius: 10px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
         margin-bottom: 30px;
         overflow: hidden;
      }

      .table {
         margin: 0;
      }

      .table thead th {
         background: var(--police-blue);
         color: white;
         font-weight: 600;
         text-transform: uppercase;
         font-size: 0.85em;
         padding: 15px;
         border: none;
      }

      .table tbody td {
         padding: 15px;
         vertical-align: middle;
         border-bottom: 1px solid #eee;
      }

      .table tbody tr:hover {
         background: #f8f9fa;
      }

      .status-badge {
         padding: 6px 12px;
         border-radius: 20px;
         font-size: 0.8em;
         font-weight: 500;
      }

      .dropdown-menu {
         border: none;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
      }

      .dropdown-item {
         padding: 8px 20px;
      }

      .dropdown-item i {
         margin-right: 8px;
         width: 20px;
      }

      .img-thumbnail {
         border: 2px solid #eee;
         border-radius: 8px;
      }

      .btn-sm {
         padding: 0.25rem 0.5rem;
         font-size: 0.875rem;
         line-height: 1.5;
         border-radius: 0.2rem;
      }

      .gap-2 {
         gap: 0.5rem !important;
      }

      .btn i {
         font-size: 0.875rem;
      }
   </style>
</head>

<body>
   <div class="container-fluid px-4">
      <div class="case-header">
         <h2><i class="fas fa-search"></i> Criminal Records Search</h2>
      </div>

      <div class="search-container text-center">
         <form method="post" class="d-flex justify-content-center align-items-center">
            <input type="text" class="search-input" placeholder="Search Criminal's By Name" name="search">
            <button class="search-btn">
               <i class="fas fa-search"></i>
               Search Records
            </button>
         </form>
      </div>

      <div class="case-section">
         <?php
         include 'config.php';
         $q = "SELECT * FROM crime_register";
         $result = mysqli_query($conn, $q);
         ?>
         <div class="table-responsive">
            <table class="table">
               <thead>
                  <tr>
                     <th>Image</th>
                     <th>Crime ID</th>
                     <th>Head ID</th>
                     <th>Date of Offence</th>
                     <th>Under Section</th>
                     <th>Date of Report</th>
                     <th>Case Status</th>
                     <th>Arrested</th>
                     <th>Challan</th>
                     <th>Officer ID</th>
                     <th>Name</th>
                     <th>Father Name</th>
                     <th>CNIC</th>
                     <th>Gender</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php while ($row = mysqli_fetch_array($result)) { ?>
                     <tr>
                        <td><img src="<?php echo $row['img']; ?>" class="img-thumbnail" width="100"></td>
                        <td><strong><?php echo $row['Crime_Id']; ?></strong></td>
                        <td><?php echo $row['Head_id']; ?></td>
                        <td><?php echo $row['Date_of_Offence']; ?></td>
                        <td><?php echo $row['Under_Section']; ?></td>
                        <td><?php echo $row['Date_of_Report']; ?></td>
                        <td><span class="status-badge bg-info"><?php echo $row['Case_status']; ?></span></td>
                        <td><span
                              class="status-badge <?php echo $row['Arrested'] == 'Yes' ? 'bg-success' : 'bg-danger'; ?>"><?php echo $row['Arrested']; ?></span>
                        </td>
                        <td><?php echo $row['Challan']; ?></td>
                        <td><?php echo $row['Officer_id']; ?></td>
                        <td><?php echo $row['Name']; ?></td>
                        <td><?php echo $row['Father_name']; ?></td>
                        <td><?php echo $row['CNIC']; ?></td>
                        <td><?php echo $row['Gender']; ?></td>
                        <td>
                           <div class="d-flex gap-2">
                              <a href="view.php?id=<?php echo $row['Crime_Id']; ?>" class="btn btn-sm btn-primary">
                                 <i class="fas fa-eye"></i>
                              </a>
                              <a href="update.php?id=<?php echo $row['Crime_Id']; ?>" class="btn btn-sm btn-warning">
                                 <i class="fas fa-edit"></i>
                              </a>
                              <a href="delete.php?id=<?php echo $row['Crime_Id']; ?>" class="btn btn-sm btn-danger"
                                 onclick="return confirm('Are you sure you want to delete this record?')">
                                 <i class="fas fa-trash-alt"></i>
                              </a>
                           </div>
                        </td>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>

      <div class="text-center mt-4 mb-5">
         <a href="printable.php" target="_blank" class="btn btn-success btn-lg">
            <i class="fas fa-print me-2"></i> Print Records
         </a>
      </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php include 'footer.php';
// Rest of your code
?>