<?php
session_start();
include 'session.php';

include 'header.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Police Case Management System - New Case Entry</title>
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

      .case-form {
         background: white;
         border-radius: 10px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
         margin-bottom: 30px;
         overflow: hidden;
      }

      .form-section {
         border-bottom: 1px solid #eee;
         padding: 20px;
         margin-bottom: 20px;
      }

      .form-section-title {
         color: var(--police-blue);
         font-weight: 600;
         margin-bottom: 20px;
         padding-bottom: 10px;
         border-bottom: 2px solid var(--police-gold);
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
      }

      .btn-submit:hover {
         background: #152d69;
         transform: translateY(-1px);
      }

      .image-preview {
         border: 2px dashed #ddd;
         border-radius: 8px;
         padding: 20px;
         text-align: center;
         margin-bottom: 20px;
      }

      .required-field::after {
         content: '*';
         color: red;
         margin-left: 4px;
      }

      .form-floating label {
         padding-left: 15px;
      }

      .radio-group {
         display: flex;
         gap: 20px;
         padding: 10px 0;
      }
   </style>
</head>

<body>
   <div class="container-fluid px-4">
      <div class="case-header">
         <h2><i class="fas fa-file-alt"></i> New Case Registration / نیا کیس رجسٹریشن</h2>
      </div>

      <div class="case-form">
         <form name="crimeInfo" method="post" enctype="multipart/form-data" onsubmit="return submitBtn()">
            <!-- Criminal Details Section -->
            <div class="form-section">
               <h4 class="form-section-title"><i class="fas fa-user-alt me-2"></i>Criminal Details / مجرم کی تفصیلات
               </h4>
               <div class="row g-3">
                  <div class="col-md-6">
                     <div class="image-preview">
                        <label for="my_img" class="form-label required-field">Criminal's Image / مجرم کی تصویر</label>
                        <input type="file" name="my_img" class="form-control" id="my_img" required>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <label for="Name" class="form-label required-field">Criminal Name / مجرم کا نام</label>
                     <input type="text" name="Name" class="form-control" id="Name" required>
                  </div>
                  <div class="col-md-6">
                     <label for="Father_name" class="form-label required-field">Father's Name / والد کا نام</label>
                     <input type="text" name="Father_name" class="form-control" id="Father_name" required>
                  </div>
                  <div class="col-md-6">
                     <label for="CNIC" class="form-label required-field">CNIC / شناختی کارڈ</label>
                     <input type="text" name="CNIC" class="form-control" id="CNIC" required>
                  </div>
               </div>
            </div>

            <!-- Case Details Section -->
            <div class="form-section">
               <h4 class="form-section-title"><i class="fas fa-gavel me-2"></i>Case Details / کیس کی تفصیلات</h4>
               <div class="row g-3">
                  <div class="col-md-6">
                     <label for="Officer_id" class="form-label required-field">Officer ID / افسر کی شناخت</label>
                     <input type="text" name="Officer_id" class="form-control" id="Officer_id" required>
                  </div>
                  <div class="col-md-6">
                     <label for="Under_Section" class="form-label required-field">Under Section / سیکشن کے تحت</label>
                     <input type="text" name="Under_Section" class="form-control" id="Under_Section" required>
                  </div>
                  <div class="col-md-6">
                     <label for="Date_of_Offence" class="form-label required-field">Date of Offence / جرم کی
                        تاریخ</label>
                     <input type="date" name="Date_of_Offence" class="form-control" id="Date_of_Offence" required>
                  </div>
                  <div class="col-md-6">
                     <label for="Date_of_Report" class="form-label required-field">Date of Report / رپورٹ کی
                        تاریخ</label>
                     <input type="date" name="Date_of_Report" class="form-control" id="Date_of_Report" required>
                  </div>
               </div>
            </div>

            <!-- Status Section -->
            <div class="form-section">
               <h4 class="form-section-title"><i class="fas fa-info-circle me-2"></i>Status Information / حیثیت کی
                  معلومات</h4>
               <div class="row g-3">
                  <div class="col-md-4">
                     <label for="Case_status" class="form-label required-field">Case Status / کیس کی حیثیت</label>
                     <select name="Case_status" class="form-select" id="Case_status" required>
                        <option value="">--Select Status / حیثیت منتخب کریں--</option>
                        <option value="Open">Open / کھلا</option>
                        <option value="Closed">Closed / بند</option>
                        <option value="Under Investigation">Under Investigation / زیر تفتیش</option>
                     </select>
                  </div>
                  <div class="col-md-4">
                     <label for="Arrested" class="form-label required-field">Arrested / گرفتار</label>
                     <select name="Arrested" class="form-select" id="Arrested" required>
                        <option value="">--Select Status / حیثیت منتخب کریں--</option>
                        <option value="Yes">Yes / ہاں</option>
                        <option value="No">No / نہیں</option>
                     </select>
                  </div>
                  <div class="col-md-4">
                     <label for="Challan" class="form-label required-field">Challan / چالان</label>
                     <input type="text" name="Challan" class="form-control" id="Challan" required>
                  </div>
                  <div class="col-12">
                     <label class="form-label required-field">Gender / جنس</label>
                     <div class="radio-group">
                        <div class="form-check">
                           <input type="radio" name="Gender" value="M" class="form-check-input" id="male" required>
                           <label class="form-check-label" for="male">Male / مرد</label>
                        </div>
                        <div class="form-check">
                           <input type="radio" name="Gender" value="F" class="form-check-input" id="female">
                           <label class="form-check-label" for="female">Female / عورت</label>
                        </div>
                        <div class="form-check">
                           <input type="radio" name="Gender" value="O" class="form-check-input" id="others">
                           <label class="form-check-label" for="others">Others / دیگر</label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Accused Section -->
            <div class="form-section">
               <h4 class="form-section-title"><i class="fas fa-user-minus me-2"></i>Accused Information / ملزم کی
                  معلومات</h4>
               <div class="row g-3">
                  <div class="col-md-6">
                     <label for="Accussed_CNIC" class="form-label">CNIC / شناختی کارڈ</label>
                     <input type="text" class="form-control" name="Accussed_CNIC" id="Accussed_CNIC"
                        pattern="\d{5}-\d{7}-\d" placeholder="12345-1234567-1">
                  </div>
                  <div class="col-md-6">
                     <label for="AccusedName" class="form-label">Name / نام</label>
                     <input type="text" class="form-control" name="AccusedName" id="AccusedName">
                  </div>
                  <div class="col-md-6">
                     <label for="AccusedFatherName" class="form-label">Father's Name / والد کا نام</label>
                     <input type="text" class="form-control" name="AccusedFatherName" id="AccusedFatherName">
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Gender / جنس</label>
                     <div class="radio-group">
                        <div class="form-check">
                           <input type="radio" name="AccusedGender" value="M" class="form-check-input" id="accusedMale">
                           <label class="form-check-label" for="accusedMale">Male / مرد</label>
                        </div>
                        <div class="form-check">
                           <input type="radio" name="AccusedGender" value="F" class="form-check-input"
                              id="accusedFemale">
                           <label class="form-check-label" for="accusedFemale">Female / عورت</label>
                        </div>
                        <div class="form-check">
                           <input type="radio" name="AccusedGender" value="O" class="form-check-input"
                              id="accusedOther">
                           <label class="form-check-label" for="accusedOther">Other / دیگر</label>
                        </div>
                     </div>
                  </div>
                  <div class="col-12">
                     <label for="AccusedRemarks" class="form-label">Remarks / تبصرہ</label>
                     <textarea class="form-control" name="AccusedRemarks" id="AccusedRemarks" rows="2"></textarea>
                  </div>
               </div>
            </div>

            <!-- Witness Section -->
            <div class="form-section">
               <h4 class="form-section-title"><i class="fas fa-user-friends me-2"></i>Witness Information / گواہ کی
                  معلومات</h4>
               <div class="row g-3">
                  <div class="col-md-6">
                     <label for="Witness_CNIC" class="form-label">CNIC / شناختی کارڈ</label>
                     <input type="text" class="form-control" name="Witness_CNIC" id="Witness_CNIC"
                        pattern="\d{5}-\d{7}-\d" placeholder="12345-1234567-1">
                  </div>
                  <div class="col-md-6">
                     <label for="WitnessName" class="form-label">Name / نام</label>
                     <input type="text" class="form-control" name="WitnessName" id="WitnessName">
                  </div>
                  <div class="col-md-6">
                     <label for="WitnessFatherName" class="form-label">Father's Name / والد کا نام</label>
                     <input type="text" class="form-control" name="WitnessFatherName" id="WitnessFatherName">
                  </div>
                  <div class="col-md-6">
                     <label for="WitnessContact" class="form-label">Contact / رابطہ نمبر</label>
                     <input type="tel" class="form-control" name="WitnessContact" id="WitnessContact">
                  </div>
                  <div class="col-12">
                     <label for="WitnessRemarks" class="form-label">Remarks / تبصرہ</label>
                     <textarea class="form-control" name="WitnessRemarks" id="WitnessRemarks" rows="2"></textarea>
                  </div>
               </div>
            </div>

            <div class="form-section">
               <div class="d-grid">
                  <button type="submit" class="btn btn-submit" name="submit">
                     <i class="fas fa-save me-2"></i>Register Case / کیس رجسٹر کریں
                  </button>
               </div>
            </div>
         </form>
      </div>
   </div>

   <?php
   if (isset($_POST['submit'])) {
      $errors = [];

      // Check required fields
      $requiredFields = [
         'Name' => 'Criminal Name',
         'Father_name' => 'Father\'s Name',
         'CNIC' => 'CNIC',
         'Officer_id' => 'Officer ID',
         'Under_Section' => 'Under Section',
         'Date_of_Offence' => 'Date of Offence',
         'Date_of_Report' => 'Date of Report',
         'Case_status' => 'Case Status',
         'Arrested' => 'Arrested',
         'Challan' => 'Challan',
         'Gender' => 'Gender'
      ];

      foreach ($requiredFields as $field => $label) {
         if (empty($_POST[$field])) {
            $errors[] = "Please fill out the $label field.";
         }
      }

      $dateOffence = strtotime($_POST['Date_of_Offence']);
      $currentYear = date('Y');

      if (date('Y', $dateOffence) > $currentYear) {
         $errors[] = 'Invalid Date of Offence';
      }

      $cnicPattern = '/^\d{5}-\d{7}-\d$/';

      if (!empty($_POST['Accussed_CNIC']) && !preg_match($cnicPattern, $_POST['Accussed_CNIC'])) {
         $errors[] = 'Please enter a valid Accused CNIC format (e.g., 12345-1234567-1)';
      }

      if (!empty($_POST['Witness_CNIC']) && !preg_match($cnicPattern, $_POST['Witness_CNIC'])) {
         $errors[] = 'Please enter a valid Witness CNIC format (e.g., 12345-1234567-1)';
      }

      if (!empty($errors)) {
         foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
         }
      } else {
         $fname = $_FILES['my_img']['name'];
         $tmpname = $_FILES['my_img']['tmp_name'];
         $folder = "images/" . $fname;
         move_uploaded_file($tmpname, $folder);

         $fields = [
            'Date_of_Offence',
            'Under_Section',
            'Date_of_Report',
            'Case_status',
            'Arrested',
            'Challan',
            'Officer_id',
            'Name',
            'Father_name',
            'CNIC',
            'Gender'
         ];

         $values = array_map(function ($field) {
            return "'" . $_POST[$field] . "'";
         }, $fields);

         $q1 = "INSERT INTO crime_register (" . implode(", ", $fields) . ", img) 
                  VALUES (" . implode(", ", $values) . ", '$folder')";

         if (mysqli_query($conn, $q1)) {
            echo "<script>alert('Record inserted successfully!');</script>";
         } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
         }

         if (!empty($_POST['Accussed_CNIC'])) {
            $accused_cnic = mysqli_real_escape_string($conn, $_POST['Accussed_CNIC']);
            $accused_name = mysqli_real_escape_string($conn, $_POST['AccusedName']);
            $accused_father = mysqli_real_escape_string($conn, $_POST['AccusedFatherName']);
            $accused_gender = mysqli_real_escape_string($conn, $_POST['AccusedGender']);
            $accused_remarks = mysqli_real_escape_string($conn, $_POST['AccusedRemarks']);

            $accused_query = "INSERT INTO accussed (Accussed_CNIC, Name, Father_name, Gender, Remarks, crime_id) 
                             VALUES ('$accused_cnic', '$accused_name', '$accused_father', 
                                    '$accused_gender', '$accused_remarks', LAST_INSERT_ID())";
            mysqli_query($conn, $accused_query);
         }

         if (!empty($_POST['Witness_CNIC'])) {
            $witness_cnic = mysqli_real_escape_string($conn, $_POST['Witness_CNIC']);
            $witness_name = mysqli_real_escape_string($conn, $_POST['WitnessName']);
            $witness_father = mysqli_real_escape_string($conn, $_POST['WitnessFatherName']);
            $witness_contact = mysqli_real_escape_string($conn, $_POST['WitnessContact']);
            $witness_remarks = mysqli_real_escape_string($conn, $_POST['WitnessRemarks']);

            $witness_query = "INSERT INTO witness (Witness_CNIC, Name, Father_name, Contact, Remarks, crime_id) 
                             VALUES ('$witness_cnic', '$witness_name', '$witness_father', 
                                    '$witness_contact', '$witness_remarks', LAST_INSERT_ID())";
            mysqli_query($conn, $witness_query);
         }
      }
   }
   ?>
</body>

</html>

<?php include 'footer.php'; ?>