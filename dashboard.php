<?php
session_start();
require_once 'config.php';

// Check if the database connection is valid
if (!$conn || $conn->connect_error) {
    error_log("Database connection failed: " . ($conn ? $conn->connect_error : "Connection not established"));
    die("Database connection failed. Please try again later.");
}

$user_profile_pic = null;
$bookings = [];
if (isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    
    // Fetch user profile picture
    $sql = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed for profile picture query: " . $conn->error);
        die("Error preparing profile picture query. Please contact support.");
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        error_log("Execute failed for profile picture query: " . $stmt->error);
        die("Error executing profile picture query. Please try again.");
    }
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user_profile_pic = $row['profile_picture'];
    }
    $stmt->close();

    // Fetch recent bookings
	$sql_bookings = "SELECT booking_id, vehicle_name, pickup_location, destination, status 
						 FROM bookings 
						 WHERE user_id = ? 
						 ORDER BY pickup_date DESC 
						 LIMIT 3";
    $stmt_bookings = $conn->prepare($sql_bookings);
    if ($stmt_bookings === false) {
        error_log("Prepare failed for bookings query: " . $conn->error);
        die("Error preparing bookings query. Please contact support.");
    }
    $stmt_bookings->bind_param("i", $user_id);
    if (!$stmt_bookings->execute()) {
        error_log("Execute failed for bookings query: " . $stmt_bookings->error);
        die("Error executing bookings query. Please try again.");
    }
    $bookings_result = $stmt_bookings->get_result();
    $bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
    $stmt_bookings->close();
} else {
    // Redirect to login if not logged in
    $_SESSION['redirect_after_login'] = 'dashboard.php';
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard - Global Sunrise Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-card { 
            transition: transform 0.2s; 
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px #002147;
        }
        .dashboard-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 15px #002147;
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
        .stat-card {
            border-radius: 15px;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .welcome-section {
            background: linear-gradient(135deg, #003366 0%, #003366 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .action-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            height: 100%;
        }
        .footer {
            background: #002147;
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
        /* Status badge colors */
        .badge-confirmed { background-color: #ffc107 !important; } /* Yellow for Confirmed */
        .badge-accepted { background-color: #28a745 !important; } /* Green for Accepted */
        .badge-pending { background-color: #dc3545 !important; } /* Red for Pending */
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
                            <?php if (!empty($user_profile_pic) && file_exists('Uploads/' . $user_profile_pic)): ?>
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
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="my_reservations.php">
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
                            <h1 class="display-6 fw-bold">Welcome Back!</h1>
                            <p class="lead mb-0">Ready to book your next journey? Manage all your transportation needs in one place.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-bus fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <h3 class="mb-4">Quick Actions</h3>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card border-0">
                            <div class="card-body action-card" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); color: white;">
                                <i class="fas fa-history fa-4x mb-3 opacity-75"></i>
                                <h4>My Reservations</h4>
                                <p class="mb-3">View your booking history and status</p>
                                <a href="my_reservations.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-list me-2"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <?php if (!empty($bookings)): ?>
                                        <?php foreach ($bookings as $booking): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <?php
                                                    $status = strtolower($booking['status']);
                                                    $icon_class = '';
                                                    if ($status === 'confirmed') {
                                                        $icon_class = 'fas fa-check-circle text-warning';
                                                    } elseif ($status === 'accepted') {
                                                        $icon_class = 'fas fa-check-circle text-success';
                                                    } elseif ($status === 'pending') {
                                                        $icon_class = 'fas fa-clock text-danger';
                                                    } else {
                                                        $icon_class = 'fas fa-question-circle text-secondary'; // Fallback for unknown status
                                                    }
                                                    ?>
                                                    <i class="<?php echo $icon_class; ?> me-2"></i>
                                                    <strong><?php echo htmlspecialchars($booking['booking_id']); ?></strong> - 
                                                    <?php echo htmlspecialchars($booking['vehicle_name']); ?> from 
                                                    <?php echo htmlspecialchars($booking['pickup_location']); ?> to 
                                                    <?php echo htmlspecialchars($booking['destination']); ?>
                                                </div>
                                                <span class="badge <?php echo $status === 'confirmed' ? 'badge-confirmed' : ($status === 'accepted' ? 'badge-accepted' : 'badge-pending'); ?>">
                                                    <?php echo htmlspecialchars(ucfirst($booking['status'])); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="list-group-item text-center">
                                            <p class="text-muted">No recent bookings found.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-bottom text-center">
            <p class="mb-0">&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
            <p>
                <a href="contact.php" class="text-white me-3">Contact</a> |
                <a href="about.php" class="text-white mx-3">About Us</a> |
                <a href="terms.php" class="text-white ms-3">Terms</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>