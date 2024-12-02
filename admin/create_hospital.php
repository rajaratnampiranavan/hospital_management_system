<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    // Handle the uploaded photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
    } else {
        $photo = null;
    }

    // Prepare SQL statement with photo
    $stmt = $conn->prepare("INSERT INTO hospitals (username, password, name, address, phone, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssb', $username, $password, $name, $address, $phone, $photo);
    
    if ($stmt->execute()) {
        echo "<p class='success-message'>New hospital created successfully</p>";
    } else {
        echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Hospital Account</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f9f9f9;
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
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: #28a745;
        }
        .error-message {
            color: #dc3545;
        }
        .btn-container {
            margin-top: 20px;
        }
        .btn-back {
            background-color: #6c757d;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>Create Hospital Account</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="name">Hospital Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo">
        <button type="submit">Create</button>
        
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
   
    </form>
    
</body>
</html>
