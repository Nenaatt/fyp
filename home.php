<?php 
session_start();
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Sunrise Travel & Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <style>
        body { background-color: #f8f9fa; }
        .hero-banner { 
            background: linear-gradient(135deg, #003366 0%, #003366 100%);
            color: white; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            position: relative; 
            padding: 60px 0;
        }
        .hero-image {
            max-width: 100%;
            height: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            margin: 20px 0;
            object-fit: cover;
        }
        .search-container { 
            background-color: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            margin-top: 30px; 
            position: relative; 
            z-index: 2; 
        }
        .footer { 
            background-color: #343a40; 
            color: #f8f9fa; 
            padding: 20px 0; 
            margin-top: 50px;
        }
        .hero-content {
            z-index: 2;
            position: relative;
        }
        
        /* Image Slider Styles */
        .slider-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        .slider-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            display: none;
        }
        .slider-image.active {
            display: block;
            animation: fadeIn 1s ease-in-out;
        }
        .slider-nav {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .slider-dot.active {
            background-color: white;
        }
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 3;
        }
        .slider-arrow:hover {
            background: rgba(0,0,0,0.8);
        }
        .slider-arrow.prev {
            left: 10px;
        }
        .slider-arrow.next {
            right: 10px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
		.search-box {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 30px 35px;
            max-width: 1150px;
            margin: -60px auto 0;
            border: 3px solid #ffd700;
            position: relative;
            z-index: 2;
        }

        .search-box h3 {
            color: #003366;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }
        
        .search-box h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #ffd700;
            border-radius: 3px;
        }
        
        .booking-note {
            background: #e7f3ff;
            border: 1px solid #003366;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .booking-note i {
            color: #003366;
            margin-right: 8px;
        }
        
        .booking-note strong {
            color: #003366;
        }

        label {
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #003366;
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.25);
        }
		
		.hero {
            background: linear-gradient(rgba(0, 51, 102, 0.85), rgba(0, 51, 102, 0.9));
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: white;
            padding: 50px 0 30px;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 25px;
            max-width: 800px;
        }
        
        .contact-banner {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #003366;
            padding: 20px 0;
            text-align: center;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            border: 2px solid #003366;
        }
        
        .contact-banner h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .contact-banner .contact-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .contact-banner .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        .whatsapp-btn {
            background: #25D366;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
        }
        
        .whatsapp-btn:hover {
            background: #128C7E;
            color: white;
            transform: translateY(-2px);
        }
        
        .phone-link {
            color: #003366;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            padding: 6px 12px;
            border: 2px solid #003366;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .phone-link:hover {
            background: #003366;
            color: #ffd700;
        }
        
        .slider-container {
            position: relative;
            max-width: 900px;
            margin: 20px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .slider-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            display: none;
        }
        .slider-image.active {
            display: block;
            animation: fadeIn 1.2s ease-in-out;
        }
        .slider-nav {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .slider-dot.active {
            background-color: white;
            transform: scale(1.2);
        }
        .slider-dot:hover {
            background-color: rgba(255,255,255,0.8);
        }
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 3;
            font-size: 1.2rem;
        }
        .slider-arrow:hover {
            background: rgba(0,0,0,0.9);
            transform: translateY(-50%) scale(1.1);
        }
        .slider-arrow.prev {
            left: 20px;
        }
        .slider-arrow.next {
            right: 20px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn-search {
            background-color: #003366;
            border-color: #003366;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-search:hover {
            background-color: #002147;
            border-color: #002147;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        
        .features-section {
            margin-top: 80px;
        }
        
        .features-section .card {
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }
        
        .features-section .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.1) !important;
        }
        
        .features-section .card-body {
            padding: 30px 20px;
        }
        
        .features-section i {
            margin-bottom: 20px;
        }
        
        .features-section h5 {
            color: #003366;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .features-section p {
            color: #555;
            line-height: 1.6;
        }
        
        .footer {
            background-color: #003366 !important;
            color: #fff;
            padding: 30px 0;
            margin-top: 100px;
            text-align: center;
        }

        .footer a {
            color: #ffd700 !important;
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }
        .footer a:hover {
            color: #fff !important;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .text-primary {
            color: #003366 !important;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .slider-image {
                height: 300px;
            }
            
            .search-box {
                padding: 20px;
                margin-top: -40px;
            }
            
            .slider-arrow {
                width: 40px;
                height: 40px;
            }
            
            .contact-banner .contact-info {
                flex-direction: column;
                gap: 10px;
            }
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
                    <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking.php">Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-banner">
        <div class="container">
            <div class="hero-content">
                <h1 class="display-4 fw-bold mb-3">Welcome to Global Sunrise Travel & Tours</h1>
                <p class="lead mb-4">Your journey begins here. Experience comfortable and reliable transportation services.</p>
                
                <!-- Image Slider -->
                <div class="slider-container">
                    <button class="slider-arrow prev" onclick="changeSlide(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <!-- Replace these image sources with your actual 6 images -->
                    <img src="pic3.jpg" alt="Transport Vehicle 1" class="slider-image active">
                    <img src="global.jpg" alt="Transport Vehicle 2" class="slider-image">
                    <img src="van.jpg" alt="Transport Vehicle 3" class="slider-image">
                    <img src="sedan.jpg" alt="Transport Vehicle 4" class="slider-image">
                    <img src="rv.jpg" alt="Transport Vehicle 5" class="slider-image">
                    <img src="bus.jpg" alt="Transport Vehicle 6" class="slider-image">
                    <img src="alphard.jpg" alt="Transport Vehicle 6" class="slider-image">
					<img src="mpv.jpg" alt="Transport Vehicle 6" class="slider-image">
					<img src="pic3.jpg" alt="Transport Vehicle 6" class="slider-image">
					<img src="city.jpg" alt="Transport Vehicle 6" class="slider-image">
					<img src="alza.jpg" alt="Transport Vehicle 6" class="slider-image">
					<img src="pic4.jpg" alt="Transport Vehicle 6" class="slider-image">
					
                    <button class="slider-arrow next" onclick="changeSlide(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="slider-nav">
                        <div class="slider-dot active" onclick="currentSlide(1)"></div>
                        <div class="slider-dot" onclick="currentSlide(2)"></div>
                        <div class="slider-dot" onclick="currentSlide(3)"></div>
                        <div class="slider-dot" onclick="currentSlide(4)"></div>
                        <div class="slider-dot" onclick="currentSlide(5)"></div>
                        <div class="slider-dot" onclick="currentSlide(6)"></div>
                    </div>
                </div>
                
                <p class="mt-3">Trusted by thousands for safe and comfortable journeys across Malaysia</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="search-box"> 
            <h3 class="text-center mb-2">Book Your City Tour Transportation</h3>
            <p class="text-center text-muted mb-3">Complete city tour packages with professional drivers</p>
            
            <div class="booking-note">
                <i class="fas fa-info-circle"></i>
                <strong>Note:</strong> This booking is for <strong>city tour trips only</strong>. 
                For long distance trips or custom packages, please contact our admin directly.
            </div>
            
            <form action="step2.php" method="POST">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label for="pickup">Pick-up Location</label>
                        <input type="text" id="pickup" name="pickup_location" class="form-control" 
                               placeholder="Enter pick-up location" required>
                        <small class="form-text text-muted">e.g., Kuala Lumpur City Center</small>
                    </div>
                    <div class="col-md-3">
                        <label for="destination">Drop-off Location</label>
                        <input type="text" id="destination" name="destination" class="form-control" 
                               placeholder="Enter drop-off location" required>
                        <small class="form-text text-muted">e.g., Same as pick-up (City Tour)</small>
                    </div>
                    <div class="col-md-2">
                        <label for="pickup_date">Tour Date</label>
                        <input type="date" id="pickup_date" name="pickup_date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label for="pickup_time">Start Time</label>
                        <input type="time" id="pickup_time" name="pickup_time" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-search me-2"></i>Check Availability
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="contact-banner">
            <h4><i class="fas fa-info-circle me-2"></i>For Long Distance Trips & Custom Packages</h4>
            <p class="mb-2">Contact our admin directly for interstate travel, multi-day packages, and special requests</p>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:0124895876" class="phone-link">012-489 5876 (Admin)</a>
                </div>
                <div class="contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <a href="https://wa.me/60124895876?text=Hi,%20I'm%20interested%20in%20long%20distance%20trip%20or%20custom%20package" 
                       target="_blank" class="whatsapp-btn">
                        <i class="fab fa-whatsapp"></i> WhatsApp Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container features-section">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Safe & Secure</h5>
                        <p>Your safety is our top priority with well-maintained vehicles and professional drivers.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-success mb-3"></i>
                        <h5>8-Hour City Tours</h5>
                        <p>Complete day packages with flexible itineraries and experienced tour guides.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x text-warning mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p>Round-the-clock customer support to assist you with any queries during your trip.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2025 Global Sunrise Travel & Tours. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="contact.php">Contact</a>
                <a href="about_us.php">About Us</a>
                <a href="terms.php">Terms & Conditions</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentSlideIndex = 1;
        let slideInterval;
        
        // Initialize the slider
        showSlides(currentSlideIndex);
        
        // Start automatic sliding
        startAutoSlide();
        
        function startAutoSlide() {
            slideInterval = setInterval(() => {
                changeSlide(1);
            }, 5000);
        }
        
        function changeSlide(n) {
            showSlides(currentSlideIndex += n);
        }
        
        function currentSlide(n) {
            showSlides(currentSlideIndex = n);
        }
        
        function showSlides(n) {
            const slides = document.getElementsByClassName("slider-image");
            const dots = document.getElementsByClassName("slider-dot");
            
            if (n > slides.length) { currentSlideIndex = 1; }
            if (n < 1) { currentSlideIndex = slides.length; }
            
            // Hide all slides
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");
            }
            
            // Remove active class from all dots
            for (let i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }
            
            // Show current slide and activate corresponding dot
            slides[currentSlideIndex - 1].classList.add("active");
            if (dots[currentSlideIndex - 1]) {
                dots[currentSlideIndex - 1].classList.add("active");
            }
        }
        
        // Pause auto-slide when user interacts with slider
        function pauseAutoSlide() {
            clearInterval(slideInterval);
        }
        
        function resumeAutoSlide() {
            startAutoSlide();
        }
        
        // Add event listeners for manual navigation
        document.querySelector('.slider-container').addEventListener('mouseenter', pauseAutoSlide);
        document.querySelector('.slider-container').addEventListener('mouseleave', resumeAutoSlide);
        
        // Also pause when user clicks on dots
        const dots = document.getElementsByClassName("slider-dot");
        for (let i = 0; i < dots.length; i++) {
            dots[i].addEventListener('click', function() {
                pauseAutoSlide();
                setTimeout(resumeAutoSlide, 10000);
            });
        }
        
        // Pause when using arrow buttons
        const arrows = document.getElementsByClassName("slider-arrow");
        for (let i = 0; i < arrows.length; i++) {
            arrows[i].addEventListener('click', function() {
                pauseAutoSlide();
                setTimeout(resumeAutoSlide, 10000);
            });
        }
        
        // Set minimum date to today for the date picker
        document.getElementById('pickup_date').min = new Date().toISOString().split("T")[0];
    </script>
</body>
</html>
