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

    <main class="bookings-layout">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="mybookings" class="sidebar-link active">
                        <span class="sidebar-text">Accommodation Bookings</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="mytransportbookings" class="sidebar-link">
                        <span class="sidebar-text">Transport Bookings</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="feed" class="sidebar-link">
                        <span class="sidebar-text">Vlogs</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="profile_setting" class="sidebar-link">
                        <span class="sidebar-text">Settings</span>
                    </a>
                </li>
                <li class="sidebar-item sidebar-logout">
                    <a href="logout.php" class="sidebar-link logout-link">
                        <span class="sidebar-text">Log Out</span>
                    </a>
                </li>
            </ul>
        </aside>

    <!-- Main Content -->
    <section class="main-content">
        <div class="page-header">
            <h1>My Accommodation Bookings</h1>
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
    </section>
    </main>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- Booking Details Modal -->
    <div id="bookingDetailsModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2em; border-radius: 16px; width: 80%; max-width: 800px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 style="margin-bottom: 1em; color: #222;">Booking Details</h2>
            <div id="modalContent"></div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div id="cancelConfirmModal" class="cancel-confirm-modal">
        <div class="cancel-confirm-content">
            <div class="cancel-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#ef4444" stroke-width="3" fill="#fef2f2"/>
                    <path d="M30 20V32" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="30" cy="40" r="2" fill="#ef4444"/>
                </svg>
            </div>
            <h2>Cancel Booking?</h2>
            <p>Are you sure you want to cancel this booking? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-cancel-action" onclick="closeConfirmModal()">No, Keep It</button>
                <button class="btn-confirm-cancel" onclick="proceedWithCancel()">Yes, Cancel Booking</button>
            </div>
        </div>
    </div>

    <!-- Cancel Success Modal -->
    <div id="cancelSuccessModal" class="cancel-success-modal">
        <div class="cancel-success-content">
            <div class="success-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
                    <path d="M20 30L26 36L40 22" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Booking Cancelled Successfully</h2>
            <p>Your booking has been cancelled and you will receive a confirmation email shortly.</p>
            <button class="btn-close-success" onclick="closeCancelSuccessModal()">Close</button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="error-modal">
        <div class="error-modal-content">
            <div class="error-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#ef4444" stroke-width="3" fill="#fef2f2"/>
                    <path d="M30 20V32" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="30" cy="40" r="2" fill="#ef4444"/>
                </svg>
            </div>
            <h2>Error</h2>
            <p id="errorModalMessage">An error occurred</p>
            <button class="btn-close-error" onclick="closeErrorModal()">Close</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="delete-confirm-modal">
        <div class="delete-confirm-content">
            <div class="delete-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#dc2626" stroke-width="3" fill="#fef2f2"/>
                    <path d="M20 22H40M25 22V18C25 17.4477 25.4477 17 26 17H34C34.5523 17 35 17.4477 35 18V22M37 22V40C37 40.5523 36.5523 41 36 41H24C23.4477 41 23 40.5523 23 40V22" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M27 28V36M33 28V36" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
            <h2>Delete Booking?</h2>
            <p>Are you sure you want to permanently delete this booking? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-cancel-action" onclick="closeDeleteConfirmModal()">No, Keep It</button>
                <button class="btn-confirm-delete" onclick="proceedWithDelete()">Yes, Delete Permanently</button>
            </div>
        </div>
    </div>

    <!-- Delete Success Modal -->
    <div id="deleteSuccessModal" class="delete-success-modal">
        <div class="delete-success-content">
            <div class="success-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
                    <path d="M20 30L26 36L40 22" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Booking Deleted Successfully</h2>
            <p>The booking has been permanently removed from your records.</p>
            <button class="btn-close-success" onclick="closeDeleteSuccessModal()">Close</button>
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

        /* Cancel Confirmation Modal */
        .cancel-confirm-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .cancel-confirm-modal.show {
            display: flex;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .cancel-confirm-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .cancel-icon {
            margin-bottom: 24px;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .cancel-confirm-content h2 {
            color: #ef4444;
            font-size: 28px;
            margin: 0 0 12px 0;
            font-weight: 700;
        }

        .cancel-confirm-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 28px 0;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-cancel-action {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel-action:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
        }

        .btn-confirm-cancel {
            background: #ef4444;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm-cancel:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Cancel Success Modal */
        .cancel-success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .cancel-success-modal.show {
            display: flex;
        }

        .cancel-success-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .success-icon {
            margin-bottom: 24px;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .cancel-success-content h2 {
            color: #10b981;
            font-size: 28px;
            margin: 0 0 12px 0;
            font-weight: 700;
        }

        .cancel-success-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 28px 0;
            line-height: 1.6;
        }

        .btn-close-success {
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 36px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Error Modal */
        .error-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .error-modal.show {
            display: flex;
        }

        .error-modal-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .error-icon {
            margin-bottom: 24px;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .error-modal-content h2 {
            color: #ef4444;
            font-size: 28px;
            margin: 0 0 12px 0;
            font-weight: 700;
        }

        .error-modal-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 28px 0;
            line-height: 1.6;
        }

        .btn-close-error {
            background: #ef4444;
            color: white;
            border: none;
            padding: 12px 36px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-error:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Delete Confirmation Modal */
        .delete-confirm-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .delete-confirm-modal.show {
            display: flex;
        }

        .delete-confirm-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .delete-icon {
            margin-bottom: 24px;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .delete-confirm-content h2 {
            color: #dc2626;
            font-size: 28px;
            margin: 0 0 12px 0;
            font-weight: 700;
        }

        .delete-confirm-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 28px 0;
            line-height: 1.6;
        }

        .btn-confirm-delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm-delete:hover {
            background: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* Delete Success Modal */
        .delete-success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .delete-success-modal.show {
            display: flex;
        }

        .delete-success-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }
    </style>
</body>
</html>