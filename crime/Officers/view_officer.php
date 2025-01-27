<?php
include '../config.php';
include 'header.php';

if (!isset($_GET['id'])) {
    header("Location: ofc_record.php");
    exit();
}

$officer_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM officer_record WHERE NGO_id = '$officer_id'";
$result = mysqli_query($conn, $query);
$officer = mysqli_fetch_assoc($result);

if (!$officer) {
    header("Location: ofc_record.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Officer Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --police-blue: #1a3a85;
            --police-gold: #c4a777;
        }

        .officer-details dt {
            color: var(--police-blue);
            font-weight: 600;
        }

        .officer-details dd {
            margin-bottom: 1rem;
            padding-left: 1rem;
            border-left: 3px solid var(--police-gold);
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
                        Officer Details
                    </h2>
                    <a href="ofc_record.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to List
                    </a>
                </div>

                <dl class="row officer-details">
                    <dt class="col-sm-3">NGO ID</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['NGO_id']); ?>
                    </dd>

                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Name']); ?>
                    </dd>

                    <dt class="col-sm-3">Father's Name</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Fname']); ?>
                    </dd>

                    <dt class="col-sm-3">Surname</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Surname']); ?>
                    </dd>

                    <dt class="col-sm-3">NGO Code</dt>
                    <dd class="col-sm-9"><span class="badge bg-secondary">
                            <?php echo htmlspecialchars($officer['NGO_code']); ?>
                        </span></dd>

                    <dt class="col-sm-3">Address</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Per_address']); ?>
                    </dd>

                    <dt class="col-sm-3">Phone</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Res_phone']); ?>
                    </dd>

                    <dt class="col-sm-3">Date of Birth</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['D_O_B']); ?>
                    </dd>

                    <dt class="col-sm-3">Qualification</dt>
                    <dd class="col-sm-9">
                        <?php echo htmlspecialchars($officer['Qualification']); ?>
                    </dd>
                </dl>

                <div class="mt-4">
                    <a href="edit_officer.php?id=<?php echo $officer['NGO_id']; ?>" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Edit Officer
                    </a>
                    <a href="delete_officer.php?id=<?php echo $officer['NGO_id']; ?>" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this officer?');">
                        <i class="fas fa-trash-alt me-2"></i>Delete Officer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>