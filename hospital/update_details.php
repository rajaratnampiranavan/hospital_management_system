<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $conn->real_escape_string($_POST['user_id']);
    $entryDate = $conn->real_escape_string($_POST['entry_date']);
    $exitDate = $conn->real_escape_string($_POST['exit_date']);
    $treatmentNotes = $conn->real_escape_string($_POST['treatment_notes']);
    $prescribedPills = $conn->real_escape_string($_POST['prescribed_pills']);
    $dosage = $conn->real_escape_string($_POST['dosage']);

    $sql = "UPDATE patient_details 
            SET entry_date = '$entryDate', exit_date = '$exitDate', treatment_notes = '$treatmentNotes', prescribed_pills = '$prescribedPills', dosage = '$dosage' 
            WHERE user_id = '$userId'";

    if ($conn->query($sql) === TRUE) {
        header('Location: view_patients.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
