<?php session_start();
require_once 'config.php';

// Redirect back if vehicle or passenger details are missing
if (!isset($_SESSION['vehicle']) || !isset($_SESSION['passenger_name'])) {
    header('Location: step3.php');
    exit;
}

// Calculate total amount (base vehicle price + add-ons)
$total_price = $_SESSION['vehicle']['price'] ?? 0;
$tourist_guide_cost = ($_SESSION['tourist_guide'] ?? 'no') == 'yes' ? 100 : 0;
$luggage_assistance_cost = ($_SESSION['luggage_assistance'] ?? 'no') == 'yes' ? 50 : 0;

$total_payable = $total_price + $tourist_guide_cost + $luggage_assistance_cost;

// Store final calculated price in session for confirmation step
$_SESSION['total_payable'] = $total_payable;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 4 - Bank Transfer Payment - Global Sunrise Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Consistent Styling */
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; }
        .navbar { background-color: #003366 !important; }
        .navbar .nav-link:hover { color: #ffd700 !important; }
        .text-primary { color: #003366 !important; }
        .btn-primary { background-color: #003366; border-color: #003366; }
        .btn-primary:hover { background-color: #002147; border-color: #002147; }
        .footer { background-color: #003366; color: #f8f9fa; padding: 20px 0; margin-top: 50px; }
        .footer a { color: #ffd700 !important; text-decoration: none; }
        
        .payment-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            border-top: 5px solid #003366;
        }
        .bank-details p {
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        .bank-details strong {
            display: inline-block;
            width: 150px; /* Align details neatly */
        }
    </style>
</head>
<body>

    <!-- Header (Copied from step3.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
                Global Sunrise Travel
            </a>
            <!-- Navbar Toggler and Links removed for brevity but keep the structure -->
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-primary mb-4 text-center">Payment via Bank Transfer</h2>
        <p class="text-muted text-center lead">Please transfer the total amount into the account details below. Your booking will be confirmed once payment is verified.</p>
        
        <div class="row mt-5">
            <div class="col-lg-6 mx-auto">
                <div class="payment-card">
                    <h4 class="text-primary mb-4">Transfer Details</h4>
                    
                    <div class="bank-details mb-4 p-3 bg-light rounded">
                        <p><strong>Bank Name:</strong> CIMB Bank Berhad</p>
                        <p><strong>Account Holder:</strong> Global Sunrise Travel & Tours Sdn. Bhd.</p>
                        <p><strong>Account Number:</strong> 800-1234567-8900</p>
                        <p><strong>Reference:</strong> Please use your Email/Contact number</p>
                    </div>

                    <div class="alert alert-info text-center" role="alert">
                        <h4>Total Amount Due: <span class="text-danger fw-bold">RM <?php echo number_format($total_payable, 2); ?></span></h4>
                    </div>
                    
                    <h5 class="mt-4">Important Notes:</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Email the transfer receipt to **payment@globalsunrise.com** immediately after transfer.</li>
                        <li class="list-group-item">Please allow up to 1 hour for verification during business hours.</li>
                        <li class="list-group-item">Your final booking confirmation will be sent to: **<?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?>**</li>
                    </ul>

                    <a href="confirmation.php" class="btn btn-primary btn-lg d-block mt-4">I Have Made the Transfer → View Booking Summary</a>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Footer (Copied from step3.php) -->
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
</body>
</html>