<?php
// Retrieve email or unique identifier from the URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!empty($email)) {
    try {
        // Fetch patient information from the database
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
?>

<!--container 1: patient information-->
<div class="container" id="profile-container">
    <h1> Profile</h1>
    <table class="table" id="patienttable">
        <tr>
            <td>First name</td>
            <td><?php echo isset($patient['name']) ? htmlspecialchars($patient['name']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>Last name</td>
            <td><?php echo isset($patient['surname']) ? htmlspecialchars($patient['surname']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>Age</td>
            <td><?php echo isset($patient['age']) ? htmlspecialchars($patient['age']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>Medication</td>
            <td><?php echo isset($patient['medication']) ? htmlspecialchars($patient['medication']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>Dosage</td>
            <td><?php echo isset($patient['dosage']) ? htmlspecialchars($patient['dosage']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>Time</td>
            <td><?php echo isset($patient['time']) ? htmlspecialchars($patient['time']) : 'N/A'; ?></td>
        </tr>
    </table>

    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editPatientModal">Edit</button> <br><br>
</div>


<!--popup: edit patient information-->
<div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPatientModalLabel">Edit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="home.php?email=<?php echo $patient['email']; ?>" method="POST">
                    <div class="form-group">
                        <label for="name">First Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($patient['name']) ? $patient['name'] : ''; ?>">
                        <label for="surname">Last Name:</label>
                        <input type="text" id="surname" name="surname" class="form-control" value="<?php echo isset($patient['surname']) ? $patient['surname'] : ''; ?>">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" class="form-control" value="<?php echo isset($patient['age']) ? $patient['age'] : ''; ?>">
                        <label for="medication">Medication:</label>
                        <input type="text" id="medication" name="medication" class="form-control" value="<?php echo isset($patient['medication']) ? $patient['medication'] : ''; ?>">
                        <label for="dosage">Dosage:</label>
                        <input type="number" id="dosage" name="dosage" class="form-control" value="<?php echo isset($patient['dosage']) ? $patient['dosage'] : ''; ?>">
                        <label for="time">Time:</label>
                        <input type="time" id="time" name="time" class="form-control" value="<?php echo isset($patient['time']) ? $patient['time'] : ''; ?>">
                    </div>
                    <input type="submit" name="updatepatient" class="btn btn-outline-secondary float-right" value="Update Patient">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- PHP: edit patient information -->
<?php
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
