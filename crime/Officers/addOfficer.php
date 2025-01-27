<?php
ob_start();
session_start();
include '../config.php';
include 'header.php';
require_once 'Validator.php';

if (isset($_POST['submit'])) {
    $validator = new Validator();

    $validation_rules = [
        'NGO_id' => 'required|alphanumeric',
        'Name' => 'required|alphanumeric',
        'Fname' => 'required|alphanumeric',
        'Surname' => 'required|alphanumeric',
        'NGO_code' => 'required|alphanumeric',
        'Res_phone' => 'required|phone',
        'D_O_B' => 'required|date|age',
        'Qualification' => 'required'
    ];

    $error = $validator->validate($_POST, $validation_rules);

    if ($error != '') {
        $_SESSION['error_message'] = "Validation failed:\n" . $error;
    } else {
        // Sanitize inputs
        $ngo_id = mysqli_real_escape_string($conn, $_POST['NGO_id']);
        $name = mysqli_real_escape_string($conn, $_POST['Name']);
        $fname = mysqli_real_escape_string($conn, $_POST['Fname']);
        $surname = mysqli_real_escape_string($conn, $_POST['Surname']);
        $ngo_code = mysqli_real_escape_string($conn, $_POST['NGO_code']);
        $per_address = mysqli_real_escape_string($conn, $_POST['Per_address']);
        $res_phone = mysqli_real_escape_string($conn, $_POST['Res_phone']);
        $dob = mysqli_real_escape_string($conn, $_POST['D_O_B']);
        $qualification = mysqli_real_escape_string($conn, $_POST['Qualification']);

        $query = "INSERT INTO officer_record (NGO_id, Name, Fname, Surname, NGO_code, Per_address, Res_phone, D_O_B, Qualification) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Use prepared statement
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssss",
            $ngo_id,
            $name,
            $fname,
            $surname,
            $ngo_code,
            $per_address,
            $res_phone,
            $dob,
            $qualification
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Officer added successfully! / افسر کامیابی سے شامل کر دیا گیا";
            header("Location: ofc_record.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error / خرابی: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!-- Add this after your header but before the form -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php
        echo nl2br(htmlspecialchars($_SESSION['error_message']));
        unset($_SESSION['error_message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Case Management System - Add Officer</title>
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

        .officer-form {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .form-section {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }

        .form-section-title {
            color: var(--police-blue);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--police-gold);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--police-blue);
            box-shadow: 0 0 0 0.2rem rgba(26, 58, 133, 0.15);
        }

        .form-select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
        }

        .form-check-input:checked {
            background-color: var(--police-blue);
            border-color: var(--police-blue);
        }

        .btn-submit {
            background: var(--police-blue);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background: #152d69;
            transform: translateY(-1px);
        }

        .gender-group {
            display: flex;
            gap: 20px;
            padding: 10px 0;
        }

        .required::after {
            content: '*';
            color: red;
            margin-left: 4px;
        }
    </style>
</head>

<body>

    '
    <div class="container-fluid px-4">
        <div class="case-header">
            <h2><i class="fas fa-user-plus"></i> Add New Officer / نیا افسر شامل کریں</h2>
        </div>

        <div class="officer-form">
            <form id="offInfo" method="post" onsubmit="return validateForm()">
                <!-- Personal Information -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-user"></i> Personal Information / ذاتی معلومات
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="NGO_id" class="form-label required">NGO ID / این جی او آئی ڈی</label>
                            <input type="text" class="form-control" name="NGO_id" id="NGO_id" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Name" class="form-label required">Officer's Name / افسر کا نام</label>
                            <input type="text" class="form-control" name="Name" id="Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Fname" class="form-label required">Father's Name / والد کا نام</label>
                            <input type="text" class="form-control" name="Fname" id="Fname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Surname" class="form-label required">Surname / خاندانی نام</label>
                            <input type="text" class="form-control" name="Surname" id="Surname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="NGO_code" class="form-label required">NGO Code / این جی او کوڈ</label>
                            <input type="text" class="form-control" name="NGO_code" id="NGO_code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Res_phone" class="form-label required">Contact Number / رابطہ نمبر</label>
                            <input type="tel" class="form-control" name="Res_phone" id="Res_phone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="D_O_B" class="form-label required">Date of Birth / تاریخ پیدائش</label>
                            <input type="date" class="form-control" name="D_O_B" id="D_O_B" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Qualification" class="form-label required">Qualification / تعلیمی قابلیت</label>
                            <select class="form-select" name="Qualification" id="Qualification" required>
                                <option value="">--Select Qualification / قابلیت منتخب کریں--</option>
                                <option value="Bachelors">Bachelors / بیچلرز</option>
                                <option value="Masters">Masters / ماسٹرز</option>
                                <option value="PhD">PhD / پی ایچ ڈی</option>
                                <option value="Other">Other / دیگر</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="Per_address" class="form-label required">Permanent Address / مستقل پتہ</label>
                            <textarea class="form-control" name="Per_address" id="Per_address" rows="3"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-section">
                    <button type="submit" class="btn btn-submit" name="submit">
                        <i class="fas fa-save me-2"></i>Add Officer / افسر شامل کریں
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // function validateForm() {
        //     const phone = document.forms["offInfo"]["Res_phone"].value;
        //     if (phone.length !== 10 || phone.charAt(0) <= '5') {
        //         alert('Please enter a valid contact number\nبراہ کرم درست رابطہ نمبر درج کریں');
        //         return false;
        //     }

        //     const dob = new Date(document.forms["offInfo"]["D_O_B"].value);
        //     const today = new Date();
        //     const age = today.getFullYear() - dob.getFullYear();

        //     if (age < 18 || age > 60) {
        //         alert('Officer age must be between 18 and 60 years\nافسر کی عمر 18 سے 60 سال کے درمیان ہونی چاہیے');
        //         return false;
        //     }

        //     return true;
        // }
    </script>

</body>

</html>

<?php include '../footer.php'; ?>