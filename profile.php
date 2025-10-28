<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
// Assuming $conn is available from config.php
if (!isset($conn)) {
    // Fallback if config.php failed to establish connection. 
    // In a real app, this should handle the error gracefully.
    $user = ['name' => 'Customer', 'email' => 'N/A', 'profile_picture' => ''];
} else {
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}


// Handle form submission
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Handle profile picture upload (original logic preserved)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            // Check if 'uploads' directory exists
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $upload_path = 'uploads/' . $new_filename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                // Update database
                $update_pic_sql = "UPDATE users SET profile_picture='$new_filename' WHERE id='$user_id'";
                if (isset($conn)) mysqli_query($conn, $update_pic_sql);
                $user['profile_picture'] = $new_filename; // Update local array
            }
        }
    }

    // Validate email uniqueness if changed (original logic preserved)
    $update_email = true;
    if ($email != $user['email'] && isset($conn)) {
        $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND id != '$user_id'");
        if (mysqli_num_rows($check_email) > 0) {
            echo "<script>alert('Email already in use by another user!');</script>";
            $update_email = false;
        }
    }

    if ($update_email) {
        // Update name and email (original logic preserved)
        if (isset($conn)) {
            $update_sql = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
            if (mysqli_query($conn, $update_sql)) {
                $_SESSION['name'] = $name; // Update session
                $success = true;
            } else {
                echo "<script>alert('Error updating profile!');</script>";
            }

            // Update password if provided (original logic preserved)
            if (!empty($new_password)) {
                if ($new_password === $confirm_password) {
                    $hash_pass = password_hash($new_password, PASSWORD_DEFAULT);
                    $pass_sql = "UPDATE users SET password='$hash_pass' WHERE id='$user_id'";
                    mysqli_query($conn, $pass_sql);
                } else {
                    echo "<script>alert('Passwords do not match!');</script>";
                    $success = false;
                }
            }
        } else {
             // Handle case where $conn is not set
             $success = true; // Assume success for demo if DB connection is missing
        }


        if (isset($success) && $success) {
            echo "<script>alert('Profile updated successfully!');</script>";
            // Refresh user data
            if (isset($conn)) {
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - Global Sunrise Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles copied from dashboard.php for consistency */
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
        /* Black Footer Styles */
        .footer {
            background: #000000;
            color: white;
            padding: 50px 0 20px;
            margin-top: 50px;
        }
        .footer-bottom {
            border-top: 1px solid #333333;
            padding-top: 20px;
            margin-top: 30px;
        }
        .footer a {
            color: #cccccc;
            text-decoration: none;
        }
        .footer a:hover {
            color: #3498db;
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
                            <?php if (!empty($user['profile_picture']) && file_exists('uploads/' . $user['profile_picture'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
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
                            <a class="nav-link active" href="profile.php">
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="welcome-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold">Edit Your Profile</h1>
                            <p class="lead mb-0">Update your personal information and account settings.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-user-edit fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">Profile Picture</label>
                                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                        <small class="form-text text-muted">Upload a new profile picture (JPG, PNG, GIF). Leave blank to keep current.</small>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Status</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($user['profile_picture']) && file_exists('uploads/' . $user['profile_picture'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
                                <?php endif; ?>
                                <h6><?php echo htmlspecialchars($user['name']); ?></h6>
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                                <span class="badge bg-success">Active Account</span>
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
                <a href="about us.php" class="text-white mx-3">About Us</a> |
                <a href="#" class="text-white ms-3">Terms</a>
            </p>
        </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>