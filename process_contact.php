<?php
session_start();
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error_message'] = "Semua kolom harus diisi!";
        header("Location: contact.php");
        exit();
    }

    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Your message has been successfully sent. We will contact you soon!";
            $stmt->close();
            $conn->close();
            header("Location: contact.php?status=success");
            exit();
        } else {
            $stmt->close();
            $_SESSION['error_message'] = "An error occurred while saving data. Please try again. Error: " . $conn->error;
            header("Location: contact.php?status=error");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Gagal menyiapkan query database: " . $conn->error;
        header("Location: contact.php?status=error");
        exit();
    }

} else {
    header("Location: contact.php");
    exit();
}
?>