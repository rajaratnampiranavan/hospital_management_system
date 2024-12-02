<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_management";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete patient details
        $stmt = $conn->prepare("DELETE FROM patient_details WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Commit transaction
            $conn->commit();
            echo "User deleted successfully. <a href='view_patients.php'>Go back to patient list</a>";
        } else {
            // Rollback transaction
            $conn->rollback();
            echo "No user found with ID $id. <a href='view_patients.php'>Go back to patient list</a>";
        }
        $stmt->close();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error deleting user: " . $e->getMessage() . "<br><a href='view_patients.php'>Go back to patient list</a>";
    }
} else {
    echo "Invalid ID. <a href='view_patients.php'>Go back to patient list</a>";
}

$conn->close();
?>
