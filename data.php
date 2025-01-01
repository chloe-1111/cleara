<?php
session_start();
include("templates/conn.php");

// Check if the email is present in the URL
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    try {
        // Query to retrieve user information
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user'] = $user; // Store user info in session
        } else {
            echo "No user found with email: " . htmlspecialchars($email) . "<br>";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
} else {
    echo "No email parameter provided.<br>";
    header("Location: login.php");
    exit();
}
?>
