<?php
include('../database/config.php');
session_start();

$sql = "SELECT * FROM hospitals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospitals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .hospital-list {
            list-style-type: none;
            padding: 0;
        }
        .hospital-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .hospital-item h3 {
            margin: 0;
            color: #007bff;
        }
        .hospital-item p {
            margin: 5px 0;
            color: #555;
        }
        .btn-back {
            display: block;
            width: 100%;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hospitals List</h1>
        <ul class="hospital-list">
            <?php while ($hospital = $result->fetch_assoc()): ?>
                <li class="hospital-item">
                    <h3><?php echo htmlspecialchars($hospital['name']); ?></h3>
                    <p><?php echo htmlspecialchars($hospital['address']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($hospital['phone']); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="profile.php" class="btn btn-primary btn-back">Back to Profile</a>
    </div>
</body>
</html>

