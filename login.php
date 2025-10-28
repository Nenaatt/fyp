<?php
session_start();
require_once 'config.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $name, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            // Update booking with user_id if a pending booking exists
            if (isset($_SESSION['booking_id'])) {
                $booking_id = (int)$_SESSION['booking_id'];
                $stmt_update = $conn->prepare("UPDATE bookings SET user_id = ? WHERE id = ?");
                $stmt_update->bind_param("ii", $user_id, $booking_id);
                $stmt_update->execute();
                $stmt_update->close();
            }
            // Redirect to the stored URL or dashboard based on role
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect_url");
                exit;
            } else {
                if ($role == 'admin') {
                    header('Location: admin/dashboard.php');
                    exit;
                } else {
                    header('Location: dashboard.php');
                    exit;
                }
            }
        } else {
            $error_message = 'Invalid email or password.';
        }
        $stmt->close();
    } else {
        $error_message = 'Invalid email or password.';
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Global Sunrise</title>
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
                    <a class="nav-link active" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-lg rounded-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Customer Login</h3>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        
        <p class="text-center mt-3">
            Don't have an account? <a href="register.php">Register here</a>
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
</body>
</html>