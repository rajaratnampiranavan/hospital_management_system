  <?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize search variable
$searchNIC = '';
if (isset($_POST['search'])) {
    $searchNIC = $_POST['search_nic'];
}

// Fetch users and their details with optional search filter
$sql = "SELECT u.*, pd.entry_date, pd.exit_date, pd.treatment_notes, pd.prescribed_pills, pd.dosage
        FROM users u
        LEFT JOIN patient_details pd ON u.id = pd.user_id";

if (!empty($searchNIC)) {
    $sql .= " WHERE u.nic_number LIKE '%" . $conn->real_escape_string($searchNIC) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Patients</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* General Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .hidden-form {
            display: none;
        }
        .search-form {
            margin: 20px 0;
        }
        .print-button, .delete-button {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            background: none;
            border: none;
            padding: 0;
        }
        /* Print-specific styles */
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
            table {
                border: 1px solid #000;
                margin: 0;
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                font-size: 12px;
            }
            th {
                background-color: #ccc;
            }
            .search-form, .print-button, .delete-button {
                display: none;
            }
        }
    </style>
    <script>
        function toggleForm(userId) {
            var form = document.getElementById('form-' + userId);
            form.classList.toggle('hidden-form');
        }

        function printRow(userId) {
            var row = document.getElementById('row-' + userId);
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Patient Details</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; }</style>');
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>Patient Details</h1>');
            printWindow.document.write(row.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</head>
<body>
    <h1>Patients List</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="">
            <label for="search_nic">Search by NIC Number:</label>
            <input type="text" id="search_nic" name="search_nic" value="<?php echo htmlspecialchars($searchNIC); ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <!-- Patients Table -->
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>NIC Number</th>
                <th>Address</th>
                <th>Blood Type</th>
                <th>Allergies</th>
                <th>Entry Date and Time</th>
                <th>Exit Date and Time</th>
                <th>Treatment Notes</th>
                <th>Prescribed Pills</th>
                <th>Dosage</th>
                <th>Actions</th>
                <th>Print</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $user['id']; ?>">
                    <td><?php echo !empty($user['full_name']) ? htmlspecialchars($user['full_name']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['username']) ? htmlspecialchars($user['username']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['nic_number']) ? htmlspecialchars($user['nic_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['blood_type']) ? htmlspecialchars($user['blood_type']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['allergies']) ? htmlspecialchars($user['allergies']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['entry_date']) ? htmlspecialchars($user['entry_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['exit_date']) ? htmlspecialchars($user['exit_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['treatment_notes']) ? htmlspecialchars($user['treatment_notes']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['prescribed_pills']) ? htmlspecialchars($user['prescribed_pills']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['dosage']) ? htmlspecialchars($user['dosage']) : 'N/A'; ?></td>
                    <td>
                        <button onclick="toggleForm(<?php echo $user['id']; ?>)">Add/Edit Details</button>
                    </td>
                    <td>
                        <button class="print-button" onclick="printRow(<?php echo $user['id']; ?>)">Print</button>
                    </td>
                    <td>
                        <!-- Delete Form -->
                        <form action="delete_patient.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <!-- Editable Form -->
                <tr class="hidden-form" id="form-<?php echo $user['id']; ?>">
                    <td colspan="15">
                        <form action="update_details.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                            <label for="entry_date-<?php echo $user['id']; ?>">Entry Date and Time:</label>
                            <input type="datetime-local" id="entry_date-<?php echo $user['id']; ?>" name="entry_date"
                                   value="<?php echo !empty($user['entry_date']) ? htmlspecialchars($user['entry_date']) : ''; ?>">

                            <label for="exit_date-<?php echo $user['id']; ?>">Exit Date and Time:</label>
                            <input type="datetime-local" id="exit_date-<?php echo $user['id']; ?>" name="exit_date"
                                   value="<?php echo !empty($user['exit_date']) ? htmlspecialchars($user['exit_date']) : ''; ?>">

                            <label for="treatment_notes-<?php echo $user['id']; ?>">Treatment Notes:</label>
                            <textarea id="treatment_notes-<?php echo $user['id']; ?>" name="treatment_notes"><?php echo !empty($user['treatment_notes']) ? htmlspecialchars($user['treatment_notes']) : ''; ?></textarea>

                            <label for="prescribed_pills-<?php echo $user['id']; ?>">Prescribed Pills:</label>
                            <input type="text" id="prescribed_pills-<?php echo $user['id']; ?>" name="prescribed_pills"
                                   value="<?php echo !empty($user['prescribed_pills']) ? htmlspecialchars($user['prescribed_pills']) : ''; ?>">

                            <label for="dosage-<?php echo $user['id']; ?>">Dosage:</label>
                            <input type="text" id="dosage-<?php echo $user['id']; ?>" name="dosage"
                                   value="<?php echo !empty($user['dosage']) ? htmlspecialchars($user['dosage']) : ''; ?>">

                            <button type="submit">Save Changes</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function toggleForm(userId) {
            var form = document.getElementById('form-' + userId);
            form.classList.toggle('hidden-form');
        }

        function printRow(userId) {
            var row = document.getElementById('row-' + userId);
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Patient Details</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; }</style>');
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>Patient Details</h1>');
            printWindow.document.write(row.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>


<?php
include('../database/config.php');
session_start();

if (!isset($_SESSION['hospital_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize search variable
$searchNIC = '';
if (isset($_POST['search'])) {
    $searchNIC = $_POST['search_nic'];
}

// Fetch users and their details with optional search filter
$sql = "SELECT u.*, pd.entry_date, pd.exit_date, pd.treatment_notes, pd.prescribed_pills, pd.dosage
        FROM users u
        LEFT JOIN patient_details pd ON u.id = pd.user_id";

if (!empty($searchNIC)) {
    $sql .= " WHERE u.nic_number LIKE '%" . $conn->real_escape_string($searchNIC) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Patients</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* General Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .hidden-form {
            display: none;
        }
        .search-form {
            margin: 20px 0;
        }
        .print-button, .delete-button {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            background: none;
            border: none;
            padding: 0;
        }
        /* Print-specific styles */
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
            table {
                border: 1px solid #000;
                margin: 0;
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                font-size: 12px;
            }
            th {
                background-color: #ccc;
            }
            .search-form, .print-button, .delete-button {
                display: none;
            }
            .hidden-form, .print-button, .delete-button {
                display: none;
            }
        }
    </style>
    <script>
        function toggleForm(userId) {
            var form = document.getElementById('form-' + userId);
            form.classList.toggle('hidden-form');
        }

        function printRow(userId) {
            var row = document.getElementById('row-' + userId);
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Patient Details</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; }</style>');
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>Patient Details</h1>');
            printWindow.document.write(row.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</head>
<body>
    <h1>Patients List</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="">
            <label for="search_nic">Search by NIC Number:</label>
            <input type="text" id="search_nic" name="search_nic" value="<?php echo htmlspecialchars($searchNIC); ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <!-- Patients Table -->
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>NIC Number</th>
                <th>Address</th>
                <th>Blood Type</th>
                <th>Allergies</th>
                <th>Entry Date and Time</th>
                <th>Exit Date and Time</th>
                <th>Treatment Notes</th>
                <th>Prescribed Pills</th>
                <th>Dosage</th>
                <th>Actions</th>
                <th>Print</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $user['id']; ?>">
                    <td><?php echo !empty($user['full_name']) ? htmlspecialchars($user['full_name']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['username']) ? htmlspecialchars($user['username']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['nic_number']) ? htmlspecialchars($user['nic_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['blood_type']) ? htmlspecialchars($user['blood_type']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['allergies']) ? htmlspecialchars($user['allergies']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['entry_date']) ? htmlspecialchars($user['entry_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['exit_date']) ? htmlspecialchars($user['exit_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['treatment_notes']) ? htmlspecialchars($user['treatment_notes']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['prescribed_pills']) ? htmlspecialchars($user['prescribed_pills']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['dosage']) ? htmlspecialchars($user['dosage']) : 'N/A'; ?></td>
                    <td>
                        <button onclick="toggleForm(<?php echo $user['id']; ?>)">Add Details</button>
                    </td>
                    <td>
                        <button class="print-button" onclick="printRow(<?php echo $user['id']; ?>)">Print</button>
                    </td>
                    <td>
                        <!-- Delete Form -->
                        <form action="delete_patient.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <tr class="hidden-form" id="form-<?php echo $user['id']; ?>">
    

            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this record?');
        }
    </script>
</body>
</html>

