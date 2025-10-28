<?php 
session_start();
require_once 'config.php';

// Prevent direct access
if (!isset($_SESSION['booking_id']) || !isset($_SESSION['payment_method'])) {
    header('Location: step1.php');
    exit;
}

$booking_id = $_SESSION['booking_id'];
$payment_method = $_SESSION['payment_method'];
$total_amount = $_SESSION['total_amount'] ?? 0;
$status = 'Confirmed';
$payment_status = 'Confirmed';

// ✅ Update the existing booking (not insert)
$sql = "UPDATE bookings 
        SET status = ?, payment_method = ?, total_amount = ?, payment_status = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsi", $status, $payment_method, $total_amount, $payment_status, $booking_id);

if ($stmt->execute()) {
    $_SESSION['payment_status'] = 'Confirmed';
} else {
    die("Database error: " . $stmt->error);
}

$stmt->close();
$conn->close();

header('Location: confirmation.php');
exit;
?>
