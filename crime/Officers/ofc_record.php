<?php
include '../config.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --police-blue: #1a3a85;
            --police-gold: #c4a777;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ef 100%);
            padding-top: 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--police-blue);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 500;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table tbody tr:hover {
            background-color: rgba(26, 58, 133, 0.05);
        }

        .badge {
            padding: 8px 12px;
            border-radius: 6px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 8px 20px;
            color: #495057;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(26, 58, 133, 0.1);
            color: var(--police-blue);
        }

        .dropdown-item i {
            margin-right: 10px;
            color: var(--police-gold);
        }

        .section-title {
            color: var(--police-blue);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .btn-action {
            padding: 8px 12px;
            background-color: var(--police-blue);
            color: white;
            border: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background-color: #234aa8;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .table-responsive {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-user-shield me-2" style="color: var(--police-gold);"></i>
                        Officer Records
                    </h2>
                    <a href="addOfficer.php" class="btn btn-action">
                        <i class="fas fa-plus me-2"></i>
                        Add New Officer
                    </a>
                </div>

                <div class="table-responsive">
                    <?php
                    $query = "SELECT NGO_id, Name, Fname, Surname, NGO_code, Per_address, Res_phone, D_O_B, Qualification FROM officer_record";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table class='table table-hover'>
                                <thead>
                                    <tr>
                                        <th>NGO ID</th>
                                        <th>Name</th>
                                        <th>Father's Name</th>
                                        <th>Surname</th>
                                        <th>NGO Code</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>DOB</th>
                                        <th>Qualification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['NGO_id']}</td>
                                    <td>{$row['Name']}</td>
                                    <td>{$row['Fname']}</td>
                                    <td>{$row['Surname']}</td>
                                    <td><span class='badge bg-secondary'>{$row['NGO_code']}</span></td>
                                    <td>{$row['Per_address']}</td>
                                    <td>{$row['Res_phone']}</td>
                                    <td>{$row['D_O_B']}</td>
                                    <td>{$row['Qualification']}</td>
                                    <td>
                                        <div class='dropdown'>
                                            <button class='btn btn-action btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                                Actions
                                            </button>
                                            <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                <li>
                                                    <a class='dropdown-item' href='view_officer.php?id={$row['NGO_id']}'>
                                                        <i class='fas fa-eye'></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item' href='edit_officer.php?id={$row['NGO_id']}'>
                                                        <i class='fas fa-edit'></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class='dropdown-item text-danger' href='delete_officer.php?id={$row['NGO_id']}' 
                                                       onclick='return confirm(\"Are you sure you want to delete this officer?\");'>
                                                        <i class='fas fa-trash-alt'></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>
                                <i class='fas fa-exclamation-triangle me-2'></i>
                                No officer records found
                              </div>";
                    }
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>
    </div>
    
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (Required for Bootstrap 4 dropdowns) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>