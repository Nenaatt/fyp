<?php 
session_start();

// Direct database connection - NO CONFIG.PHP to avoid prepared statement issues
$conn = new mysqli("localhost", "root", "", "reservation_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

// 1. Define the correct, consistent vehicle list (indexed by ID)
$vehicles_list = [
    1 => ['id' => 1, 'name' => 'Sedan Car', 'seaters' => 4, 'price' => 350.00, 'extra_hour_rate' => 40],
    2 => ['id' => 2, 'name' => 'MPV', 'seaters' => 7, 'price' => 450.00, 'extra_hour_rate' => 50],
    3 => ['id' => 3, 'name' => 'Alphard/Vellfire', 'seaters' => 5, 'price' => 900.00, 'extra_hour_rate' => 80],
    4 => ['id' => 4, 'name' => 'Van', 'seaters' => 14, 'price' => 600.00, 'extra_hour_rate' => 60],
    5 => ['id' => 5, 'name' => 'Bus', 'seaters' => 44, 'price' => 1200.00, 'extra_hour_rate' => 150],
];

// 2. Load the full vehicle data into the session 
if (isset($_POST['vehicle_id']) || isset($_SESSION['vehicle_id'])) {
    $vehicle_id = (int)($_POST['vehicle_id'] ?? $_SESSION['vehicle_id']);
    $_SESSION['vehicle_id'] = $vehicle_id;

    if (isset($vehicles_list[$vehicle_id])) {
        $_SESSION['vehicle'] = $vehicles_list[$vehicle_id];
        $_SESSION['vehicle_name'] = $_SESSION['vehicle']['name'];
        $_SESSION['vehicle_price'] = $_SESSION['vehicle']['price'];
        $_SESSION['extra_hour_rate'] = $_SESSION['vehicle']['extra_hour_rate'];
    }
}

// 3. Redirect if vehicle details are missing
if (!isset($_SESSION['vehicle']) || !isset($_SESSION['pickup_location']) || !isset($_SESSION['destination'])) {
    header('Location: home.php');
    exit;
}

// Handle passenger details and payment method (POST request from Step 3 form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passenger_name'])) {
    
    // Input sanitization
    $passenger_name = trim($_POST['passenger_name']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $payment_method = $_POST['payment_method'] ?? '';
    $tourist_guide = isset($_POST['tourist_guide']) ? 1 : 0;
    $luggage_assistance = isset($_POST['luggage_assistance']) ? 1 : 0;

    // Validate payment method
    if (!in_array($payment_method, ['QR', 'Transfer'])) {
        $error_message = 'Please select a valid payment method (QR or Bank Transfer).';
    } else {
        // Clean inputs for security
        $passenger_name_clean = $conn->real_escape_string($passenger_name);
        $email_clean = $conn->real_escape_string($email);
        
        // Check if the user exists in the users table (using name and email) - WITHOUT PREPARED STATEMENTS
        $sql_user_check = "SELECT id FROM users WHERE email = '$email_clean' AND name = '$passenger_name_clean'";
        $result_user_check = $conn->query($sql_user_check);
        
        if ($result_user_check && $result_user_check->num_rows === 0) {
            // User not found, store form data in session and redirect to register.php
            $_SESSION['registration_data'] = [
                'passenger_name' => $passenger_name,
                'contact_number' => $contact_number,
                'email' => $email,
                'payment_method' => $payment_method,
                'tourist_guide' => $tourist_guide ? 'yes' : 'no',
                'luggage_assistance' => $luggage_assistance ? 'yes' : 'no'
            ];
            header('Location: register.php');
            exit;
        } else {
            // User exists, fetch user_id and auto-login if not already logged in
            if ($result_user_check && $row = $result_user_check->fetch_assoc()) {
                $user_id = $row['id'];
                if (!isset($_SESSION['user_id'])) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['name'] = $passenger_name;
                }
            }
        }

        // Proceed with booking logic if no error
        if (empty($error_message) && isset($_SESSION['booking_id'])) {
            $booking_id = (int)$_SESSION['booking_id'];
            
            // Save to session
            $_SESSION['passenger_name'] = $passenger_name;
            $_SESSION['contact_number'] = $contact_number;
            $_SESSION['email'] = $email;
            $_SESSION['payment_method'] = $payment_method;
            $_SESSION['tourist_guide'] = $tourist_guide ? 'yes' : 'no';
            $_SESSION['luggage_assistance'] = $luggage_assistance ? 'yes' : 'no';

            // Calculate total
            $vehicle_price = $_SESSION['vehicle']['price'] ?? 0.00;
            $total_amount = $vehicle_price + ($tourist_guide ? 100.00 : 0) + ($luggage_assistance ? 50.00 : 0);
            $_SESSION['total_amount'] = $total_amount;

            // Clean data for SQL
            $vehicle_name_clean = $conn->real_escape_string($_SESSION['vehicle']['name']);
            $passenger_name_clean = $conn->real_escape_string($passenger_name);
            $email_clean = $conn->real_escape_string($email);
            $contact_number_clean = $conn->real_escape_string($contact_number);
            $payment_method_clean = $conn->real_escape_string($payment_method);
            // Set payment_status: Transfer payments should be pending for admin confirmation
            $payment_status = 'Pending';
            $payment_status_clean = $conn->real_escape_string($payment_status);
            // Ensure we associate the booking with the logged-in user (if available)
            $user_id_to_save = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

            // Update database - USING DIRECT QUERY (NO PREPARED STATEMENTS)
            $update_sql = "UPDATE bookings SET 
                vehicle_name = '$vehicle_name_clean',
                vehicle_price = $vehicle_price,
                passenger_name = '$passenger_name_clean',
                email = '$email_clean',
                phone = '$contact_number_clean',
                payment_method = '$payment_method_clean',
                payment_status = '$payment_status_clean',
                user_id = $user_id_to_save,
                tourist_guide = $tourist_guide,
                luggage_assistance = $luggage_assistance,
                total_amount = $total_amount,
                status = 'Pending Payment'
                WHERE id = $booking_id";

            if ($conn->query($update_sql)) {
                // Redirect to payment
                if ($payment_method === 'QR') {
                    header('Location: payment_qr.php');
                    exit;
                } elseif ($payment_method === 'Transfer') {
                    header('Location: transfer_payment.php');
                    exit;
                }
            } else {
                error_log("Booking Update Error: " . $conn->error); 
                $error_message = 'An error occurred while saving your booking. Please try again.';
            }
        } else if (empty($error_message)) {
            $error_message = 'Booking session is invalid. Please start over.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 3 - Passenger Details - Global Sunrise Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; }
        .navbar { background-color: #003366 !important; }
        .text-primary { color: #003366 !important; }
        .btn-primary { background-color: #003366; border-color: #003366; }
        .btn-primary:hover { background-color: #002147; border-color: #002147; }
        .btn-outline-secondary { color: #003366; border-color: #003366; }
        .btn-outline-secondary:hover { background-color: #003366; color: white; }
        .step-progress { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #eee; }
        .step-item.completed .step-number { background-color: #ffd700 !important; color: #003366 !important; font-weight: bold; }
        .step-item.active .step-number { background-color: #003366 !important; color: white !important; }
        .step-item.active .step-label { color: #003366 !important; font-weight: bold; }
        .form-container { background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; border-left: 5px solid #003366; }
        .footer { background-color: #003366; color: #f8f9fa; padding: 20px 0; margin-top: 50px; }
        .footer a { color: #ffd700 !important; text-decoration: none; }
        .footer a:hover { color: #fff !important; }
        .service-checkbox { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 10px; transition: all 0.3s; }
        .service-checkbox:hover { border-color: #003366; background-color: #f8f9fa; }
        .service-checkbox.checked { border-color: #003366; background-color: #e8f4fd; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
                Global Sunrise Travel
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="step-progress">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="step-item completed">
                        <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">✓</div>
                        <div class="step-label mt-1 text-muted">Trip Details</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-item completed">
                        <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">✓</div>
                        <div class="step-label mt-1 text-muted">Select Vehicle</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-item active">
                        <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">3</div>
                        <div class="step-label mt-1 text-primary fw-bold">Passenger Details</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-item">
                        <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">4</div>
                        <div class="step-label mt-1 text-muted">Confirmation</div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-primary">Step 3: Passenger & Payment Details</h2>
        <p class="text-muted">Enter contact information, select payment method, and choose additional services.</p>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h5 class="text-primary">Trip Summary:</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Route:</strong> <?php echo htmlspecialchars($_SESSION['pickup_location'] ?? 'N/A'); ?> to <?php echo htmlspecialchars($_SESSION['destination'] ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>When:</strong> <?php echo htmlspecialchars($_SESSION['pickup_date'] ?? 'N/A'); ?> at <?php echo htmlspecialchars($_SESSION['pickup_time'] ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Vehicle:</strong> <?php echo htmlspecialchars($_SESSION['vehicle_name'] ?? 'N/A'); ?> (<?php echo htmlspecialchars($_SESSION['vehicle']['seaters'] ?? 'N/A'); ?> seaters)</p>
                    <p class="mb-0"><strong>Price:</strong> RM <?php echo number_format($_SESSION['vehicle']['price'] ?? 0, 2); ?> per trip</p>
                </div>
            </div>
        </div>

        <form method="POST" action="step3.php">
            <div class="form-container">
                <h5 class="text-primary mb-3">Passenger Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Full Name:</strong></label>
                        <input type="text" name="passenger_name" class="form-control" placeholder="Enter your full name" value="<?php echo htmlspecialchars($_SESSION['passenger_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Contact Number:</strong></label>
                        <input type="tel" name="contact_number" class="form-control" placeholder="e.g., 012-3456789" pattern="01[0-9]-[0-9]{7,8}" title="Phone number must be in the format 01X-XXXXXXX" value="<?php echo htmlspecialchars($_SESSION['contact_number'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Email Address:</strong></label>
                    <input type="email" name="email" class="form-control" placeholder="your.email@example.com" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                </div>
                
                <h5 class="text-primary mb-3 mt-4">Payment Method</h5>
                <div class="mb-4">
                    <label for="payment_method" class="form-label"><strong>Select Method:</strong></label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="">Choose your payment method</option> 
                        <option value="QR" <?php echo (($_SESSION['payment_method'] ?? '') === 'QR') ? 'selected' : ''; ?>>QR Payment (Instant verification)</option>
                        <option value="Transfer" <?php echo (($_SESSION['payment_method'] ?? '') === 'Transfer') ? 'selected' : ''; ?>>Bank Transfer (CIMB)</option>
                    </select>
                </div>

                <h5 class="text-primary mb-3">Additional Services</h5>
                <div class="mb-3">
                    <div class="service-checkbox">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tourist_guide" value="yes" id="tourist_guide" <?php echo (($_SESSION['tourist_guide'] ?? '') === 'yes') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="tourist_guide">
                                <strong>Tourist Guide</strong> - Professional guide service (Additional RM 100)
                            </label>
                        </div>
                    </div>
                    <div class="service-checkbox">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="luggage_assistance" value="yes" id="luggage_assistance" <?php echo (($_SESSION['luggage_assistance'] ?? '') === 'yes') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="luggage_assistance">
                                <strong>Luggage Assistance</strong> - Help with baggage handling (Additional RM 50)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Next → Payment/Booking Summary</button>
                <a href="step2.php" class="btn btn-outline-secondary">← Back to Vehicle Selection</a>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
            <p>
                <a href="contact.php" class="text-white me-3">Contact</a> |
                <a href="about us.php" class="text-white mx-3">About Us</a> |
                <a href="#" class="text-white ms-3">Terms</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add visual feedback for service checkboxes
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const serviceBox = this.closest('.service-checkbox');
                if (this.checked) {
                    serviceBox.classList.add('checked');
                } else {
                    serviceBox.classList.remove('checked');
                }
            });
            
            // Initialize checked state on page load
            if (checkbox.checked) {
                checkbox.closest('.service-checkbox').classList.add('checked');
            }
        });
    </script>
</body>
</html>
weyh try step3 ni