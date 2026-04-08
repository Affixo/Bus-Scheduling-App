<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Welcome to Bus Schedule System';
$css_path = 'assets/css/style.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-bus"></i> Bus Schedule System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="user/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 mb-3">Welcome to Bus Schedule System</h1>
            <p class="lead mb-4">Book your bus tickets online - Fast, Easy & Convenient</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="user/register.php" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus"></i> Register Now
                </a>
                <a href="user/login.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Search Routes</h5>
                        <p class="card-text">Find available bus routes between your desired locations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-ticket-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Book Tickets</h5>
                        <p class="card-text">Easy and secure online ticket booking system</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Real-time Updates</h5>
                        <p class="card-text">Get real-time updates on bus schedules and bookings</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Popular Routes</h2>
                <?php
                $query = "SELECT * FROM ROUTE WHERE is_active = 1 LIMIT 3";
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0):
                ?>
                <div class="row g-3">
                    <?php while($route = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $route['route_name']; ?></h5>
                                <p class="mb-1"><i class="fas fa-map-marker-alt"></i> From: <?php echo $route['origin_city']; ?></p>
                                <p class="mb-1"><i class="fas fa-map-marker-alt"></i> To: <?php echo $route['destination_city']; ?></p>
                                <p class="mb-1"><i class="fas fa-money-bill"></i> Fare: ৳<?php echo $route['fare']; ?></p>
                                <p class="mb-3"><i class="fas fa-clock"></i> Departure: <?php echo formatTime($route['departure_time']); ?></p>
                                <a href="user/register.php" class="btn btn-primary btn-sm">Book Now</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Bus Schedule System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>