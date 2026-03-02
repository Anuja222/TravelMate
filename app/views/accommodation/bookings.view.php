<!-- Bookings Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/bookings.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/dashboard.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    
    <main>
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul>
                <li><a href="ac_dashboard">Dashboard</a></li>
                <li><a href="ac_bookings" class="active">Bookings</a></li>
                <li><a href="acc_setting">Settings</a></li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <section class="dashboard-content">
            <div class="bookings-container">
                <!-- New Booking Notification Banner -->
                <div id="newBookingNotification" class="notification-banner" style="display: none;">
                    <div class="notification-content">
                        <i class="fas fa-bell"></i>
                        <div class="notification-text">
                            <strong>New Bookings!</strong>
                            <p id="notificationMessage">You have new bookings while you were away.</p>
                        </div>
                    </div>
                    <button class="notification-close" onclick="dismissNotification()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="page-header">
                    <h1><i class="fas fa-calendar-check"></i> Bookings</h1>
                    <p>Manage all your property bookings in one place</p>
                </div>
                
                <div class="bookings-grid">
                    <!-- Bookings will be dynamically loaded here -->
                </div>
            </div>
        </section>
    </main>
    
    <!-- Booking Details Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2 class="modal-title">Booking Details</h2>
            <div class="modal-body" id="modalBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contactModal" class="modal">
        <div class="modal-content contact-modal-content">
            <span class="modal-close" onclick="closeContactModal()">&times;</span>
            <h2 class="modal-title">Contact Guest</h2>
            <div class="modal-body" id="contactModalBody">
                <!-- Contact options will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/providerbookings.js?v=<?php echo time(); ?>"></script>
</body>
</html>
