<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $entry_date = $_POST['entry_date'];
    $exit_date = $_POST['exit_date'];
    $treatment_notes = $_POST['treatment_notes'];
    $prescribed_pills = $_POST['prescribed_pills'];
    $dosage = $_POST['dosage'];

    $stmt = $conn->prepare("UPDATE patient_details SET entry_date = ?, exit_date = ?, treatment_notes = ?, prescribed_pills = ?, dosage = ? WHERE user_id = ?");
    $stmt->bind_param('sssssi', $entry_date, $exit_date, $treatment_notes, $prescribed_pills, $dosage, $user_id);

    if ($stmt->execute()) {
        header('Location: view_patients.php'); // Redirect to the page with the updated list
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
