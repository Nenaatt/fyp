<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_profile_pic = null;
$sql = "SELECT profile_picture FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $user_profile_pic = $row['profile_picture'];
}

// Fetch user's booking history
$bookings = [];
$query = "SELECT * FROM bookings WHERE user_id='$user_id' AND passenger_name IS NOT NULL AND passenger_name != '' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Reservations - Global Sunrise Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-card {
            transition: transform 0.2s;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .welcome-section {
            background: linear-gradient(135deg, #003366 0%, #003366 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .footer {
            background: #000000;
            color: white;
            padding: 50px 0 20px;
            margin-top: 50px;
        }
        .footer h5 {
            color: #3498db;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .footer-links a {
            color: #cccccc;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 8px;
        }
        .footer-links a:hover {
            color: #3498db;
        }
        .footer-contact i {
            width: 20px;
            margin-right: 10px;
            color: #3498db;
        }
        .social-icons a {
            color: white;
            background: #333333;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .social-icons a:hover {
            background: #3498db;
            transform: translateY(-3px);
        }
        .footer-bottom {
            border-top: 1px solid #333333;
            padding-top: 20px;
            margin-top: 30px;
        }
        .status-badge {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <?php if (!empty($user_profile_pic) && file_exists('uploads/' . $user_profile_pic)): ?>
                                <img src="Uploads/<?php echo htmlspecialchars($user_profile_pic); ?>" alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-3x"></i>
                            <?php endif; ?>
                        </div>
                        <h5><?php echo htmlspecialchars($_SESSION['name'] ?? 'Customer'); ?></h5>
                        <small>Manage your bookings</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="my_reservations.php">
                                <i class="fas fa-list me-2"></i> My Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">
                                <i class="fas fa-envelope me-2"></i> Contact Us
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold">My Reservations</h1>
                            <p class="lead mb-0">View your complete booking history and manage your reservations.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-calendar-check fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <!-- Booking History -->
                <div class="row">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Booking History</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($bookings)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Reservations Found</h4>
                                        <p class="text-muted">You haven't made any bookings yet.</p>
                                        <a href="home.php" class="btn btn-primary">Make Your First Booking</a>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Booking ID</th>
                                                    <th>Route</th>
                                                    <th>Vehicle</th>
                                                    <th>Date & Time</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($bookings as $booking): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($booking['booking_id']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($booking['pickup_location']); ?> → <?php echo htmlspecialchars($booking['destination']); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['vehicle_name']); ?></td>
                                                        <td>
                                                            <div><?php echo htmlspecialchars($booking['pickup_date']); ?></div>
                                                            <small class="text-muted"><?php echo htmlspecialchars($booking['pickup_time']); ?></small>
                                                        </td>
                                                        <td><strong>RM <?php echo number_format($booking['total_amount'], 2); ?></strong></td>
                                                        <td>
                                                            <?php
                                                            $status = strtolower($booking['status']);
                                                            $badge_class = 'bg-secondary';
                                                            if ($status == 'completed') $badge_class = 'bg-success';
                                                            elseif ($status == 'confirmed') $badge_class = 'bg-primary';
                                                            elseif ($status == 'pending') $badge_class = 'bg-warning';
                                                            elseif ($status == 'cancelled') $badge_class = 'bg-danger';
                                                            ?>
                                                            <span class="badge <?php echo $badge_class; ?> status-badge"><?php echo ucfirst($status); ?></span>
                                                        </td>
                                                        <td>
                                                            <a href="download_receipt.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i> Receipt
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Global Sunrise Transport</h5>
                    <p>Your trusted transportation partner for all your travel needs.</p>
                    <div class="footer-contact">
                        <p><i class="fas fa-phone"></i> +60 12-345 6789</p>
                        <p><i class="fas fa-envelope"></i> info@globalsunrise.com.my</p>
                        <p><i class="fas fa-map-marker-alt"></i> Kuala Lumpur, Malaysia</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <div class="footer-links">
                        <a href="home.php">Home</a>
                        <a href="about us.php">About Us</a>
                        <a href="contact.php">Contact</a>
                        <a href="terms.php">Terms & Conditions</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
                <p class="mb-0">
                    <a href="contact.php" class="text-white me-3">Contact</a> |
                    <a href="about us.php" class="text-white mx-3">About Us</a> |
                    <a href="terms.php" class="text-white ms-3">Terms</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>