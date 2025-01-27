<?php
ob_start();
include '../config.php';
include 'header.php';

if (!isset($_GET['id'])) {
    header("Location: ofc_record.php");
    exit();
}

$officer_id = mysqli_real_escape_string($conn, $_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $ngo_code = mysqli_real_escape_string($conn, $_POST['ngo_code']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);

    $query = "UPDATE officer_record SET 
              Name = '$name',
              Fname = '$fname',
              Surname = '$surname',
              NGO_code = '$ngo_code',
              Per_address = '$address',
              Res_phone = '$phone',
              D_O_B = '$dob',
              Qualification = '$qualification'
              WHERE NGO_id = '$officer_id'";

    if (mysqli_query($conn, $query)) {
        header("Location: ofc_record.php");
        exit();
    } else {
        $error = "Error updating record: " . mysqli_error($conn);
    }
}

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
    <title>Edit Officer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-edit me-2" style="color: var(--police-gold);"></i>
                        Edit Officer
                    </h2>
                    <a href="ofc_record.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to List
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name"
                                value="<?php echo htmlspecialchars($officer['Name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="fname"
                                value="<?php echo htmlspecialchars($officer['Fname']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Surname</label>
                            <input type="text" class="form-control" name="surname"
                                value="<?php echo htmlspecialchars($officer['Surname']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NGO Code</label>
                            <input type="text" class="form-control" name="ngo_code"
                                value="<?php echo htmlspecialchars($officer['NGO_code']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3"
                            required><?php echo htmlspecialchars($officer['Per_address']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone"
                                value="<?php echo htmlspecialchars($officer['Res_phone']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob"
                                value="<?php echo htmlspecialchars($officer['D_O_B']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Qualification</label>
                            <input type="text" class="form-control" name="qualification"
                                value="<?php echo htmlspecialchars($officer['Qualification']); ?>" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>