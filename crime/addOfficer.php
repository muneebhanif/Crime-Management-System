<?php
session_start();
include 'header.php';
include 'session.php';
?>

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
    <div class="container-fluid px-4">
        <div class="case-header">
            <h2><i class="fas fa-user-plus"></i> Add New Officer</h2>
        </div>

        <div class="officer-form">
            <form id="offInfo" method="post" onsubmit="return submitBtn()">
                <!-- Personal Information -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="offName" class="form-label required">Officer's Name</label>
                            <input type="text" class="form-control" name="offName" id="offName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="offID" class="form-label required">Officer's ID</label>
                            <input type="text" class="form-control" name="offID" id="offID" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact" class="form-label required">Contact Number</label>
                            <input type="tel" class="form-control" name="contact" id="contact" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Gender</label>
                            <div class="gender-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="M" id="male"
                                        required>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="F" id="female">
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment Information -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-briefcase"></i> Assignment Information
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ID" class="form-label required">Assigned Case ID</label>
                            <input type="text" class="form-control" name="ID" id="ID" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label required">Role</label>
                            <select class="form-select" name="role" id="role" required>
                                <option value="">--Select role of officer--</option>
                                <option value="Sr.PI">Sr.PI (Senior Police Inspector)</option>
                                <option value="API">API (Asst. Police Inspector)</option>
                                <option value="PSI">PSI (Police Sub-Inspector)</option>
                                <option value="HC">HC (Head Constable)</option>
                                <option value="C">Constable</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="weapon" class="form-label required">Weapon Assignment</label>
                            <select class="form-select" name="weapon" id="weapon" required>
                                <option value="">--Select weapon assigned to officer--</option>
                                <option value="M4">M4</option>
                                <option value="M107">M107</option>
                                <option value="Smith and Wesson M&P">Smith and Wesson M&P</option>
                                <option value="Glock Pistol">Glock Pistol</option>
                                <option value="Pistol Auto 9mm 1A">Pistol Auto 9mm 1A</option>
                                <option value="MP5 German Automatic Sub-machine Gun">MP5 German Automatic Sub-machine
                                    Gun</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-section">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>Add Officer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function submitBtn() {
            const contact = document.forms["offInfo"]["contact"].value;
            if (contact.length !== 10 || contact.charAt(0) <= '5') {
                alert('Please enter a valid contact number');
                return false;
            }
            return true;
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'config.php';

        $offName = $_POST['offName'];
        $offID = $_POST['offID'];
        $ID = $_POST['ID'];
        $contact = $_POST['contact'];
        $gender = $_POST['gender'];
        $weapon = $_POST['weapon'];
        $role = $_POST['role'];

        $q1 = "INSERT INTO `officer`(`offName`, `offID`, `ID`, `contact`, `gender`, `weapon`, `role`) 
               VALUES ('$offName','$offID','$ID','$contact','$gender','$weapon','$role')";

        if (mysqli_query($conn, $q1)) {
            echo "<script>alert('Officer added successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
    ?>

</body>

</html>

<?php include 'footer.php'; ?>