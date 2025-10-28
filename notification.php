<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-bus me-2"></i>Global Sunrise Transport - Admin
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0"><i class="fas fa-bell me-2"></i>System Notifications & Alerts</h4>
                    </div>
                    <div class="card-body p-0">
                        <!-- Critical Alerts -->
                       
                            <div class="alert alert-warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-clock me-2"></i>
                                        <strong>Pending Approvals</strong> - 8 bookings waiting for confirmation
                                    </div>
                                    <button class="btn btn-sm btn-outline-warning">Review Now</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Notifications -->
                        <div class="p-4 border-bottom">
                            <h5 class="text-primary"><i class="fas fa-info-circle me-2"></i>Recent Notifications</h5>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>New Booking Completed</strong> - GS-2024-1567
                                        </div>
                                        <small class="text-muted">5 minutes ago</small>
                                    </div>
                                    <p class="mb-1">Ali Ahmad - MPV to KLIA (RM 180.00)</p>
                                </div>
                                
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <i class="fas fa-user-plus text-info me-2"></i>
                                            <strong>New Customer Registration</strong>
                                        </div>
                                        <small class="text-muted">1 hour ago</small>
                                    </div>
                                    <p class="mb-1">Sarah Johnson registered as new customer</p>
                                </div>
                                
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <i class="fas fa-money-bill-wave text-success me-2"></i>
                                            <strong>Payment Received</strong>
                                        </div>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                    <p class="mb-1">RM 350.00 payment for booking GS-2024-1565</p>
                                </div>
                                
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <i class="fas fa-star text-warning me-2"></i>
                                            <strong>Customer Review Received</strong>
                                        </div>
                                        <small class="text-muted">3 hours ago</small>
                                    </div>
                                    <p class="mb-1">Raj Kumar rated service 5 stars</p>
                                </div>
                            </div>
                        </div>
                       
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="admin_dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                            <button class="btn btn-danger">
                                <i class="fas fa-bell-slash me-1"></i> Mark All as Read
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>