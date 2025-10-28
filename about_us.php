<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Global Sunrise Travel & Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .about-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('about-hero.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 50px;
        }
        .section-title {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .card {
            transition: transform 0.3s;
            height: 100%;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .values-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 15px;
        }
        .team-member {
            text-align: center;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #0d6efd;
        }
        .footer { 
            background-color: #343a40; 
            color: #f8f9fa; 
            padding: 20px 0; 
            margin-top: 50px;
        }
        .stats-section {
            background-color: #0d6efd;
            color: white;
            padding: 50px 0;
            border-radius: 10px;
            margin: 50px 0;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="logo.png" alt="Logo" class="d-inline-block align-text-top me-2" width="100">
                Global Sunrise Travel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <!-- Main Content -->
    <div class="about-container">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1 class="display-4 fw-bold">About Global Sunrise Travel & Tours</h1>
            <p class="lead">Your trusted partner for memorable journeys since 2011</p>
        </div>

        <!-- Our Story Section -->
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Our Story</h2>
                <p>Global Sunrise Travel & Tours Sdn. Bhd. is a Malaysian-based company established on 8 September 2011, proudly registered under company number 0959504K / 201101031369. Headquartered in Sungai Petani, Kedah, the company specializes in Express Bus Transport Operations, providing safe, reliable, and comfortable travel experiences for passengers across Malaysia.</p>

<p>Since its incorporation, Global Sunrise Travel & Tours Sdn. Bhd. has been officially licensed by the Ministry of Tourism, Arts and Culture (MOTAC) to operate and manage travel and transportation services nationwide. The company is dedicated to delivering quality transportation services that emphasize customer satisfaction, punctuality, and convenience.</p>

<p>With a strong commitment to excellence and continuous improvement, Global Sunrise Travel & Tours Sdn. Bhd. aims to be one of Malaysia’s most trusted names in the express bus and travel industry. Whether for daily commuting, intercity travel, or group tours, the company strives to make every journey pleasant, safe, and memorable.</p>
                
            </div>
            <div class="col-md-6">
                <img src="about-story.jpg" alt="Our Story" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- Our Mission & Vision -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="text-center">
                        <i class="fas fa-bullseye values-icon"></i>
                        <h3>Our Mission</h3>
                    </div>
                    <p>To provide safe, reliable, and comfortable transportation solutions that exceed customer expectations, making every journey memorable and hassle-free.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="text-center">
                        <i class="fas fa-eye values-icon"></i>
                        <h3>Our Vision</h3>
                    </div>
                    <p>To become Malaysia's leading travel and tour service provider, recognized for excellence in customer service, innovation, and sustainable tourism practices.</p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section text-center">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-number">14+</div>
                    <p>Years of Experience</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">50+</div>
                    <p>Vehicles in Fleet</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">10,000+</div>
                    <p>Satisfied Customers</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">24/7</div>
                    <p>Customer Support</p>
                </div>
            </div>
        </div>

        <!-- Our Values -->
        <h2 class="section-title text-center">Our Values</h2>
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-shield-alt values-icon"></i>
                    <h4>Safety First</h4>
                    <p>We prioritize the safety of our passengers above all else, with regular vehicle maintenance and professional drivers.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-users values-icon"></i>
                    <h4>Customer Focus</h4>
                    <p>Our customers are at the heart of everything we do. We listen, adapt, and strive to exceed expectations.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-handshake values-icon"></i>
                    <h4>Integrity</h4>
                    <p>We conduct our business with honesty, transparency, and ethical practices in all our dealings.</p>
                </div>
            </div>
        </div>

        <!-- Our Team -->
        <h2 class="section-title text-center">Meet Our Team</h2>
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="team-member">
                    <img src="team1.jpg" alt="Team Member">
                    <h4>Selvan Gopal</h4>
                    <p class="text-muted">Founder & CEO</p>
                    <p>With over 20 years in the tourism industry, Ahmad leads our company with passion and vision.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="team-member">
                    <img src="team2.jpg" alt="Team Member">
                    <h4>Valimah Suparmany</h4>
                    <p class="text-muted">Vice Chairman</p>
                    <p>Valimah ensures our services run smoothly and efficiently, coordinating our fleet and drivers.</p>
                </div>
            </div>

        <!-- Why Choose Us -->
        <h2 class="section-title">Why Choose Global Sunrise?</h2>
        <div class="row mb-5">
            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>Wide range of vehicles to suit all group sizes and budgets</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>Professional, licensed, and experienced drivers</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>24/7 customer support and booking assistance</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>Competitive pricing with no hidden charges</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>Regular vehicle maintenance and safety checks</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <span>Flexible booking options and easy cancellation policy</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
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