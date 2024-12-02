<?php
include('../database/config.php');
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Query for user information
$sql = "SELECT username, fullname, blood, allergies, date_of_birth, address, nic_number, email, phone_number, gender, civil_status
        FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Query for patient details
$sql_details = "SELECT entry_date, exit_date, treatment_notes, prescribed_pills, dosage
                FROM patient_details WHERE user_id = $user_id";
$result_details = $conn->query($sql_details);
$patient_details = $result_details->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
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
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .user-info p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }
        .table {
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-group {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
            <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($user['blood']); ?></p>
            <p><strong>Allergies:</strong> <?php echo htmlspecialchars($user['allergies']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['date_of_birth']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>NIC Number:</strong> <?php echo htmlspecialchars($user['nic_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($user['civil_status']); ?></p>
        </div>

        <h2>Patient Details</h2>
        <?php if (count($patient_details) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Entry Date and Time</th>
                        <th>Exit Date and Time</th>
                        <th>Treatment Notes</th>
                        <th>Prescribed Pills</th>
                        <th>Dosage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patient_details as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['entry_date']); ?></td>
                            <td><?php echo htmlspecialchars($detail['exit_date']); ?></td>
                            <td><?php echo htmlspecialchars($detail['treatment_notes']); ?></td>
                            <td><?php echo htmlspecialchars($detail['prescribed_pills']); ?></td>
                            <td><?php echo htmlspecialchars($detail['dosage']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patient details available.</p>
        <?php endif; ?>

        <div class="btn-group">
            <a href="view_hospitals.php" class="btn btn-primary">View Hospitals</a>
            <a href="edit_profile.php" class="btn btn-secondary">Edit Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
