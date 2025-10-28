<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Global Sunrise Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .terms-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
            margin-bottom: 50px;
        }
        .terms-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            border-radius: 15px 15px 0 0;
        }
        .section-title {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .highlight-box {
            background: #e8f4fd;
            border-left: 4px solid #3498db;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #3498db;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        .back-to-top:hover {
            background: #2980b9;
            transform: translateY(-3px);
        }
        .nav-pills .nav-link {
            color: #2c3e50;
            border: 1px solid #dee2e6;
            margin: 5px 0;
            border-radius: 8px;
        }
        .nav-pills .nav-link.active {
            background: #3498db;
            border-color: #3498db;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-bus me-2"></i>Global Sunrise Transport&Tours
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

    <div class="container">
        <div class="terms-container">
            <!-- Header -->
            <div class="terms-header text-center">
                <div class="container">
                    <h1 class="display-4 fw-bold"><i class="fas fa-file-contract me-3"></i>Terms of Service</h1>
                </div>
            </div>

            <div class="container py-5">
                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 mb-4">
                        <div class="sticky-top" style="top: 20px;">
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link active" href="#acceptance">1. Acceptance</a>
                                <a class="nav-link" href="#services">2. Services</a>
                                <a class="nav-link" href="#booking">3. Booking</a>
                                <a class="nav-link" href="#payments">4. Payments</a>
                                <a class="nav-link" href="#cancellation">5. Cancellation</a>
                                <a class="nav-link" href="#liability">6. Liability</a>
                                <a class="nav-link" href="#privacy">7. Privacy</a>
                                <a class="nav-link" href="#changes">8. Changes</a>
                            </nav>
                        </div>
                    </div>

                    <!-- Terms Content -->
                    <div class="col-lg-9">
                        <!-- Introduction -->
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Important Notice</h5>
                            <p class="mb-0">Please read these terms carefully before using our services. By booking with Global Sunrise Transport, you agree to be bound by these terms and conditions.</p>
                        </div>

                        <!-- Section 1: Acceptance -->
                        <section id="acceptance" class="mb-5">
                            <h2 class="section-title">1. Acceptance of Terms</h2>
                            <p>By accessing and using the Global Sunrise Transport booking system, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
                            <div class="highlight-box">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                                <p class="mb-0">If you do not agree with any part of these terms, you must not use our services or make any bookings through our platform.</p>
                            </div>
                        </section>

                        <!-- Section 2: Services -->
                        <section id="services" class="mb-5">
                            <h2 class="section-title">2. Services Description</h2>
                            <p>Global Sunrise Transport provides transportation services including but not limited to:</p>
                            <ul>
                                <li>Airport transfers and pickups</li>
                                <li>City-to-city transportation</li>
                                <li>Corporate travel services</li>
                                <li>Tourist transportation</li>
                                <li>Special event transportation</li>
                            </ul>
                            <p>We reserve the right to modify, suspend, or discontinue any service at any time without prior notice.</p>
                        </section>

                        <!-- Section 3: Booking -->
                        <section id="booking" class="mb-5">
                            <h2 class="section-title">3. Booking and Reservations</h2>
                            <h5>3.1 Booking Process</h5>
                            <p>All bookings must be made through our official website or authorized agents. You must provide accurate and complete information during the booking process.</p>
                            
                            <h5>3.2 Confirmation</h5>
                            <p>Bookings are confirmed only after:</p>
                            <ul>
                                <li>Successful payment processing</li>
                                <li>Receipt of booking confirmation email</li>
                                <li>Availability of the requested vehicle</li>
                            </ul>
                            
                            <h5>3.3 Vehicle Capacity</h5>
                            <p>You must select vehicles appropriate for your passenger count. Overloading vehicles is strictly prohibited and may result in service cancellation without refund.</p>
                        </section>

                        <!-- Section 4: Payments -->
                        <section id="payments" class="mb-5">
                            <h2 class="section-title">4. Payments and Fees</h2>
                            <h5>4.1 Payment Methods</h5>
                            <p>We accept the following payment methods:</p>
                            <ul>
                                <li>Online banking transfers</li>
                                <li>Credit/Debit cards</li>
                                <li>E-wallet payments</li>
                                <li>Cash (subject to approval)</li>
                            </ul>
                            
                            <h5>4.2 Pricing</h5>
                            <p>All prices are quoted in Malaysian Ringgit (RM) and include:</p>
                            <ul>
                                <li>Vehicle rental</li>
                                <li>Professional driver</li>
                                <li>Basic insurance coverage</li>
                            </ul>
                            <p>Additional charges may apply for:</p>
                            <ul>
                                <li>Toll fees</li>
                                <li>Parking fees</li>
                                <li>Additional waiting time</li>
                                <li>Extra services (tourist guide, luggage assistance)</li>
                            </ul>
                        </section>

                        <!-- Section 5: Cancellation -->
                        <section id="cancellation" class="mb-5">
                            <h2 class="section-title">5. Cancellation and Refunds</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Cancellation Time</th>
                                            <th>Refund Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>More than 48 hours before trip</td>
                                            <td>Full refund (100%)</td>
                                        </tr>
                                        <tr>
                                            <td>24-48 hours before trip</td>
                                            <td>50% refund</td>
                                        </tr>
                                        <tr>
                                            <td>Less than 24 hours before trip</td>
                                            <td>No refund</td>
                                        </tr>
                                        <tr>
                                            <td>No-show</td>
                                            <td>No refund + additional charges may apply</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <!-- Section 6: Liability -->
                        <section id="liability" class="mb-5">
                            <h2 class="section-title">6. Liability and Responsibilities</h2>
                            <h5>6.1 Customer Responsibilities</h5>
                            <p>You are responsible for:</p>
                            <ul>
                                <li>Being ready at the pickup location at the scheduled time</li>
                                <li>Providing accurate pickup and destination information</li>
                                <li>Ensuring passenger count matches vehicle capacity</li>
                                <li>Supervising children and minors during the trip</li>
                            </ul>
                            
                            <h5>6.2 Company Liability</h5>
                            <p>Global Sunrise Transport is not liable for:</p>
                            <ul>
                                <li>Delays caused by traffic, weather, or road conditions</li>
                                <li>Loss or damage to personal belongings</li>
                                <li>Customer injuries due to negligence</li>
                                <li>Mechanical failures beyond our control</li>
                            </ul>
                        </section>

                        <!-- Section 7: Privacy -->
                        <section id="privacy" class="mb-5">
                            <h2 class="section-title">7. Privacy and Data Protection</h2>
                            <p>We are committed to protecting your privacy. Your personal information is collected and used in accordance with our Privacy Policy.</p>
                            <div class="highlight-box">
                                <h6><i class="fas fa-shield-alt me-2"></i>Data Protection</h6>
                                <p class="mb-0">We implement appropriate security measures to protect your personal data from unauthorized access, alteration, or disclosure.</p>
                            </div>
                        </section>

                        <!-- Section 8: Changes -->
                        <section id="changes" class="mb-5">
                            <h2 class="section-title">8. Changes to Terms</h2>
                            <p>We reserve the right to modify these terms at any time. Continued use of our services after changes constitutes acceptance of the modified terms.</p>
                            <p>Significant changes will be communicated via email or website notifications at least 30 days before they take effect.</p>
                        </section>

                        <!-- Contact Information -->
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Questions?</h5>
                            </div>
                            <div class="card-body">
                                <p>If you have any questions about these Terms of Service, please contact us:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-envelope me-2"></i>globalsunrise@gmail.com</li>
                                    <li><i class="fas fa-phone me-2"></i> +60 12-489 5876</li>
                                    <li><i class="fas fa-map-marker-alt me-2"></i> Sungai Petani, Kedah, Malaysia</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Footer -->
    <footer class="footer bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-bus me-2"></i>Global Sunrise Transport&Tours</h5>
                    <p class="mb-0" style="color: #cccccc;">&copy; 2024 Global Sunrise Travel & Tours. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="privacy.php" class="text-light me-3">Privacy Policy</a>
                    <a href="terms.php" class="text-light me-3">Terms of Service</a>
                    <a href="contact.php" class="text-light">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Back to top button
        const backToTopButton = document.querySelector('.back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'flex';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        // Update active nav link on scroll
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>