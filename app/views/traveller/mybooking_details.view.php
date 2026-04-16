<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - TravelMate</title>
    <link rel="stylesheet" href="assets/css/Traveller/mybooking_details.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- main Content -->
    <div class="main-content">
        <div class="booking-details-container">
            <!-- page Header -->
            <div class="page-header">
                <div class="header-content">
                    <!-- <button onclick="window.history.back()" class="back-button">
                        <i class="fas fa-arrow-left"></i> Back to Bookings
                    </button> -->
                    <h1><i class="fas fa-file-invoice"></i> Booking Details</h1>
                    <p>View and update your booking information</p>
                </div>
            </div>

            <!-- booking Form Card -->
            <div class="booking-card">
                <form class="details-form" id="bookingForm">
                    <!-- row 1: Booking ID & Room Name -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bookingId"><i class="fas fa-hashtag"></i> Booking ID</label>
                            <input type="text" id="bookingId" readonly>
                        </div>
                        <div class="form-group">
                            <label for="roomName"><i class="fas fa-door-open"></i> Room Name</label>
                            <input type="text" id="roomName" readonly>
                        </div>
                    </div>

                    <!-- row 2: Check-in & Check-out Dates -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkinDate"><i class="fas fa-calendar-check"></i> Check-in Date</label>
                            <input type="date" id="checkinDate">
                        </div>
                        <div class="form-group">
                            <label for="checkoutDate"><i class="fas fa-calendar-times"></i> Check-out Date</label>
                            <input type="date" id="checkoutDate">
                        </div>
                    </div>

                    <!-- row 3: Adults & Children -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="adults"><i class="fas fa-user"></i> Adults</label>
                            <input type="number" id="adults" min="1">
                        </div>
                        <div class="form-group">
                            <label for="children"><i class="fas fa-child"></i> Children</label>
                            <input type="number" id="children" min="0" value="0">
                        </div>
                    </div>

                    <!-- row 4: Nights & Total Price -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nights"><i class="fas fa-moon"></i> Nights</label>
                            <input type="number" id="nights" readonly>
                        </div>
                        <div class="form-group">
                            <label for="totalPrice"><i class="fas fa-tag"></i> Total Price (LKR)</label>
                            <input type="text" id="totalPrice" readonly>
                        </div>
                    </div>

                    <!-- messages -->
                    <div class="error-message" id="errorMessage"></div>
                    <div class="success-message" id="successMessage"></div>

                    <!-- form Actions -->
                    <div class="form-actions">
                        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- booking Update Success Modal -->
    <div id="updateSuccessModal" class="update-success-modal">
        <div class="update-success-content">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h2>Booking Updated Successfully!</h2>
            <p>Your booking information has been updated and saved.</p>
            <button onclick="goToMyBookings()" class="btn-go-bookings">
                <i class="fas fa-list"></i> View My Bookings
            </button>
        </div>
    </div>

    <style>
        .update-success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            animation: fadeIn 0.3s ease-in-out;
        }

        .update-success-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.4s ease-out;
        }

        .update-success-modal .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }

        .update-success-modal .success-icon svg {
            width: 45px;
            height: 45px;
            color: white;
        }

        .update-success-content h2 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .update-success-content p {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-go-bookings {
            background: linear-gradient(135deg, #1abc5b, #149647);
            color: white;
            border: none;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-go-bookings:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 188, 91, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
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
    </style>

    <script src="assets/js/mybooking_details.js"></script>
</body>
</html>
