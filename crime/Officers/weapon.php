<?php include 'header.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Criminal Management System - Home</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .table-responsive {
            margin: 20px;
        }
        table {
            width: 30%; /* Adjusted width to make the table smaller */
            margin: auto;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light"></nav>
        <a class="navbar-brand" href="#"><img src="police_logo.png" width="50"></a>
        <span class="navbar-text">Criminal Management System</span>
        <div class="ml-auto">
            <a href="logout.php" class="btn btn-danger">Log out</a>
        </div>
    </nav>
    
    <div class="table-responsive" style="width: 20%;"></div>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Officer ID</th>
                    <th>Officer Name</th>
                    <th>Weapon</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $servername = "localhost";
                    $username= "root";
                    $password= "";
                    $db = "criminalinfo";
                    $conn = mysqli_connect($servername,$username,$password,$db);
                    $q1 = "SELECT * FROM `officer`";
                    $result = mysqli_query($conn,$q1);
                    if($result){
                        while($row=mysqli_fetch_array($result)){
                            echo'
                            <tr>
                                <td>'. $row['offID'].'</td>
                                <td>'.$row['offName'].'</td>
                                <td>'. $row['weapon'].'</td>
                                <td>'.$row['role'].'</td>
                            </tr>';
                        }   
                    }
                    else{
                        echo"<tr><td colspan='4'>Error retrieving data</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
