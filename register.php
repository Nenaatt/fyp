<?php
session_start();
require_once 'config.php';

$registration_error = '';
$full_name = $_SESSION['registration_data']['passenger_name'] ?? '';
$email = $_SESSION['registration_data']['email'] ?? '';
// Note: No phone in users table, so not pre-filling phone here. Phone is stored in bookings.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    $role = 'customer';

    if ($password !== $cpassword) {
        $registration_error = 'Passwords do not match!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $registration_error = 'Email already registered!';
            $stmt_check->close();
        } else {
            $stmt_check->close();

            // Insert new user
            $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt_insert->execute()) {
                $user_id = $stmt_insert->insert_id;
                $stmt_insert->close();

                // Auto-login by setting session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $name;

                // If there's a pending booking, update it with user_id
                if (isset($_SESSION['booking_id'])) {
                    $booking_id = (int)$_SESSION['booking_id'];
                    $stmt_update = $conn->prepare("UPDATE bookings SET user_id = ? WHERE id = ?");
                    $stmt_update->bind_param("ii", $user_id, $booking_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                }

                // If coming from Step3 (registration_data exists), complete the booking update and redirect to payment
                if (isset($_SESSION['registration_data'])) {
                    // Extract data from session
                    $passenger_name = $_SESSION['registration_data']['passenger_name'];
                    $contact_number = $_SESSION['registration_data']['contact_number'];
                    $email = $_SESSION['registration_data']['email'];
                    $payment_method = $_SESSION['registration_data']['payment_method'];
                    $guide_status = ($_SESSION['registration_data']['tourist_guide'] === 'yes') ? 1 : 0;
                    $luggage_status = ($_SESSION['registration_data']['luggage_assistance'] === 'yes') ? 1 : 0;

                    // Calculate total amount
                    $vehicle_price = $_SESSION['vehicle']['price'] ?? 0.00;
                    $total_amount = $vehicle_price;
                    if ($guide_status) $total_amount += 100.00;
                    if ($luggage_status) $total_amount += 50.00;

                    $_SESSION['total_amount'] = $total_amount;
                    $_SESSION['passenger_name'] = $passenger_name;
                    $_SESSION['contact_number'] = $contact_number;
                    $_SESSION['email'] = $email;
                    $_SESSION['payment_method'] = $payment_method;
                    $_SESSION['tourist_guide'] = $guide_status ? 'yes' : 'no';
                    $_SESSION['luggage_assistance'] = $luggage_status ? 'yes' : 'no';

                    // Update the booking
                    if (isset($_SESSION['booking_id'])) {
                        $booking_id = (int)$_SESSION['booking_id'];
                        $stmt = $conn->prepare("UPDATE bookings SET 
                            vehicle_name = ?,
                            vehicle_price = ?,
                            passenger_name = ?,
                            email = ?,
                            phone = ?,
                            payment_method = ?,
                            tourist_guide = ?,
                            luggage_assistance = ?,
                            total_amount = ?
                            WHERE id = ?");
                        
                        $stmt->bind_param(
                            "sdsssdsidi", 
                            $_SESSION['vehicle']['name'],
                            $vehicle_price,
                            $passenger_name,
                            $email,
                            $contact_number,
                            $payment_method,
                            $guide_status,
                            $luggage_status,
                            $total_amount,
                            $booking_id
                        );

                        if ($stmt->execute()) {
                            // Clear registration data
                            unset($_SESSION['registration_data']);

                            // Redirect based on payment method
                            if ($payment_method === 'QR') {
                                header('Location: payment_qr.php');
                                exit;
                            } elseif ($payment_method === 'Transfer') {
                                header('Location: transfer_payment.php');  // Assuming this file exists
                                exit;
                            } else {
                                header('Location: dashboard.php');
                                exit;
                            }
                        } else {
                            $registration_error = 'An error occurred while finalizing your booking. Please try again.';
                        }
                        $stmt->close();
                    } else {
                        // No booking_id, redirect to dashboard
                        header('Location: dashboard.php');
                        exit;
                    }
                } else {
                    // Normal registration, redirect to dashboard
                    header('Location: dashboard.php');
                    exit;
                }
            } else {
                $registration_error = 'An error occurred during registration. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Global Sunrise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #003366 !important; }
        .navbar .nav-link.active, .navbar .nav-link:hover { color: #ffd700 !important; }
        .footer { background-color: #003366 !important; color: #fff; padding: 20px 0; margin-top: 80px; text-align: center; }
        .footer a { color: #ffd700 !important; text-decoration: none; }
        .footer a:hover { color: #fff !important; }
        .text-primary { color: #003366 !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
            Global Sunrise Travel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Transport</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-lg rounded-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Customer Registration</h3>
        
        <?php if (!empty($registration_error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($registration_error); ?>
            </div>
        <?php endif; ?>

        <form method="post" onsubmit="return validatePassword()">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="Re-enter your password" required>
                <div id="passwordHelp" class="form-text text-danger"></div>
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
        </form>
        
        <p class="text-center mt-3">
            Already registered? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<footer class="footer">
    <div class="container text-center">
        <p>&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
        <p>
            <a href="contact.php" class="text-white me-3">Contact</a> |
            <a href="about us.php" class="text-white mx-3">About Us</a> |
            <a href="terms.php" class="text-white ms-3">Terms</a>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validatePassword() {
    const pass = document.getElementById("password").value;
    const cpass = document.getElementById("cpassword").value;
    const help = document.getElementById("passwordHelp");

    if (pass !== cpass) {
        help.textContent = "❌ Passwords do not match!";
        return false;
    } else {
        help.textContent = "";
        return true;
    }
}

document.getElementById("cpassword").addEventListener("keyup", function () {
    const pass = document.getElementById("password").value;
    const cpass = this.value;
    const help = document.getElementById("passwordHelp");

    if (pass !== cpass) {
        help.textContent = "❌ Passwords do not match!";
    } else {
        help.textContent = "✅ Passwords match";
        help.classList.remove("text-danger");
        help.classList.add("text-success");
    }
});
</script>
</body>
</html>