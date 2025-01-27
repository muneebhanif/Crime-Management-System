<?php
session_start();
if (!isset($_SESSION['login_user'])) {
    header("Location: login1.php");
    exit();
}
include 'header.php';
include 'config.php';

// Fetch data from database for each table
$witnessQuery = "SELECT * FROM witness";
$victimQuery = "SELECT * FROM victim";
$accusedQuery = "SELECT * FROM accussed";

$witnessResult = mysqli_query($conn, $witnessQuery);
$victimResult = mysqli_query($conn, $victimQuery);
$accusedResult = mysqli_query($conn, $accusedQuery);
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

        .case-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-header {
            background: var(--police-blue);
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--police-gold);
        }

        .section-header i {
            color: var(--police-gold);
            margin-right: 10px;
        }

        .badge-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid var(--police-blue);
            color: var(--police-blue);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            padding: 15px;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .status-accused {
            background: #dc3545;
            color: white;
        }

        .status-victim {
            background: #ffc107;
            color: #000;
        }

        .status-witness {
            background: #0dcaf0;
            color: white;
        }

        .cnic-number {
            font-family: 'Consolas', monospace;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .quick-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            border-left: 4px solid var(--police-blue);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: var(--police-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 1.8em;
            color: var(--police-blue);
        }

        .stat-info p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-4">
        <div class="case-header">
            <h2><i class="fas fa-shield-alt"></i> Crime Information</h2>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-minus"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo mysqli_num_rows($accusedResult); ?></h3>
                    <p>Accused Persons</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo mysqli_num_rows($victimResult); ?></h3>
                    <p>Victims</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo mysqli_num_rows($witnessResult); ?></h3>
                    <p>Witnesses</p>
                </div>
            </div>
        </div>

        <!-- Accused Section -->
        <div class="case-section">
            <div class="section-header">
                <h4 class="mb-0"><i class="fas fa-user-minus"></i> Accused Persons</h4>
                <span class="badge-count"><?php echo mysqli_num_rows($accusedResult); ?> Records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>CNIC</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($accusedResult)) { ?>
                            <tr>
                                <td><span class="cnic-number"><?php echo $row['Accussed_CNIC']; ?></span></td>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Father_name']; ?></td>
                                <td><?php echo $row['Gender']; ?></td>
                                <td><span class="status-badge status-accused">Accused</span></td>
                                <td><?php echo $row['Remarks']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Victim Section -->
        <div class="case-section">
            <div class="section-header">
                <h4 class="mb-0"><i class="fas fa-user-shield"></i> Victims</h4>
                <span class="badge-count"><?php echo mysqli_num_rows($victimResult); ?> Records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>CNIC</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($victimResult)) { ?>
                            <tr>
                                <td><strong><?php echo $row['Victim_CNIC']; ?></strong></td>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Father_name']; ?></td>
                                <td><span class="status-badge bg-warning text-dark">Victim</span></td>
                                <td><?php echo $row['Remarks']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Witness Section -->
        <div class="case-section">
            <div class="section-header">
                <h4 class="mb-0"><i class="fas fa-eye"></i> Witnesses</h4>
                <span class="badge-count"><?php echo mysqli_num_rows($witnessResult); ?> Records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>CNIC</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($witnessResult)) { ?>
                            <tr>
                                <td><strong><?php echo $row['Witness_CNIC']; ?></strong></td>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Father_name']; ?></td>
                                <td><?php echo $row['Contact']; ?></td>
                                <td><span class="status-badge bg-info text-white">Witness</span></td>
                                <td><?php echo $row['Remarks']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>