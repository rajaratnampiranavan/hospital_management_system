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

// Function to calculate age
function calculate_age($date_of_birth) {
    $birthDate = new DateTime($date_of_birth);
    $today = new DateTime();
    $interval = $today->diff($birthDate);
    return $interval->y;
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
     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:300;400;600;700;800&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="lib/slick/slick.css" rel="stylesheet">
        <link href="lib/slick/slick-theme.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

        
        
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

   
 <style>
       body {
   
    background-color: #F0F8FF;
}
/* styles.css */
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

input[type="datetime-local"],
input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    margin-bottom: 15px;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button.usubmit {
    padding: 10px 15px;
    background-color: #007bff;
    border: none;
    color: #fff;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

button.usubmit:hover {
    background-color: #0056b3;
}

    </style> 
    
    <script>
        function toggleAddForm(userId) {
            var form = document.getElementById('add-form-' + userId);
            if (form) {
                form.classList.toggle('hidden-form');
            }
        }

        function toggleEditForm(userId) {
            var form = document.getElementById('edit-form-' + userId);
            if (form) {
                form.classList.toggle('hidden-form');
            }
        }

function printRow(userId) {
    var row = document.getElementById('row-' + userId);
    if (row) {
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Patient Details</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        printWindow.document.write('.container { width: 98%; max-width: 800px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }');
        printWindow.document.write('.photo { float: right; width: 120px; height: 150px; border: 1px solid #ccc; text-align: center; line-height: 150px; font-size: 18px; color: #888; border-radius: 8px; object-fit: cover; overflow: hidden; }');
        printWindow.document.write('h1 { text-align: center; text-decoration: underline; color: #333; }');
        printWindow.document.write('.details, .qualification { margin-top: 20px; }');
        printWindow.document.write('.details table, .qualification table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('.details table td, .qualification table td { padding: 8px; border-bottom: 1px solid #ddd; }');
        printWindow.document.write('.qualification table th { background-color: #f2f2f2; font-weight: bold; }');
        printWindow.document.write('.qualification table, .qualification th, .qualification td { border: 1px solid #ddd; }');
        printWindow.document.write('.qualification th, .qualification td { padding: 10px; text-align: left; }');
        printWindow.document.write('.dosage { max-width: 98%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="container">');
        printWindow.document.write('<h1>Patient Details</h1>');
        printWindow.document.write('<div class="photo">PHOTO</div>');
        printWindow.document.write('<div class="details"><table>');
        printWindow.document.write('<tr><td>Name:</td><td>' + row.querySelector('td:nth-child(1)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Username:</td><td>' + row.querySelector('td:nth-child(2)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Email:</td><td>' + row.querySelector('td:nth-child(3)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Phone Number:</td><td>' + row.querySelector('td:nth-child(4)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Date of Birth:</td><td>' + row.querySelector('td:nth-child(5)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Age:</td><td>' + row.querySelector('td:nth-child(6)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>NIC Number:</td><td>' + row.querySelector('td:nth-child(7)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Address:</td><td>' + row.querySelector('td:nth-child(8)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Blood Type:</td><td>' + row.querySelector('td:nth-child(9)').textContent + '</td></tr>');
        printWindow.document.write('<tr><td>Allergies:</td><td>' + row.querySelector('td:nth-child(10)').textContent + '</td></tr>');
        printWindow.document.write('</table></div>');
        printWindow.document.write('<div class="qualification">');
        printWindow.document.write('<h2>Medical Details</h2>');
        printWindow.document.write('<table>');
        printWindow.document.write('<tr><th>Entry Date and Time</th><th>Exit Date and Time</th><th>Treatment Notes</th><th>Prescribed Pills</th><th class="dosage">Dosage</th></tr>');
        printWindow.document.write('<tr>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(11)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(12)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(13)').textContent + '</td>');
        printWindow.document.write('<td>' + row.querySelector('td:nth-child(14)').textContent + '</td>');
        printWindow.document.write('<td class="dosage">' + row.querySelector('td:nth-child(15)').textContent + '</td>');
        printWindow.document.write('</tr></table></div></div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    } else {
        alert('Row not found for user ID: ' + userId);
    }
}

    </script>
</head>
<body>
<nav>
  <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 1px 5px; font-size: 14px;">
    
    <a class="navbar-brand" href="profile.php">
      <?php if (!empty($hospital['photo'])): ?>
        <div class="mb-4">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($hospital['photo']); ?>" alt="Hospital Photo" class="img-fluid" style="width: 80px; height: 80px; border-radius: 50%; background-color: red;">
        </div>
      <?php else: ?>
        <div class="mb-4" style="width: 80px; height: 80px; border-radius: 50%; background-color: red;"></div>
        <p>No photo available.</p>
      <?php endif; ?>
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="profile.php" style="font-size: 14px;">Patients List <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="profile.php" style="font-size: 14px;">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php" style="font-size: 14px;">Log out</a>
        </li>
      </ul>
      <div class="search-form">
        <form method="POST" action="" style="display: flex; align-items: center; font-size: 14px;">
          <label for="search_nic" style="margin-right: 10px;">Search by NIC Number:</label>
          <input type="text" id="search_nic" name="search_nic" value="<?php echo htmlspecialchars($searchNIC); ?>" style="margin-right: 10px; font-size: 14px;">
          <button class="ssubmit" type="submit" name="search" style="font-size: 14px;">Search</button>
        </form>
      </div>
    </div>
  </nav>
</nav>



    <!-- Patients Table -->
    <table >
        <thead  >
            <tr > 
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>D.O.B</th>
                <th>Age</th>
                <th>NIC Number</th>
                <th>Address</th>
                <th>Blood Type</th>
                <th>Allergies</th>
                <th>Entry Date,Time</th>
                <th>Exit Date,Time</th>
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
                    <td><?php echo !empty($user['fullname']) ? htmlspecialchars($user['fullname']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['username']) ? htmlspecialchars($user['username']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['date_of_birth']) ? calculate_age($user['date_of_birth']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['nic_number']) ? htmlspecialchars($user['nic_number']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['blood']) ? htmlspecialchars($user['blood']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['allergies']) ? htmlspecialchars($user['allergies']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['entry_date']) ? htmlspecialchars($user['entry_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['exit_date']) ? htmlspecialchars($user['exit_date']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['treatment_notes']) ? htmlspecialchars($user['treatment_notes']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['prescribed_pills']) ? htmlspecialchars($user['prescribed_pills']) : 'N/A'; ?></td>
                    <td><?php echo !empty($user['dosage']) ? htmlspecialchars($user['dosage']) : 'N/A'; ?></td>
                    
                    <td>
                        <button class="add-button" onclick="toggleAddForm(<?php echo $user['id']; ?>)">Add</button>
                        <button class="modify-button" onclick="toggleEditForm(<?php echo $user['id']; ?>)">Edit</button>
                    </td>
                    <td>
                       <button class="print-button" onclick="printRow(<?php echo $user['id']; ?>)">Print</button>
                    </td>
                    
                    <td>
                     <a class="delete_user" href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>

                <!-- Add Details Form -->
                <tr class="hidden-form" id="add-form-<?php echo $user['id']; ?>">
                    <td colspan="16">
                        <form action="add_details.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                            <label for="entry_date-<?php echo $user['id']; ?>">Entry Date and Time:</label>
                            <input type="datetime-local" id="entry_date-<?php echo $user['id']; ?>" name="entry_date">

                            <label for="exit_date-<?php echo $user['id']; ?>">Exit Date and Time:</label>
                            <input type="datetime-local" id="exit_date-<?php echo $user['id']; ?>" name="exit_date">

                            <label for="treatment_notes-<?php echo $user['id']; ?>">Treatment Notes:</label>
                            <textarea id="treatment_notes-<?php echo $user['id']; ?>" name="treatment_notes"></textarea>

                            <label for="prescribed_pills-<?php echo $user['id']; ?>">Prescribed Pills:</label>
                            <input type="text" id="prescribed_pills-<?php echo $user['id']; ?>" name="prescribed_pills">

                            <label for="dosage-<?php echo $user['id']; ?>">Dosage:</label>
                            <input type="text" id="dosage-<?php echo $user['id']; ?>" name="dosage">

                            <button class="submit" type="submit">Save Details</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Details Form -->
                <tr class="hidden-form" id="edit-form-<?php echo $user['id']; ?>">
                    <td colspan="16">
                       <form action="edit_details.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

    <label for="entry_date-<?php echo $user['id']; ?>">Entry Date and Time:</label>
    <input type="datetime-local" id="entry_date-<?php echo $user['id']; ?>" name="entry_date" value="<?php echo $user['entry_date']; ?>">

    <label for="exit_date-<?php echo $user['id']; ?>">Exit Date and Time:</label>
    <input type="datetime-local" id="exit_date-<?php echo $user['id']; ?>" name="exit_date" value="<?php echo $user['exit_date']; ?>">

    <label for="treatment_notes-<?php echo $user['id']; ?>">Treatment Notes:</label>
    <textarea id="treatment_notes-<?php echo $user['id']; ?>" name="treatment_notes"><?php echo htmlspecialchars($user['treatment_notes']); ?></textarea>

    <label for="prescribed_pills-<?php echo $user['id']; ?>">Prescribed Pills:</label>
    <input type="text" id="prescribed_pills-<?php echo $user['id']; ?>" name="prescribed_pills" value="<?php echo htmlspecialchars($user['prescribed_pills']); ?>">

    <label for="dosage-<?php echo $user['id']; ?>">Dosage:</label>
    <input type="text" id="dosage-<?php echo $user['id']; ?>" name="dosage" value="<?php echo htmlspecialchars($user['dosage']); ?>">

    <button class="usubmit" type="submit">Update Details</button>
</form>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="content">
        <!-- Your page content here -->
    </div>

    <footer class="bg-body-tertiary text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3">
            © 2024 Copyright: Thunder Beast
        </div>
        <!-- Copyright -->
    </footer>
</body>
</html>
