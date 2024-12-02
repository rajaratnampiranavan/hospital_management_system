<?php
// Include database configuration
include('../database/config.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

$hospital_id = $_SESSION['hospital_id'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['tmp_name']) {
        $photo = $_FILES['photo']['tmp_name'];
        $photoData = addslashes(file_get_contents($photo));
        $sql = "UPDATE hospitals SET name='$name', address='$address', phone='$phone', photo='$photoData' WHERE id=$hospital_id";
    } else {
        $sql = "UPDATE hospitals SET name='$name', address='$address', phone='$phone' WHERE id=$hospital_id";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success text-center'>Profile updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
    }
}

// Fetch hospital data
$sql = "SELECT * FROM hospitals WHERE id=$hospital_id";
$result = $conn->query($sql);
$hospital = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Hospital Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
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
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Hospital Profile</h1>
        <?php echo $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="form-label">Hospital Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($hospital['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($hospital['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($hospital['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" id="photo" name="photo" class="form-control">
                <?php if (!empty($hospital['photo'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($hospital['photo']); ?>" alt="Hospital Photo" class="img-thumbnail mt-2" width="100">
                <?php endif; ?>
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
            </div>
        </form>
    </div>
</body>
</html>
