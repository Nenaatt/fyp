<?php
session_start();
require_once 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vehicle_id'])) {
    $_SESSION['vehicle_id'] = (int)$_POST['vehicle_id'];
    header("Location: Step3.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['vehicle_id'])) {
    $_SESSION['trip_type'] = $_POST['trip_type'] ?? 'One-Way'; 
    $_SESSION['pickup_location'] = $_POST['pickup_location'] ?? '';
    $_SESSION['destination'] = $_POST['destination'] ?? '';
    $_SESSION['pickup_date'] = $_POST['pickup_date'] ?? '';
    $_SESSION['pickup_time'] = $_POST['pickup_time'] ?? '';
    $_SESSION['seater_requested'] = $_POST['seater'] ?? '';

    // ✅ Generate a unique booking_id (e.g., BK2025-1234)
    $booking_code = 'BK' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

    if ($conn) {
        $stmt = $conn->prepare("INSERT INTO bookings 
            (booking_id, status, pickup_location, destination, pickup_date, pickup_time, created_at)
            VALUES (?, 'Pending', ?, ?, ?, ?, NOW())");
        $stmt->bind_param(
            "sssss",
            $booking_code,
            $_SESSION['pickup_location'],
            $_SESSION['destination'],
            $_SESSION['pickup_date'],
            $_SESSION['pickup_time']
        );
        if ($stmt->execute()) {
            $_SESSION['booking_id'] = $stmt->insert_id; // store DB id for Step 3
        } else {
            error_log("Insert booking failed: " . $stmt->error);
        }
        $stmt->close();
    }
}

// Updated vehicles with 8-hour city tour pricing and corrected extra charges
$vehicles = [
    [
        'id' => 1, 
        'name' => 'Sedan Car', 
        'seaters' => 4, 
        'price' => 350, 
        'extra_hour_rate' => 40,
        'image' => 'sedan.jpg', 
        'type' => 'car'
    ],
    [
        'id' => 2, 
        'name' => 'MPV', 
        'seaters' => 7, 
        'price' => 450, 
        'extra_hour_rate' => 50,
        'image' => 'mpv.jpg', 
        'type' => 'mpv'
    ],
    [
        'id' => 3, 
        'name' => 'Alphard/Vellfire', 
        'seaters' => 5, 
        'price' => 900, 
        'extra_hour_rate' => 80,
        'image' => 'alphard.jpg', 
        'type' => 'luxury'
    ], 
    [
        'id' => 4, 
        'name' => 'Van', 
        'seaters' => 14, 
        'price' => 600, 
        'extra_hour_rate' => 60,
        'image' => 'van.jpg', 
        'type' => 'van'
    ],
    [
        'id' => 5, 
        'name' => 'Bus', 
        'seaters' => 44, 
        'price' => 1200, 
        'extra_hour_rate' => 150,
        'image' => 'bus.jpg', 
        'type' => 'bus'
    ], 
];

$requested_seaters = $_SESSION['seater_requested'] ?? 0;
$filtered_vehicles = array_filter($vehicles, function($vehicle) use ($requested_seaters) {
    return $vehicle['seaters'] >= $requested_seaters;
});

if ($requested_seaters == 0 || empty($filtered_vehicles)) {
    $filtered_vehicles = $vehicles;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 2 - Vehicle Selection - Global Sunrise Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 1200px; }
        .navbar { background-color: #003366 !important; }
        .navbar .nav-link.active, .navbar .nav-link:hover { color: #ffd700 !important; }
        .step-progress {
            background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #eee;
        }
        .step-item.active .step-number { background-color: #003366 !important; }
        .step-item.completed .step-number {
            background-color: #ffd700 !important; color: #003366 !important;
        }
        .form-container {
            background-color: white; padding: 30px; border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 30px;
            border-left: 5px solid #003366;
        }
        .card { transition: transform 0.2s; height: 100%; border: 1px solid #ddd; }
        .card:hover {
            transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #ffd700;
        }
        .vehicle-image { height: 180px; object-fit: cover; width: 100%; border-bottom: 1px solid #eee; }
        .btn-primary { background-color: #003366; border-color: #003366; }
        .btn-primary:hover { background-color: #002147; border-color: #002147; }
        .btn-outline-secondary { color: #003366; border-color: #003366; }
        .btn-outline-secondary:hover { background-color: #003366; color: white; }
        .text-primary { color: #003366 !important; }
        .footer { background-color: #003366; color: #f8f9fa; padding: 20px 0; margin-top: 50px; }
        .footer a { color: #ffd700 !important; text-decoration: none; }
        .footer a:hover { color: #fff !important; }
        .price-badge {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #003366;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 3px 8px rgba(255, 215, 0, 0.3);
        }
        .trip-type-indicator {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
            font-size: 1.1rem;
        }
        .duration-badge {
            background: #003366;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 10px;
        }
        .extra-charges-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .extra-charges-note i {
            color: #856404;
            margin-right: 8px;
        }
        .hourly-rate {
            background: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 10px;
            font-size: 0.85rem;
        }
        .hourly-rate strong {
            color: #003366;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
            Global Sunrise Travel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
           <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="step-progress">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="step-item completed">
                    <div class="step-number bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 30px; height: 30px;">✓</div>
                    <div class="step-label mt-1 text-muted">Trip Details</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-item active">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 30px; height: 30px;">2</div>
                    <div class="step-label mt-1 fw-bold text-primary">Select Vehicle</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-item">
                    <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 30px; height: 30px;">3</div>
                    <div class="step-label mt-1 text-muted">Passenger Details</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-item">
                    <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 30px; height: 30px;">4</div>
                    <div class="step-label mt-1 text-muted">Confirmation</div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-primary">Step 2: Select Your Vehicle</h2>
    <p class="text-muted">Choose the perfect vehicle for your city tour. You requested <strong><?php echo $_SESSION['seater_requested'] ?? 'an unspecified'; ?></strong> seats.</p>
    
    <!-- Trip Type Indicator -->
    <div class="trip-type-indicator">
        <i class="fas fa-map-marked-alt me-2"></i>8-Hour City Tour Package
    </div>
    
    <!-- Extra Charges Notice -->
    <div class="extra-charges-note">
        <i class="fas fa-info-circle"></i>
        <strong>Extra Charges Notice:</strong> Tours longer than 8 hours will incur additional charges of 
        <strong>RM 40/hour for Car</strong>, 
        <strong>RM 50/hour for MPV</strong>, 
        <strong>RM 80/hour for Alphard</strong>, 
        <strong>RM 60/hour for Van</strong>, and 
        <strong>RM 150/hour for Bus</strong>.
    </div>
    
    <div class="form-container">
        <div class="card-body">
            <h5 class="text-primary">Tour Summary:</h5>
            <p class="mb-1"><strong>Route:</strong> <?php echo $_SESSION['pickup_location'] ?? 'Not set'; ?> to <?php echo $_SESSION['destination'] ?? 'Not set'; ?></p>
            <p class="mb-1"><strong>Tour Date:</strong> <?php echo $_SESSION['pickup_date'] ?? 'Not set'; ?> at <?php echo $_SESSION['pickup_time'] ?? 'Not set'; ?></p>
            <p class="mb-0"><strong>Base Duration:</strong> 8 hours (extra hours charged separately)</p>
        </div>
    </div>
    
    <form method="POST" action="Step2.php">
        <div class="row">
            <?php foreach ($filtered_vehicles as $vehicle): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 position-relative">
                    <img src="<?php echo $vehicle['image']; ?>" class="vehicle-image" alt="<?php echo $vehicle['name']; ?>">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="price-badge">RM <?php echo number_format($vehicle['price'], 2); ?></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="duration-badge">
                            <i class="fas fa-clock me-1"></i>8 Hours Included
                        </div>
                        <h5 class="card-title text-primary"><?php echo $vehicle['name']; ?></h5>
                        <p class="card-text">
                            <strong><?php echo $vehicle['seaters']; ?> seaters</strong><br>
                            <small class="text-muted">Complete 8-hour city tour package</small><br>
                            <small class="text-muted">Ideal for up to <?php echo $vehicle['seaters']; ?> passengers</small>
                        </p>
                        
                        <!-- Extra Charges Information -->
                        <div class="hourly-rate">
                            <i class="fas fa-plus-circle text-primary me-1"></i>
                            <strong>Extra Hours:</strong> RM <?php echo number_format($vehicle['extra_hour_rate'], 2); ?>/hour
                            <br>
                            <small class="text-muted">Charged for tours exceeding 8 hours</small>
                        </div>
                        
                        <div class="mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vehicle_id" 
                                       value="<?php echo $vehicle['id']; ?>" required
                                       id="vehicle_<?php echo $vehicle['id']; ?>">
                                <label class="form-check-label text-primary fw-bold" for="vehicle_<?php echo $vehicle['id']; ?>">
                                    Select This Vehicle
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right me-2"></i>Next → Passenger Details
            </button>
            <a href="home.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>← Back to Tour Details
            </a>
        </div>
        
        <!-- Additional Information -->
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="text-primary mb-2"><i class="fas fa-clock me-2"></i>About Extra Hours Charges:</h6>
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-check text-success me-2"></i>Base package includes 8 hours of service</li>
                <li><i class="fas fa-check text-success me-2"></i>Extra hours are charged at hourly rates shown above</li>
                <li><i class="fas fa-check text-success me-2"></i>Minimum extra charge: 1 hour</li>
                <li><i class="fas fa-check text-success me-2"></i>Overnight charges may apply for extended tours</li>
                <li><i class="fas fa-check text-success me-2"></i>Final duration confirmed with driver on tour day</li>
            </ul>
        </div>
    </form>
</div>

<footer class="footer">
    <div class="container text-center">
        <p>&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
        <p>
            <a href="contact.php" class="text-white me-3">Contact</a> |
            <a href="about_us.php" class="text-white mx-3">About Us</a> |
            <a href="terms.php" class="text-white ms-3">Terms</a>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>