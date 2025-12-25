<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - TravelMate</title>
    <link rel="stylesheet" href="assets/css/Traveller/mybookings.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>My Bookings</h1>
            <p>Manage and view all your travel reservations</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">All Bookings</div>
            <div class="filter-tab" data-filter="confirmed">Confirmed</div>
            <div class="filter-tab" data-filter="cancelled">Cancelled</div>
        </div>

        <!-- Search Section -->
        <div class="controls-section" style="margin-bottom: 2em; display: none;">
            <input type="text" class="search-box" placeholder="Search bookings by room name or booking ID..." id="bookingSearch">
            <select class="sort-dropdown" id="sortBookings">
                <option value="date_desc">Newest First</option>
                <option value="date_asc">Oldest First</option>
                <option value="price_desc">Highest Price</option>
                <option value="price_asc">Lowest Price</option>
            </select>
        </div>

        <!-- Loading State -->
        <div class="loading-container" style="text-align: center; padding: 3em; display: none;">
            <div class="loading-spinner" style="display: inline-block; width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #1abc5b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 1em; color: #666;">Loading your bookings...</p>
        </div>

        <div class="bookings-container">
            <!-- Hotels Section -->
            <div class="booking-section" data-category="hotels">
                <div class="section-header">
                    <div class="section-icon">🏨</div>
                    <h2 class="section-title">Hotel Bookings</h2>
                </div>
                
                <!-- Bookings will be dynamically loaded here -->
            </div>

            <!-- Transport Section (Future Implementation) -->
            <div class="booking-section" data-category="transport" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">✈️</div>
                    <h2 class="section-title">Transport Bookings</h2>
                </div>
                <div class="empty-state">
                    <div class="empty-state-icon">🚗</div>
                    <h3>No Transport Bookings</h3>
                    <p>You haven't booked any transportation yet.</p>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="booking-section" style="margin-top: 2em;">
            <div class="section-header">
                <div class="section-icon">📊</div>
                <h2 class="section-title">Booking Summary</h2>
            </div>
            <div id="bookingStats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5em;">
                <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                    <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">0</div>
                    <div style="color: #666;">Total Bookings</div>
                </div>
                <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                    <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">0</div>
                    <div style="color: #666;">Confirmed</div>
                </div>
                <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                    <div style="font-size: 2em; font-weight: 700; color: #f39c12;">0</div>
                    <div style="color: #666;">Pending</div>
                </div>
                <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                    <div style="font-size: 2em; font-weight: 700; color: #169d4a;">LKR 0</div>
                    <div style="color: #666;">Total Spent</div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- Booking Details Modal -->
    <div id="bookingDetailsModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2em; border-radius: 16px; width: 80%; max-width: 800px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 style="margin-bottom: 1em; color: #222;">Booking Details</h2>
            <div id="modalContent"></div>
        </div>
    </div>

    <script src="assets/js/mybookings.js"></script>
    
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .modal {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            animation: slideDown 0.3s ease-in-out;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>