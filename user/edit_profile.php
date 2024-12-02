<?php
include('../database/config.php');
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $blood = $_POST['blood'];
    $allergies = $_POST['allergies'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $nic_number = $_POST['nic_number'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];

    // Validate the input (for debugging)
    if (empty($username) || empty($fullname) || empty($date_of_birth) || empty($address) || empty($nic_number) || empty($email) || empty($phone_number)) {
        echo "All fields are required.";
    } else {
        // Prepare and execute the update query
        $sql = "UPDATE users SET username=?, fullname=?, blood=?, allergies=?, date_of_birth=?, address=?, nic_number=?, email=?, phone_number=?, gender=?, civil_status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssssssi", $username, $fullname, $blood, $allergies, $date_of_birth, $address, $nic_number, $email, $phone_number, $gender, $civil_status, $user_id);
            if ($stmt->execute()) {
                // Redirect back to profile page on success
                header('Location: profile.php');
                exit;
            } else {
                echo "Failed to update profile.";
            }
        } else {
            echo "Failed to prepare statement.";
        }
    }
}

// Fetch user data
$sql = "SELECT username, fullname, blood, allergies, date_of_birth, address, nic_number, email, phone_number, gender, civil_status FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="../css/styles.css">
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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-back {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            color: #007bff;
            text-decoration: none;
        }
        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="blood">Blood Type:</label>
                <input type="text" id="blood" name="blood" value="<?php echo htmlspecialchars($user['blood']); ?>">
            </div>
            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <input type="text" id="allergies" name="allergies" value="<?php echo htmlspecialchars($user['allergies']); ?>">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="nic_number">NIC Number:</label>
                <input type="text" id="nic_number" name="nic_number" value="<?php echo htmlspecialchars($user['nic_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="Male" <?php echo $user['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $user['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="civil_status">Civil Status:</label>
                <select id="civil_status" name="civil_status">
                    <option value="Single" <?php echo $user['civil_status'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                    <option value="Married" <?php echo $user['civil_status'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                    <option value="Divorced" <?php echo $user['civil_status'] === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                    <option value="Widowed" <?php echo $user['civil_status'] === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                </select>
            </div>
            <button type="submit">Update Profile</button>
            <a href="profile.php" class="btn-back">Back to Profile</a>
        </form>
    </div>
</body>
</html>
