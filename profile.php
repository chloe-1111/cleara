<?php
// Retrieve email from the URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!empty($email)) {
    try {
        // Fetch user information from the database
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $patient = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array

        if (!$patient) {
            echo "<script>alert('Patient not found.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('No email provided.');</script>";
}

// Edit profile
if (isset($_POST['updatepatient'])) {
    // Retrieve and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $age = intval($_POST['age']);
    $medication = htmlspecialchars($_POST['medication']);
    $dosage = intval($_POST['dosage']);
    $time = $_POST['time'];
    $email = $_GET['email']; // Retrieve patient email from the URL

    try {
        $sql = "UPDATE user SET 
                name = :name, 
                surname = :surname, 
                age = :age, 
                medication = :medication, 
                dosage = :dosage, 
                time = :time 
                WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':surname', $surname);
        $stmt->bindValue(':age', $age);
        $stmt->bindValue(':medication', $medication);
        $stmt->bindValue(':dosage', $dosage);
        $stmt->bindValue(':time', $time);
        $stmt->bindValue(':email', $email);

        $stmt->execute();

        // Redirect after successful update
        echo "<script>alert('Patient information updated successfully!');</script>";
        echo "<script>window.location.href='home.php?email=$email';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Display User Information -->
<div id="profile-container" class="container">
    <h1>User Profile</h1>

    <div class="container-custom">
      <table class="table">
        <tr>
          <th>First Name</th>
          <td><?php echo isset($patient['name']) ? htmlspecialchars($patient['name']) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Last Name</th>
          <td><?php echo isset($patient['surname']) ? htmlspecialchars($patient['surname']) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Age</th>
          <td><?php echo isset($patient['age']) ? htmlspecialchars($patient['age']) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Medication</th>
          <td><?php echo isset($patient['medication']) ? htmlspecialchars($patient['medication']) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Dosage</th>
          <td><?php echo isset($patient['dosage']) ? htmlspecialchars($patient['dosage']) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Time</th>
          <td><?php echo isset($patient['time']) ? htmlspecialchars($patient['time']) : 'N/A'; ?></td>
        </tr>
      </table>
      <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editPatientModal">Edit</button>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPatientModalLabel">Edit Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="home.php?email=<?php echo $patient['email']; ?>" method="POST">
            <div class="form-group">
              <label for="name">First Name:</label>
              <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($patient['name']) ? $patient['name'] : ''; ?>">
            </div>
            <div class="form-group">
              <label for="surname">Last Name:</label>
              <input type="text" id="surname" name="surname" class="form-control" value="<?php echo isset($patient['surname']) ? $patient['surname'] : ''; ?>">
            </div>
            <div class="form-group">
              <label for="age">Age:</label>
              <input type="number" id="age" name="age" class="form-control" value="<?php echo isset($patient['age']) ? $patient['age'] : ''; ?>">
            </div>
            <div class="form-group">
              <label for="medication">Medication:</label>
              <input type="text" id="medication" name="medication" class="form-control" value="<?php echo isset($patient['medication']) ? $patient['medication'] : ''; ?>">
            </div>
            <div class="form-group">
              <label for="dosage">Dosage:</label>
              <input type="number" id="dosage" name="dosage" class="form-control" value="<?php echo isset($patient['dosage']) ? $patient['dosage'] : ''; ?>">
            </div>
            <div class="form-group">
              <label for="time">Time:</label>
              <input type="time" id="time" name="time" class="form-control" value="<?php echo isset($patient['time']) ? $patient['time'] : ''; ?>">
            </div>
            <input type="submit" name="updatepatient" class="btn btn-outline-secondary float-right" value="Update">
          </form>
        </div>
      </div>
    </div>
  </div>


