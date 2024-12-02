<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

$hospital_id = $_SESSION['hospital_id'];
$sql = "SELECT * FROM hospitals WHERE id=$hospital_id";
$result = $conn->query($sql);
$hospital = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/slick/slick.css" rel="stylesheet">
    <link href="lib/slick/slick-theme.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($hospital['name']); ?></h1>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($hospital['address']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($hospital['phone']); ?></p>
        
        <!-- Display Hospital Photo -->
        <?php if (!empty($hospital['photo'])): ?>
            <div class="mb-4">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($hospital['photo']); ?>" alt="Hospital Photo" class="img-fluid" style="max-width: 300px; height: auto;">
            </div>
        <?php else: ?>
            <p>No photo available.</p>
        <?php endif; ?>
        
        <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
        <a href="view_patients.php" class="btn btn-secondary">View Patients</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
