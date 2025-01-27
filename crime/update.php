<?php
session_start();
if (!isset($_SESSION['login_user'])) {
    header("Location: login1.php");
    exit();
}
include 'header.php';
include 'config.php';

// Create uploads directory if it doesn't exist
$upload_dir = "uploads";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_GET['id'])) {
    $crime_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM crime_register WHERE Crime_Id = '$crime_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header("Location: search.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $crime_id = mysqli_real_escape_string($conn, $_POST['crime_id']);
    $head_id = mysqli_real_escape_string($conn, $_POST['head_id']);
    $date_offence = mysqli_real_escape_string($conn, $_POST['date_offence']);
    $under_section = mysqli_real_escape_string($conn, $_POST['under_section']);
    $date_report = mysqli_real_escape_string($conn, $_POST['date_report']);
    $case_status = mysqli_real_escape_string($conn, $_POST['case_status']);
    $arrested = mysqli_real_escape_string($conn, $_POST['arrested']);
    $challan = mysqli_real_escape_string($conn, $_POST['challan']);
    $officer_id = mysqli_real_escape_string($conn, $_POST['officer_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $cnic = mysqli_real_escape_string($conn, $_POST['cnic']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Initialize img_path
    $img_path = $row['img']; // Default to current image

    // Handle image upload if new image is provided
    if (!empty($_FILES['img']['name'])) {
        $file_extension = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension; // Generate unique filename
        $target_file = $upload_dir . '/' . $new_filename;

        // Check if file is an actual image
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check !== false) {
            // Try to move the uploaded file
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                // Delete old image if it exists and is different from default
                if ($row['img'] && file_exists($row['img']) && $row['img'] != 'uploads/default.jpg') {
                    unlink($row['img']);
                }
                $img_path = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    if (!isset($error)) {
        $update_query = "UPDATE crime_register SET 
            Head_id = '$head_id',
            Date_of_Offence = '$date_offence',
            Under_Section = '$under_section',
            Date_of_Report = '$date_report',
            Case_status = '$case_status',
            Arrested = '$arrested',
            Challan = '$challan',
            Officer_id = '$officer_id',
            Name = '$name',
            Father_name = '$father_name',
            CNIC = '$cnic',
            Gender = '$gender',
            img = '$img_path'
            WHERE Crime_Id = '$crime_id'";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_msg'] = "Record updated successfully!";
            header("Location: search.php");
            exit();
        } else {
            $error = "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Criminal Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .current-image {
            max-width: 200px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="form-card">
            <h2><i class="fas fa-edit"></i> Update Criminal Record</h2>
            <hr>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="crime_id" value="<?php echo $row['Crime_Id']; ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Current Image</label><br>
                            <img src="<?php echo $row['img']; ?>" class="current-image">
                            <input type="file" class="form-control" name="img">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Head ID</label>
                            <input type="text" class="form-control" name="head_id"
                                value="<?php echo $row['Head_id']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date of Offence</label>
                            <input type="date" class="form-control" name="date_offence"
                                value="<?php echo $row['Date_of_Offence']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Under Section</label>
                            <input type="text" class="form-control" name="under_section"
                                value="<?php echo $row['Under_Section']; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date of Report</label>
                            <input type="date" class="form-control" name="date_report"
                                value="<?php echo $row['Date_of_Report']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Case Status</label>
                            <select class="form-control" name="case_status" required>
                                <option value="Pending" <?php echo $row['Case_status'] == 'Pending' ? 'selected' : ''; ?>>
                                    Pending</option>
                                <option value="Closed" <?php echo $row['Case_status'] == 'Closed' ? 'selected' : ''; ?>>
                                    Closed</option>
                                <option value="Under Investigation" <?php echo $row['Case_status'] == 'Under Investigation' ? 'selected' : ''; ?>>Under
                                    Investigation</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Arrested</label>
                            <select class="form-control" name="arrested" required>
                                <option value="Yes" <?php echo $row['Arrested'] == 'Yes' ? 'selected' : ''; ?>>Yes
                                </option>
                                <option value="No" <?php echo $row['Arrested'] == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Challan</label>
                            <input type="text" class="form-control" name="challan"
                                value="<?php echo $row['Challan']; ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Officer ID</label>
                            <input type="text" class="form-control" name="officer_id"
                                value="<?php echo $row['Officer_id']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $row['Name']; ?>"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="father_name"
                                value="<?php echo $row['Father_name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CNIC</label>
                            <input type="text" class="form-control" name="cnic" value="<?php echo $row['CNIC']; ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="gender" required>
                                <option value="Male" <?php echo $row['Gender'] == 'Male' ? 'selected' : ''; ?>>Male
                                </option>
                                <option value="Female" <?php echo $row['Gender'] == 'Female' ? 'selected' : ''; ?>>Female
                                </option>
                                <option value="Other" <?php echo $row['Gender'] == 'Other' ? 'selected' : ''; ?>>Other
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Record
                    </button>
                    <a href="search.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php include 'footer.php'; ?>