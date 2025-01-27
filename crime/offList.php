<?php 
session_start();
if (!isset($_SESSION['login_user'])) {
   header("Location: login1.php");
   exit();
}
include 'header.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Police Case Management System - Officers List</title>
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

      .case-header h2 {
         font-weight: 600;
         margin: 0;
         display: flex;
         align-items: center;
      }

      .case-header h2 i {
         margin-right: 15px;
         color: var(--police-gold);
      }

      .officers-section {
         background: white;
         border-radius: 10px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
         margin-bottom: 30px;
         overflow: hidden;
      }

      .section-header {
         background: var(--police-blue);
         color: white;
         padding: 20px;
         border-bottom: 3px solid var(--police-gold);
      }

      .section-header h3 {
         margin: 0;
         font-weight: 600;
         display: flex;
         align-items: center;
      }

      .section-header i {
         margin-right: 10px;
         color: var(--police-gold);
      }

      .table {
         margin: 0;
      }

      .table thead th {
         background: #f8f9fa;
         color: var(--police-blue);
         font-weight: 600;
         text-transform: uppercase;
         font-size: 0.85em;
         padding: 15px;
         border-bottom: 2px solid var(--police-blue);
      }

      .table tbody td {
         padding: 15px;
         vertical-align: middle;
         border-bottom: 1px solid #eee;
      }

      .table tbody tr:hover {
         background: #f8f9fa;
      }

      .ngo-id {
         font-family: 'Consolas', monospace;
         background: #f8f9fa;
         padding: 4px 8px;
         border-radius: 4px;
         font-weight: 500;
         color: var(--police-blue);
      }

      .qualification-badge {
         background: var(--police-blue);
         color: white;
         padding: 5px 10px;
         border-radius: 15px;
         font-size: 0.85em;
         font-weight: 500;
      }

      .phone-number {
         font-family: 'Consolas', monospace;
         color: #666;
      }

      .stats-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
         gap: 20px;
         margin-bottom: 30px;
      }

      .stat-card {
         background: white;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
         text-align: center;
         border-top: 4px solid var(--police-blue);
      }

      .stat-number {
         font-size: 2em;
         font-weight: 600;
         color: var(--police-blue);
         margin: 10px 0;
      }

      .stat-label {
         color: #666;
         font-size: 0.9em;
         text-transform: uppercase;
         letter-spacing: 1px;
      }
   </style>
</head>

<body>
   <div class="container-fluid px-4">
      <div class="case-header">
         <h2><i class="fas fa-user-shield"></i> Police Officers Directory</h2>
      </div>

      <?php
      
      $servername = "localhost";
      $username = "root";
      $password = "";
      $db = "fir_management";
      $conn = mysqli_connect($servername, $username, $password, $db);
      $q1 = "SELECT * FROM `officer_record`";
      $result = mysqli_query($conn, $q1);
      $total_officers = mysqli_num_rows($result);
      ?>

      <div class="stats-container">
         <div class="stat-card">
            <i class="fas fa-users fa-2x" style="color: var(--police-blue)"></i>
            <div class="stat-number"><?php echo $total_officers; ?></div>
            <div class="stat-label">Total Officers</div>
         </div>
         <!-- Add more stat cards if needed -->
      </div>

      <div class="officers-section">
         <div class="section-header">
            <h3><i class="fas fa-list"></i> Officers List</h3>
         </div>
         <div class="table-responsive">
            <table class="table">
               <thead>
                  <tr>
                     <th><i class="fas fa-id-badge me-2"></i>NGO ID</th>
                     <th><i class="fas fa-user me-2"></i>Name</th>
                     <th><i class="fas fa-user me-2"></i>Father's Name</th>
                     <th>Surname</th>
                     <th>NGO Code</th>
                     <th><i class="fas fa-map-marker-alt me-2"></i>Address</th>
                     <th><i class="fas fa-phone me-2"></i>Phone</th>
                     <th><i class="fas fa-calendar me-2"></i>Date of Birth</th>
                     <th><i class="fas fa-graduation-cap me-2"></i>Qualification</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($result) {
                     while ($row = mysqli_fetch_array($result)) {
                        echo '<tr>
                                    <td><span class="ngo-id">' . $row['NGO_id'] . '</span></td>
                                    <td><strong>' . $row['Name'] . '</strong></td>
                                    <td>' . $row['Fname'] . '</td>
                                    <td>' . $row['Surname'] . '</td>
                                    <td>' . $row['NGO_code'] . '</td>
                                    <td>' . $row['Per_address'] . '</td>
                                    <td><span class="phone-number">' . $row['Res_phone'] . '</span></td>
                                    <td>' . $row['D_O_B'] . '</td>
                                    <td><span class="qualification-badge">' . $row['Qualification'] . '</span></td>
                                </tr>';
                     }
                  } else {
                     echo '<tr><td colspan="9" class="text-center">No records found</td></tr>';
                  }
                  ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>


<?php include 'footer.php'; ?>