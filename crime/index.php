<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Department Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --police-blue: #1a3a85;
            --police-gold: #c4a777;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ef 100%);
        }

        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
        }

        .system-title {
            color: var(--police-blue);
            font-weight: 600;
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .btn {
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--police-blue);
            color: white;
        }

        .btn-primary:hover {
            background: #234aa8;
        }

        .btn-secondary {
            background: #2c3e50;
            color: white;
        }

        .btn-secondary:hover {
            background: #34495e;
        }

        .btn-dark {
            background: #1a1a1a;
            color: var(--police-gold);
        }

        .btn-dark:hover {
            background: #2c2c2c;
            color: var(--police-gold);
        }

        .btn i {
            color: var(--police-gold);
            margin-right: 10px;
            font-size: 1.2em;
        }

        .copyright {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .badge-police {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--police-gold);
            color: var(--police-blue);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body text-center p-5">
                        <div class="badge-police">Official Portal</div>
                        <h1 class="system-title mt-4">Police Department Management System</h1>

                        <form method="post">
                            <div class="d-grid gap-4">
                                <a href="login1.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-database"></i>
                                    Criminal Records System
                                </a>
                                <a href="./Officers/login2.php" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-user-shield"></i>
                                    Police Officer Portal
                                </a>
                                <a href="./adminpanel/src/html/login.php" class="btn btn-dark btn-lg">
                                    <i class="fas fa-user-cog"></i>
                                    Administrator Login
                                </a>
                            </div>
                        </form>

                        <div class="mt-5">
                            <p class="copyright mb-0">© 2024 Police Department Management System</p>
                            <small class="text-muted">Secure Access Portal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php

if (isset($_POST['btn1'])) {
    header("location: login1.php");
} else if (isset($_POST['btn2'])) {
    header("location: login2.php");
}
?>