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

    // Insert or update the details in the database
    $sql = "INSERT INTO patient_details (user_id, entry_date, exit_date, treatment_notes, prescribed_pills, dosage) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            entry_date = VALUES(entry_date), 
            exit_date = VALUES(exit_date), 
            treatment_notes = VALUES(treatment_notes), 
            prescribed_pills = VALUES(prescribed_pills), 
            dosage = VALUES(dosage)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssss', $user_id, $entry_date, $exit_date, $treatment_notes, $prescribed_pills, $dosage);

    if ($stmt->execute()) {
        echo "Details saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: view_patients.php');
    exit;
}
?>
