<?php
include('../database/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather form data
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $blood = $_POST['blood'];
    $allergies = $_POST['allergies'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $nic_number = $_POST['nic_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];

    // Check if phone number or NIC number already exists
    $check_sql = "SELECT * FROM users WHERE phone_number = '$phone_number' OR nic_number = '$nic_number'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Phone number or NIC number already registered
        echo "<script>alert('Phone number or NIC number already registered. Please try again.'); window.location.href = 'register.php';</script>";
    } else {
        // Insert into database
        $sql = "INSERT INTO users (username, fullname, blood, allergies, phone_number, date_of_birth, address, nic_number, email, gender, civil_status)
                VALUES ('$username', '$fullname', '$blood', '$allergies', '$phone_number', '$date_of_birth', '$address', '$nic_number', '$email', '$gender', '$civil_status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful'); window.location.href = 'login.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin-top: 200px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 800px;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            margin-right: 10px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group input[type="email"], 
        .form-group textarea, 
        .form-group select {
            flex: 2;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 30%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Style the anchor tag as a button */
        .btn-secondary-link {
            padding: 10px;
            background-color: #6c757d;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 30%;
        }

        .btn-secondary-link:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div>
        <h1>User Registration</h1>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="form-group">
                <label for="blood">Blood Type:</label>
                <input type="text" id="blood" name="blood">
            </div>

            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <input type="text" id="allergies" name="allergies">
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="nic_number">NIC Number:</label>
                <input type="text" id="nic_number" name="nic_number" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="civil_status">Civil Status:</label>
                <select id="civil_status" name="civil_status" required>
                    <option value="">Select Civil Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit">Register</button>
                <a href="login.php" class="btn-secondary-link">Login</a>
            </div>
        </form>
    </div>
</body>
</html>

