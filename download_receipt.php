<?php
session_start();
require_once 'config.php';
require('fpdf/fpdf.php');

// ----------------------------------------------------------------------
// STEP 1: Validate booking session
// ----------------------------------------------------------------------
if (!isset($_SESSION['booking_id'])) {
    die("No booking found. Please complete your booking first.");
}

$booking_id = $_SESSION['booking_id'];

// ----------------------------------------------------------------------
// STEP 2: Fetch booking details
// ----------------------------------------------------------------------
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();
$stmt->close();

// ----------------------------------------------------------------------
// STEP 3: Generate PDF receipt using FPDF
// ----------------------------------------------------------------------
class PDF extends FPDF {
    function Header() {
        // Centered logo
        if (file_exists('logo.png')) {
            $this->Image('logo.png', 90, 10, 30);
        }
        $this->Ln(25);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Global Sunrise Travel & Tours', 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 8, 'Official Booking Receipt', 0, 1, 'C');
        $this->Ln(8);
    }

    function Footer() {
        // Footer
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Thank you for choosing Global Sunrise Travel & Tours | www.globalsunrise.com.my', 0, 0, 'C');
    }

    // Draw a colored section header
    function SectionTitle($label) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(0, 51, 102); // deep blue
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 8, "  " . strtoupper($label), 0, 1, 'L', true);
        $this->Ln(2);
        $this->SetTextColor(0, 0, 0);
    }

    // Add a detail row
    function DetailRow($label, $value) {
        $this->SetFont('Arial', '', 11);
        $this->Cell(60, 8, $label, 1, 0, 'L');
        $this->Cell(0, 8, $value, 1, 1, 'L');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// ----------------------------------------------------------------------
// STEP 4: Build the content
// ----------------------------------------------------------------------
$pdf->SectionTitle('Booking Information');
$pdf->DetailRow('Booking ID', $booking['booking_id']);
$pdf->DetailRow('Status', $booking['status']);
$pdf->DetailRow('Booking Date', $booking['created_at']);
$pdf->Ln(4);

$pdf->SectionTitle('Passenger Information');
$pdf->DetailRow('Passenger Name', $booking['passenger_name']);
$pdf->DetailRow('Email', $booking['email']);
$pdf->DetailRow('Phone', $booking['phone']);
$pdf->Ln(4);

$pdf->SectionTitle('Trip Details');
$pdf->DetailRow('Pickup Location', $booking['pickup_location']);
$pdf->DetailRow('Destination', $booking['destination']);
$pdf->DetailRow('Pickup Date', $booking['pickup_date']);
$pdf->DetailRow('Pickup Time', $booking['pickup_time']);
$pdf->Ln(4);

$pdf->SectionTitle('Vehicle Details');
$pdf->DetailRow('Vehicle Name', $booking['vehicle_name']);
$pdf->DetailRow('Vehicle Price (per trip)', 'RM ' . number_format($booking['vehicle_price'], 2));
$pdf->DetailRow('Tourist Guide', $booking['tourist_guide'] ? 'Yes' : 'No');
$pdf->DetailRow('Luggage Assistance', $booking['luggage_assistance'] ? 'Yes' : 'No');
$pdf->Ln(4);

$pdf->SectionTitle('Payment Summary');
$pdf->DetailRow('Payment Method', $booking['payment_method']);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 51, 102);
$pdf->Cell(60, 10, 'Total Amount', 1, 0, 'L');
$pdf->Cell(0, 10, 'RM ' . number_format($booking['total_amount'], 2), 1, 1, 'L');
$pdf->SetTextColor(0, 0, 0);

// Add space before footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'For any inquiries, please contact our support team.', 0, 1, 'C');

// ----------------------------------------------------------------------
// STEP 5: Output PDF as download
// ----------------------------------------------------------------------
$pdf->Output('D', 'Booking_Receipt_' . $booking['booking_id'] . '.pdf');
exit;
?>
