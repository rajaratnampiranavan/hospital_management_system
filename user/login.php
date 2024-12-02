<?php
include('../database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nic_number = $_POST['nic_number'];
    $phone_number = $_POST['phone_number'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE nic_number=? AND phone_number=?");
    $stmt->bind_param("ss", $nic_number, $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: profile.php');
        exit(); // Make sure to exit after header redirection
    } else {
        echo "Invalid NIC number or phone number";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>User Login</h1>
        <form class="shadow w-450 p-3" method="post" action="">
            <h4 class="display-4 fs-1">LOGIN</h4><br>
            <div class="mb-3">
                <label class="form-label" for="nic_number">NIC Number:</label>
                <input type="text" class="form-control" id="nic_number" name="nic_number" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary" type="submit">Login</button>
                <a href="register.php" class="btn btn-secondary">Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>
