<?php 
session_start();
require_once 'config.php';

// Check if the booking process was completed
if (!isset($_SESSION['booking_id'])) {
    header('Location: step1.php');
    exit;
}

$bookingId = $_SESSION['booking_id'];

// Vehicle data (must match step2.php)
$vehicles = [
    1 => ['id' => 1, 'name' => 'Sedan Car', 'seaters' => 4, 'price' => 350.00, 'extra_hour_rate' => 40],
    2 => ['id' => 2, 'name' => 'MPV', 'seaters' => 7, 'price' => 450.00, 'extra_hour_rate' => 50],
    3 => ['id' => 3, 'name' => 'Alphard/Vellfire', 'seaters' => 5, 'price' => 900.00, 'extra_hour_rate' => 80],
    4 => ['id' => 4, 'name' => 'Van', 'seaters' => 14, 'price' => 600.00, 'extra_hour_rate' => 60],
    5 => ['id' => 5, 'name' => 'Bus', 'seaters' => 44, 'price' => 1200.00, 'extra_hour_rate' => 150],
];

// Fetch selected vehicle
$selectedVehicleId = $_SESSION['vehicle_id'] ?? null;
$selectedVehicle = $selectedVehicleId ? ($vehicles[$selectedVehicleId] ?? null) : null;

// Calculate pricing
$basePrice = $selectedVehicle['price'] ?? 0;
$touristGuidePrice = ($_SESSION['tourist_guide'] ?? '') === 'yes' ? 100 : 0;
$luggagePrice = ($_SESSION['luggage_assistance'] ?? '') === 'yes' ? 50 : 0;
$totalPrice = $basePrice + $touristGuidePrice + $luggagePrice;

// Payment details
$paymentMethod = $_SESSION['payment_method'] ?? 'Not Specified';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .booking-id { font-size: 1.5rem; font-weight: bold; color: #198754; }
        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card border-success shadow">
        <div class="card-header bg-success text-white text-center">
            <h2><i class="fas fa-check-circle me-2"></i>Booking Confirmed!</h2>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="alert alert-success">
                    <h4 class="booking-id">Booking ID: <?php echo htmlspecialchars($bookingId); ?></h4>
                    <p>Your booking has been successfully confirmed.</p>
                </div>
            </div>

            <div class="row">
                <!-- Trip Details -->
                <div class="col-md-6">
                    <h5><i class="fas fa-route me-2"></i>Trip Details</h5>
                    <table class="table table-bordered">
                        <tr><th>Booking ID</th><td><?php echo htmlspecialchars($bookingId); ?></td></tr>
                        <tr><th>Vehicle</th><td><?php echo $selectedVehicle ? "{$selectedVehicle['name']} ({$selectedVehicle['seaters']} seaters)" : 'Not selected'; ?></td></tr>
                        <tr><th>Route</th><td><?php echo ($_SESSION['pickup_location'] ?? 'Not set') . ' → ' . ($_SESSION['destination'] ?? 'Not set'); ?></td></tr>
                        <tr><th>Date & Time</th><td><?php echo ($_SESSION['pickup_date'] ?? 'Not set') . ' at ' . ($_SESSION['pickup_time'] ?? 'Not set'); ?></td></tr>
                        <tr><th>Passengers</th><td><?php echo $_SESSION['passenger_count'] ?? 'Not set'; ?></td></tr>
                        <tr><th>Contact Person</th><td><?php echo $_SESSION['passenger_name'] ?? 'Not set'; ?></td></tr>
                        <tr><th>Contact Number</th><td><?php echo $_SESSION['contact_number'] ?? 'Not set'; ?></td></tr>
                        <tr><th>Email</th><td><?php echo $_SESSION['email'] ?? 'Not set'; ?></td></tr>
                    </table>
                </div>

                <!-- Payment Details -->
                <div class="col-md-6">
                    <h5><i class="fas fa-receipt me-2"></i>Payment Details</h5>
                    <table class="table table-bordered">
                        <tr><th>Base Price</th><td>RM <?php echo number_format($basePrice, 2); ?></td></tr>
                        <?php if ($touristGuidePrice): ?><tr><th>Tourist Guide</th><td>RM 100.00</td></tr><?php endif; ?>
                        <?php if ($luggagePrice): ?><tr><th>Luggage Assistance</th><td>RM 50.00</td></tr><?php endif; ?>
                        <tr><th>Total Paid</th><td><strong>RM <?php echo number_format($totalPrice, 2); ?></strong></td></tr>
                        <tr><th>Payment Method</th><td><?php echo htmlspecialchars($paymentMethod); ?></td></tr>
                        <tr><th>Status</th><td><span class="badge bg-success">Confirmed</span></td></tr>
                    </table>
                </div>
            </div>

            <div class="text-center mt-4 no-print">
                <div class="btn-group flex-wrap">
                    <button class="btn btn-primary" onclick="downloadReceipt()"><i class="fas fa-download me-2"></i>Download Receipt</button>
                    <button class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print me-2"></i>Print</button>
                    <a href="home.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>New Booking</a>
                    <a href="dashboard.php" class="btn btn-outline-dark"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function downloadReceipt() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'download_receipt.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'booking_id';
    input.value = '<?php echo $bookingId; ?>';
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
</body>
</html>
