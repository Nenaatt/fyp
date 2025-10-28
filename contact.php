<?php
session_start();
include 'config.php';
$user_profile_pic = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT profile_picture FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $user_profile_pic = $row['profile_picture'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Global Sunrise Travel&Tours</title>
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
        .action-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            height: 100%;
        }

        /* Black Footer Styles */
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
    </style>
</head>
<body>

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
            <nav class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <?php if (!empty($user_profile_pic) && file_exists('uploads/' . $user_profile_pic)): ?>
                                <img src="uploads/<?php echo htmlspecialchars($user_profile_pic); ?>" alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
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
                            <a class="nav-link active" href="contact.php">
                                <i class="fas fa-envelope me-2"></i> Contact Us
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                
                <?php 
                if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php 
                if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="welcome-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold">Contact Us</h1>
                            <p class="lead mb-0">Get in touch with our team for any inquiries or support.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-envelope fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Phone:</strong> 012-489 5876</p>
                                <p><strong>Email:</strong> globalsunrise@gmail.com</p>
                                <p><strong>Address:</strong> Tingkat 2, Pusat Perniagaan Pekan Lama, No. 68, Jalan Sekerat, 08000, Sungai Petani, Kedah, Malaysia.</p>
                                <p><strong>Hours:</strong> Mon - Fri, 9am - 6pm</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Contact Form</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="process_contact.php">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" id="name" name="name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" id="subject" name="subject" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
                <p class="mb-0">
                    <a href="contact.php" class="text-white me-3">Contact</a> |
                    <a href="about_us.php" class="text-white mx-3">About Us</a> |
                    <a href="terms.php" class="text-white ms-3">Terms</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>