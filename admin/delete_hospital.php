<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_id = $_POST['id'];

    $sql = "DELETE FROM hospitals WHERE id=$hospital_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success-message'>Hospital deleted successfully</p>";
    } else {
        echo "<p class='error-message'>Error: " . $conn->error . "</p>";
    }
}

$sql = "SELECT * FROM hospitals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Hospital Account</title>
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
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #c82333;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 20px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 20px;
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
    <h1>Delete Hospital Account</h1>
    <form method="post" action="">
        <label for="id">Select Hospital:</label>
        <select id="id" name="id" required>
            <?php while ($hospital = $result->fetch_assoc()): ?>
                <option value="<?php echo $hospital['id']; ?>">
                    <?php echo htmlspecialchars($hospital['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Delete</button>
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </form>
   
</body>
</html>
