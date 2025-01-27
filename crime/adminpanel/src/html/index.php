<?php
include 'config.php';  // Move this to the top to establish DB connection first
session_start();

// Debug session (remove in production)
// var_dump($_SESSION);

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
  // Redirect to login page if not authenticated
  header('Location: login.php');
  exit();
}

// Additional security: Check session timeout (optional)
$max_session_time = 1800; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $max_session_time)) {
  // Destroy session and redirect to login
  session_unset();
  session_destroy();
  header('Location: login.php');
  exit();
}
// Get total cases
$total_cases_query = "SELECT COUNT(*) as total FROM crime_register";
$total_cases_result = mysqli_query($conn, $total_cases_query);
$total_cases = mysqli_fetch_assoc($total_cases_result)['total'];

// Get solved cases (where Case_status is 'Solved')
$solved_cases_query = "SELECT COUNT(*) as solved FROM crime_register WHERE Case_status = 'Solved'";
$solved_cases_result = mysqli_query($conn, $solved_cases_query);
$solved_cases = mysqli_fetch_assoc($solved_cases_result)['solved'];

// Get pending cases (where Case_status is 'Pending')
$pending_cases_query = "SELECT COUNT(*) as pending FROM crime_register WHERE Case_status = 'Pending'";
$pending_cases_result = mysqli_query($conn, $pending_cases_query);
$pending_cases = mysqli_fetch_assoc($pending_cases_result)['pending'];

// Get total officers
$total_officers_query = "SELECT COUNT(*) as total FROM officer_record";
$total_officers_result = mysqli_query($conn, $total_officers_query);
$total_officers = mysqli_fetch_assoc($total_officers_result)['total'];

// Get monthly case statistics for the chart
$monthly_stats_query = "SELECT DATE_FORMAT(Date_of_Report, '%Y-%m') as month, 
                              COUNT(*) as count 
                       FROM crime_register 
                       GROUP BY month 
                       ORDER BY month DESC 
                       LIMIT 9";
$monthly_stats_result = mysqli_query($conn, $monthly_stats_query);
$months = [];
$counts = [];
while ($row = mysqli_fetch_assoc($monthly_stats_result)) {
  $months[] = date('M', strtotime($row['month']));
  $counts[] = $row['count'];
}
$months = array_reverse($months);
$counts = array_reverse($counts);

// Get case distribution by Under_Section
$case_types_query = "SELECT Under_Section, COUNT(*) as count 
                     FROM crime_register 
                     GROUP BY Under_Section 
                     ORDER BY count DESC 
                     LIMIT 4";
$case_types_result = mysqli_query($conn, $case_types_query);
$case_labels = [];
$case_counts = [];
while ($row = mysqli_fetch_assoc($case_types_result)) {
  $case_labels[] = $row['Under_Section'];
  $case_counts[] = $row['count'];
}

// Get recent cases
$recent_cases_query = "SELECT cr.Crime_Id, cr.Under_Section, cr.Date_of_Report, 
                             cr.Case_status, cr.Name as complainant_name,
                             o.Name as officer_name 
                      FROM crime_register cr 
                      LEFT JOIN officer_record o ON cr.Officer_id = o.NGO_id 
                      ORDER BY cr.Date_of_Report DESC 
                      LIMIT 5";
$recent_cases_result = mysqli_query($conn, $recent_cases_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Police Management System - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --police-blue: #1a3a85;
      --police-gold: #c4a777;
    }

    .stat-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .chart-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
    }

    .chart-title {
      color: var(--police-blue);
      font-weight: 600;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <?php include 'sidebar.php'; ?>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="fas fa-bars"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="fas fa-bell"></i>
                <div class="notification bg-danger rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                  <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a>
                  <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">
          <div class="col-md-3">
            <div class="stat-card p-4">
              <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary text-white me-3">
                  <i class="fas fa-file-alt"></i>
                </div>
                <div>
                  <h6 class="mb-0">Total Cases</h6>
                  <h3 class="mb-0"><?php echo $total_cases; ?></h3>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card p-4">
              <div class="d-flex align-items-center">
                <div class="stat-icon bg-success text-white me-3">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div>
                  <h6 class="mb-0">Solved Cases</h6>
                  <h3 class="mb-0"><?php echo $solved_cases; ?></h3>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card p-4">
              <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning text-white me-3">
                  <i class="fas fa-clock"></i>
                </div>
                <div>
                  <h6 class="mb-0">Pending Cases</h6>
                  <h3 class="mb-0"><?php echo $pending_cases; ?></h3>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card p-4">
              <div class="d-flex align-items-center">
                <div class="stat-icon bg-info text-white me-3">
                  <i class="fas fa-user-shield"></i>
                </div>
                <div>
                  <h6 class="mb-0">Active Officers</h6>
                  <h3 class="mb-0"><?php echo $total_officers; ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-8">
            <div class="chart-card">
              <div class="chart-title">
                <h5><i class="fas fa-chart-line me-2"></i>Crime Statistics Overview</h5>
              </div>
              <div class="p-4">
                <div id="crimeChart" style="height: 350px;"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="chart-card">
              <div class="chart-title">
                <h5><i class="fas fa-chart-pie me-2"></i>Case Distribution</h5>
              </div>
              <div class="p-4">
                <div id="caseDistribution" style="height: 350px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="chart-card">
              <div class="chart-title d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-list me-2"></i>Recent Cases</h5>
                <a href="#" class="btn btn-primary btn-sm">View All</a>
              </div>
              <div class="table-responsive p-4">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Case ID</th>
                      <th>Type</th>
                      <th>Location</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Assigned Officer</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($case = mysqli_fetch_assoc($recent_cases_result)) { ?>
                      <tr>
                        <td><?php echo $case['Crime_Id']; ?></td>
                        <td><?php echo $case['Under_Section']; ?></td>
                        <td><?php echo $case['complainant_name']; ?></td>
                        <td><?php echo date('d M Y', strtotime($case['Date_of_Report'])); ?></td>
                        <td>
                          <span class="badge bg-<?php echo $case['Case_status'] == 'Solved' ? 'success' : 'warning'; ?>">
                            <?php echo $case['Case_status']; ?>
                          </span>
                        </td>
                        <td><?php echo $case['officer_name'] ?? 'Not Assigned'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
              class="pe-1 text-primary text-decoration-underline">Muneeb And Hasnat</a> Distributed by <a
              href="https://themewagon.com">Crux Dynamics</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/dashboard.js"></script>
  <script>
    // Crime Statistics Chart
    var crimeOptions = {
      series: [{
        name: 'Total Cases',
        data: <?php echo json_encode($counts); ?>
      }],
      chart: {
        type: 'area',
        height: 350,
        toolbar: {
          show: false
        }
      },
      stroke: {
        curve: 'smooth'
      },
      xaxis: {
        categories: <?php echo json_encode($months); ?>
      }
    };
    var crimeChart = new ApexCharts(document.querySelector("#crimeChart"), crimeOptions);
    crimeChart.render();

    // Case Distribution Chart
    var distributionOptions = {
      series: <?php echo json_encode($case_counts); ?>,
      chart: {
        type: 'donut',
        height: 350
      },
      labels: <?php echo json_encode($case_labels); ?>,
      colors: ['#1a3a85', '#c4a777', '#dc3545', '#6c757d']
    };
    var distributionChart = new ApexCharts(document.querySelector("#caseDistribution"), distributionOptions);
    distributionChart.render();
  </script>
</body>

</html>