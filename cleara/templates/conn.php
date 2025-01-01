<?php
    $conn = new PDO('sqlite:C:\xampp\htdocs\cleara\templates\databasecleara.db'); 
    if (!$conn) {
        die("Connection failed: " . $conn->errorInfo());
    }
?>

