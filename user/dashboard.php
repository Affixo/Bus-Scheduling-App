<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();

$page_title = 'User Dashboard';

// Get user's recent bookings
$user_id = $_SESSION['user_id'];
$booking_query = "SELECT b.*, r.route_name, r.origin_city, r.destination_city, bus.bus_number 
                  FROM BOOKING b 
                  JOIN ROUTE r ON b.route_id = r.route_id 
                  JOIN BUS bus ON b.bus_id = bus.bus_id 
                  WHERE b.user_id = ? 
                  ORDER BY b.created_at DESC LIMIT 5";
$stmt = $conn->prepare($booking_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_bookings = $stmt->get_result();

// Get active routes count
$routes_count = $conn->query("SELECT COUNT(*) as count FROM ROUTE WHERE is_active = 1")->fetch_assoc()['count'];

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2>Welcome, <?php echo $_SESSION['first_name']; ?>!</h2>
        <p class="text-muted">Manage your bus bookings from here</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Bookings</h6>
                <h3><?php 
                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM BOOKING WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    echo $stmt->get_result()->fetch_assoc()['count'];
                ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Confirmed</h6>
                <h3><?php 
                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM BOOKING WHERE user_id = ? AND status = 'confirmed'");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    echo $stmt->get_result()->fetch_assoc()['count'];
                ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Pending</h6>
                <h3><?php 
                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM BOOKING WHERE user_id = ? AND status = 'pending'");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    echo $stmt->get_result()->fetch_assoc()['count'];
                ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Available Routes</h6>
                <h3><?php echo $routes_count; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body">
                <?php if ($recent_bookings->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Route</th>
                                    <th>Bus</th>
                                    <th>Journey Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $booking['booking_id']; ?></td>
                                    <td><?php echo $booking['origin_city'] . ' → ' . $booking['destination_city']; ?></td>
                                    <td><?php echo $booking['bus_number']; ?></td>
                                    <td><?php echo formatDate($booking['journey_date']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $booking['status'] == 'confirmed' ? 'success' : 
                                                ($booking['status'] == 'pending' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>৳<?php echo number_format($booking['total_amount'], 2); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="my-bookings.php" class="btn btn-sm btn-primary">View All Bookings</a>
                <?php else: ?>
                    <p class="text-muted">No bookings yet. <a href="search-routes.php">Book your first ticket!</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="search-routes.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search Routes
                    </a>
                    <a href="my-bookings.php" class="btn btn-outline-primary">
                        <i class="fas fa-ticket-alt"></i> My Bookings
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Popular Routes</h5>
            </div>
            <div class="card-body">
                <?php
                $popular_routes = $conn->query("SELECT * FROM ROUTE WHERE is_active = 1 LIMIT 3");
                while($route = $popular_routes->fetch_assoc()):
                ?>
                <div class="mb-3 pb-3 border-bottom">
                    <h6><?php echo $route['route_name']; ?></h6>
                    <small class="text-muted">
                        <?php echo $route['origin_city']; ?> → <?php echo $route['destination_city']; ?>
                    </small><br>
                    <strong>৳<?php echo $route['fare']; ?></strong>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>