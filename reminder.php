<?php
// Include database connection
include 'templates/conn.php';

// Include PHPMailer through Composer's autoload
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone
date_default_timezone_set('Europe/Berlin'); // Replace with your desired timezone

// Start an infinite loop to continuously check for reminders
while (true) {
    // Get the current time in "HH:mm" format
    $current_time = date('H:i');

    // Query users who should be reminded at the current time
    $query = "SELECT email, name, medication FROM user WHERE time = :current_time";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':current_time', $current_time);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'cleara.notifications@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'gwod lxcy yawt ckdq'; // Replace with your Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Loop through users and send emails
        foreach ($users as $user) {
            $mail->clearAddresses();
            $mail->setFrom('cleara.notifications@gmail.com', 'Cleara Notifications');
            $mail->addAddress($user['email'], $user['name']);
            $mail->Subject = 'Medication Reminder';
            $mail->Body = "Hello {$user['name']},\n\nThis is your daily reminder to take your medication: {$user['medication']}.\nPlease open Cleara to add today's symptoms.\n\nStay healthy,\nCleara Team";

            $mail->send();
        }

        if (count($users) > 0) {
            echo "Emails sent successfully.\n";
        }
    } catch (Exception $e) {
        error_log("Failed to send emails. Error: {$mail->ErrorInfo}");
        echo "Failed to send emails. Check error log for details.\n";
    }

    // Wait 60 seconds before checking again
    sleep(60);
}
?>
