<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- style -->
    <link rel="shortcut icon" href="images/logo.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="custom/custom.css">
</head>

<body>
<!-- heading -->
<header class="navbar-custom">
  <div class="header-container">
    <a class="header-brand" href="#">
      <img src="images/header.jpeg" alt="Cleara Logo">
    </a>
  </div>
</header>

<?php
// include database
session_start();
include("templates/conn.php");

$errorMessage = ""; 

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Query to fetch user based on email
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful, set session and redirect
            $_SESSION['user'] = $user;
            header("Location: home.php?email=" . urlencode($email));
            exit();
        } else {
            // Incorrect credentials
            $errorMessage = "Incorrect email or password. Please try again.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Database error: " . $e->getMessage();
    }
}

// Handle account creation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addpatient'])) {
    $email = trim($_POST['create-email']);
    $password = password_hash(trim($_POST['create-password']), PASSWORD_BCRYPT);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $age = intval($_POST['age']);
    $medication = trim($_POST['medication']);
    $dosage = intval($_POST['dosage']);
    $time = trim($_POST['time']);

    try {
        // Check if email already exists
        $checkEmailSql = "SELECT * FROM user WHERE email = :email";
        $stmt = $conn->prepare($checkEmailSql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            echo "<script>alert('An account with this email already exists.');</script>";
        } else {
            // Insert new user into the database
            $insertSql = "INSERT INTO user (email, password, name, surname, age, medication, dosage, time) 
                          VALUES (:email, :password, :name, :surname, :age, :medication, :dosage, :time)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':surname', $surname);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':medication', $medication);
            $stmt->bindValue(':dosage', $dosage);
            $stmt->bindValue(':time', $time);

            if ($stmt->execute()) {
                echo "<script>alert('Account created successfully!');</script>";
            } else {
                echo "<script>alert('Failed to create account. Please try again.');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <!-- Login Section -->
        <div class="col-md-6 container-custom">
            <h1 class="text-center">Log in</h1>
            <form method="POST" action="login.php">
                <input type="hidden" name="login" value="1">
                <div class="form-group">
                    <label for="login-email">Email:</label>
                    <input type="email" class="form-control" id="login-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" class="form-control" id="login-password" name="password" required>
                </div>

                <!-- Error Message -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-outline-primary btn-block">Submit</button>
            </form>
        </div>

        <!-- Create Account Section -->
        <div class="col-md-6 container-custom">
            <h1 class="text-center">Create a New Account</h1>
            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#addPatientModal">Create Account</button>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">Create a New Account</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="login.php">
                    <input type="hidden" name="addpatient" value="1">
                    <div class="form-group">
                        <label for="create-email">Email:</label>
                        <input type="email" id="create-email" name="create-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="create-password">Password:</label>
                        <input type="password" id="create-password" name="create-password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname:</label>
                        <input type="text" id="surname" name="surname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="medication">Medication:</label>
                        <input type="text" id="medication" name="medication" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dosage">Dosage:</label>
                        <input type="number" id="dosage" name="dosage" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" id="time" name="time" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary btn-block">Add Patient</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>
