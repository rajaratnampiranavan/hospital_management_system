<?php
include('../database/config.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

// Check if necessary POST data is provided
if (isset($_POST['user_id']) && isset($_POST['blood_type']) && isset($_POST['allergies'])) {
    $user_id = intval($_POST['user_id']);
    $blood_type = $conn->real_escape_string($_POST['blood_type']);
    $allergies = $conn->real_escape_string($_POST['allergies']);

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE users SET blood_type = ?, allergies = ? WHERE id = ?");
    $stmt->bind_param("ssi", $blood_type, $allergies, $user_id);

    if ($stmt->execute()) {
        // Redirect back to the list page
        header('Location: view_patients.php');
        exit;
    } else {
        // Handle error
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Handle missing data
    echo "Required data missing.";
}
?>
