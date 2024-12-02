<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .dashboard-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dashboard-links a {
            display: block;
            background-color: #007bff;
            color: #fff;
            padding: 15px 25px;
            margin: 10px 0;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a:hover {
            background-color: #0056b3;
        }
        .dashboard-links a:last-child {
            background-color: #dc3545;
        }
        .dashboard-links a:last-child:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <div class="dashboard-links">
        <a href="create_hospital.php">Create Hospital Account</a>
        <a href="modify_hospital.php">Modify Hospital Account</a>
        <a href="delete_hospital.php">Delete Hospital Account</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
