<?php
namespace App\Controllers;

use App\Models\TransportBooking;
use App\Models\Vehicle;
use PDO;
use Exception;

require_once __DIR__ . '/../../models/TransportBooking.php';
require_once __DIR__ . '/../../../config/database.php';

class TransportBookingController
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Helper to send JSON response
    private function sendResponse($success, $data = [], $errors = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'errors' => $errors
        ]);
        exit;
    }

    // Helper to check authentication
    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            $this->sendResponse(false, [], ['auth' => 'Please login to continue']);
        }
        return $_SESSION['user']['id'];
    }

    // Create new transport booking
    public function create()
    {
        try {
            error_log('=== Transport Booking Create Started ===');
            $userId = $this->checkAuth();
            error_log('User ID: ' . $userId);

            $input = json_decode(file_get_contents('php://input'), true);
            error_log('Input data: ' . print_r($input, true));

            if (!$input) {
                error_log('ERROR: Invalid request data');
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }

            // Validate required fields
            $required = ['vehicle_id', 'service_type', 'pickup_date', 'pickup_time', 'return_date', 
                        'return_time', 'pickup_location', 'dropoff_location', 'passengers'];
            
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    $this->sendResponse(false, [], [$field => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
                }
            }

            // Validate dates
            $pickupDateTime = strtotime($input['pickup_date'] . ' ' . $input['pickup_time']);
            $returnDateTime = strtotime($input['return_date'] . ' ' . $input['return_time']);
            
            if ($pickupDateTime >= $returnDateTime) {
                $this->sendResponse(false, [], ['date' => 'Return date/time must be after pickup date/time']);
            }

            if ($pickupDateTime < time()) {
                $this->sendResponse(false, [], ['date' => 'Pickup date/time cannot be in the past']);
            }

            // Check vehicle availability
            $bookingModel = new TransportBooking();
            $isAvailable = TransportBooking::checkAvailability(
                $this->db, 
                $input['vehicle_id'], 
                $input['pickup_date'], 
                $input['return_date']
            );

            if (!$isAvailable) {
                $this->sendResponse(false, [], ['availability' => 'Vehicle is not available for the selected dates']);
            }

            // Calculate duration in days
            $duration = ceil(($returnDateTime - $pickupDateTime) / (60 * 60 * 24));

            // Generate unique booking ID
            $bookingId = 'TB' . time() . rand(1000, 9999);

            // Prepare booking data
            $bookingData = [
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'vehicle_id' => $input['vehicle_id'],
                'service_type' => $input['service_type'],
                'pickup_date' => $input['pickup_date'],
                'pickup_time' => $input['pickup_time'],
                'return_date' => $input['return_date'],
                'return_time' => $input['return_time'],
                'pickup_location' => $input['pickup_location'],
                'dropoff_location' => $input['dropoff_location'],
                'passengers' => $input['passengers'],
                'luggage' => $input['luggage'] ?? 0,
                'special_requirements' => $input['special_requirements'] ?? null,
                'duration' => $duration,
                'base_price' => $input['base_price'],
                'service_charge' => $input['service_charge'],
                'total_price' => $input['total_price'],
                'booking_status' => 'confirmed',
                'payment_status' => 'pending',
                'booking_date' => date('Y-m-d H:i:s')
            ];

            // Create booking
            error_log('Creating booking with data: ' . print_r($bookingData, true));
            $result = $bookingModel->createBooking($this->db, $bookingData);
            error_log('Booking creation result: ' . ($result ? 'success' : 'failed'));

            if ($result) {
                $this->sendResponse(true, [
                    'booking_id' => $bookingId,
                    'message' => 'Transport booking created successfully'
                ]);
            } else {
                error_log('ERROR: Failed to create booking in database');
                $this->sendResponse(false, [], ['general' => 'Failed to create booking']);
            }

        } catch (Exception $e) {
            error_log('Transport booking creation error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->sendResponse(false, [], ['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Get all transport bookings for user
    public function getAll()
    {
        try {
            $userId = $this->checkAuth();

            $bookingModel = new TransportBooking();
            $bookings = $bookingModel->getBookingsByUserId($this->db, $userId);

            $this->sendResponse(true, ['bookings' => $bookings]);

        } catch (Exception $e) {
            error_log('Get transport bookings error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to retrieve bookings']);
        }
    }

    // Get single transport booking
    public function get()
    {
        try {
            $userId = $this->checkAuth();

            $bookingId = $_GET['id'] ?? null;

            if (!$bookingId) {
                $this->sendResponse(false, [], ['bookingId' => 'Booking ID is required']);
            }

            $bookingModel = new TransportBooking();
            $booking = $bookingModel->getBookingById($this->db, $bookingId, $userId);

            if ($booking) {
                $this->sendResponse(true, ['booking' => $booking]);
            } else {
                $this->sendResponse(false, [], ['general' => 'Booking not found']);
            }

        } catch (Exception $e) {
            error_log('Get transport booking error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to retrieve booking']);
        }
    }

    // Update transport booking
    public function update()
    {
        try {
            $userId = $this->checkAuth();

            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input || empty($input['booking_id'])) {
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }

            $bookingId = $input['booking_id'];

            // Validate dates
            $pickupDateTime = strtotime($input['pickup_date'] . ' ' . $input['pickup_time']);
            $returnDateTime = strtotime($input['return_date'] . ' ' . $input['return_time']);
            
            if ($pickupDateTime >= $returnDateTime) {
                $this->sendResponse(false, [], ['date' => 'Return date/time must be after pickup date/time']);
            }

            // Check if booking exists
            $bookingModel = new TransportBooking();
            $existingBooking = $bookingModel->getBookingById($this->db, $bookingId, $userId);

            if (!$existingBooking) {
                $this->sendResponse(false, [], ['general' => 'Booking not found']);
            }

            // Check availability (excluding current booking)
            $isAvailable = TransportBooking::checkAvailability(
                $this->db, 
                $existingBooking['vehicle_id'], 
                $input['pickup_date'], 
                $input['return_date'],
                $bookingId
            );

            if (!$isAvailable) {
                $this->sendResponse(false, [], ['availability' => 'Vehicle is not available for the selected dates']);
            }

            // Calculate duration
            $duration = ceil(($returnDateTime - $pickupDateTime) / (60 * 60 * 24));

            // Prepare update data
            $updateData = [
                'pickup_date' => $input['pickup_date'],
                'pickup_time' => $input['pickup_time'],
                'return_date' => $input['return_date'],
                'return_time' => $input['return_time'],
                'pickup_location' => $input['pickup_location'],
                'dropoff_location' => $input['dropoff_location'],
                'passengers' => $input['passengers'],
                'luggage' => $input['luggage'] ?? 0,
                'special_requirements' => $input['special_requirements'] ?? null,
                'duration' => $duration,
                'base_price' => $input['base_price'],
                'service_charge' => $input['service_charge'],
                'total_price' => $input['total_price']
            ];

            // Update booking
            $result = $bookingModel->updateBooking($this->db, $bookingId, $userId, $updateData);

            if ($result) {
                $this->sendResponse(true, ['message' => 'Booking updated successfully']);
            } else {
                $this->sendResponse(false, [], ['general' => 'Failed to update booking']);
            }

        } catch (Exception $e) {
            error_log('Transport booking update error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred while updating the booking']);
        }
    }

    // Cancel transport booking
    public function cancel()
    {
        try {
            $userId = $this->checkAuth();

            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input || empty($input['booking_id'])) {
                $this->sendResponse(false, [], ['bookingId' => 'Booking ID is required']);
            }

            $bookingId = $input['booking_id'];

            // Validate booking ID is not "null" string
            if ($bookingId === 'null' || $bookingId === 'undefined') {
                $this->sendResponse(false, [], ['bookingId' => 'Invalid booking ID']);
            }

            $bookingModel = new TransportBooking();
            
            // Verify booking exists
            $booking = $bookingModel->getBookingById($this->db, $bookingId, $userId);
            if (!$booking) {
                $this->sendResponse(false, [], ['general' => 'Booking not found']);
            }

            // Cancel booking
            $result = $bookingModel->cancelBooking($this->db, $bookingId, $userId);

            if ($result) {
                $this->sendResponse(true, ['message' => 'Booking cancelled successfully']);
            } else {
                $this->sendResponse(false, [], ['general' => 'Failed to cancel booking']);
            }

        } catch (Exception $e) {
            error_log('Transport booking cancellation error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred while cancelling the booking']);
        }
    }

    // Delete transport booking
    public function delete()
    {
        try {
            $userId = $this->checkAuth();

            // Handle both JSON and FormData
            $bookingId = null;
            if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
                $input = json_decode(file_get_contents('php://input'), true);
                $bookingId = $input['booking_id'] ?? null;
            } else {
                $bookingId = $_POST['booking_id'] ?? null;
            }

            if (!$bookingId) {
                $this->sendResponse(false, [], ['bookingId' => 'Booking ID is required']);
            }

            $bookingModel = new TransportBooking();
            
            // Verify booking exists
            $booking = $bookingModel->getBookingById($this->db, $bookingId, $userId);
            if (!$booking) {
                $this->sendResponse(false, [], ['general' => 'Booking not found']);
            }

            // Delete booking
            $result = $bookingModel->deleteBooking($this->db, $bookingId, $userId);

            if ($result) {
                $this->sendResponse(true, ['message' => 'Booking deleted successfully']);
            } else {
                $this->sendResponse(false, [], ['general' => 'Failed to delete booking']);
            }

        } catch (Exception $e) {
            error_log('Transport booking deletion error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred while deleting the booking']);
        }
    }

    // Get bookings by status
    public function getByStatus()
    {
        try {
            $userId = $this->checkAuth();

            $status = $_GET['status'] ?? null;

            if (!$status) {
                $this->sendResponse(false, [], ['status' => 'Status is required']);
            }

            $bookingModel = new TransportBooking();
            $bookings = $bookingModel->getBookingsByStatus($this->db, $userId, $status);

            $this->sendResponse(true, ['bookings' => $bookings]);

        } catch (Exception $e) {
            error_log('Get transport bookings by status error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to retrieve bookings']);
        }
    }

    // Get upcoming bookings
    public function getUpcoming()
    {
        try {
            $userId = $this->checkAuth();

            $bookingModel = new TransportBooking();
            $bookings = $bookingModel->getUpcomingBookings($this->db, $userId);

            $this->sendResponse(true, ['bookings' => $bookings]);

        } catch (Exception $e) {
            error_log('Get upcoming transport bookings error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to retrieve bookings']);
        }
    }

    // Get booking statistics
    public function getStats()
    {
        try {
            $userId = $this->checkAuth();

            $bookingModel = new TransportBooking();
            $stats = $bookingModel->getBookingStats($this->db, $userId);

            $this->sendResponse(true, ['stats' => $stats]);

        } catch (Exception $e) {
            error_log('Get transport booking stats error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to retrieve statistics']);
        }
    }

    // Initialize booking - Save booking data to session
    public function initBooking()
    {
        try {
            $userId = $this->checkAuth();

            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }

            // Validate required fields
            $required = ['vehicle_id', 'service_type', 'pickup_date', 'pickup_time', 'return_date', 
                        'return_time', 'pickup_location', 'dropoff_location', 'passengers'];
            
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    $this->sendResponse(false, [], [$field => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
                }
            }

            // Validate dates
            $pickupDateTime = strtotime($input['pickup_date'] . ' ' . $input['pickup_time']);
            $returnDateTime = strtotime($input['return_date'] . ' ' . $input['return_time']);
            
            if ($pickupDateTime >= $returnDateTime) {
                $this->sendResponse(false, [], ['date' => 'Return date/time must be after pickup date/time']);
            }

            if ($pickupDateTime < time()) {
                $this->sendResponse(false, [], ['date' => 'Pickup date/time cannot be in the past']);
            }

            // Check vehicle availability
            $bookingModel = new TransportBooking();
            $isAvailable = TransportBooking::checkAvailability(
                $this->db, 
                $input['vehicle_id'], 
                $input['pickup_date'], 
                $input['return_date']
            );

            if (!$isAvailable) {
                $this->sendResponse(false, [], ['availability' => 'Vehicle is not available for the selected dates']);
            }

            // Save to session
            $_SESSION['transport_booking_temp'] = $input;

            $this->sendResponse(true, ['message' => 'Booking data saved']);

        } catch (Exception $e) {
            error_log('Init transport booking error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Save personal details to session
    public function saveDetails()
    {
        try {
            $userId = $this->checkAuth();

            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }

            // Save to session
            $_SESSION['transport_personal_details'] = $input;

            $this->sendResponse(true, ['message' => 'Details saved']);

        } catch (Exception $e) {
            error_log('Save transport booking details error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Save payment details to session
    public function savePayment()
    {
        try {
            $userId = $this->checkAuth();

            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }

            // Save to session (already masked from frontend)
            $_SESSION['transport_payment_details'] = $input;

            $this->sendResponse(true, ['message' => 'Payment details saved']);

        } catch (Exception $e) {
            error_log('Save transport payment details error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Complete booking - Create booking in database
    public function completeBooking()
    {
        try {
            $userId = $this->checkAuth();

            // Get data from session
            $bookingData = $_SESSION['transport_booking_temp'] ?? null;
            $personalDetails = $_SESSION['transport_personal_details'] ?? null;
            $paymentDetails = $_SESSION['transport_payment_details'] ?? null;

            if (!$bookingData || !$personalDetails || !$paymentDetails) {
                $this->sendResponse(false, [], ['general' => 'Missing booking information']);
            }

            // Calculate duration in days
            $pickupDateTime = strtotime($bookingData['pickup_date'] . ' ' . $bookingData['pickup_time']);
            $returnDateTime = strtotime($bookingData['return_date'] . ' ' . $bookingData['return_time']);
            $duration = ceil(($returnDateTime - $pickupDateTime) / (60 * 60 * 24));

            // Generate unique booking ID
            $bookingId = 'TB' . time() . rand(1000, 9999);

            // Prepare final booking data
            $finalBookingData = [
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'vehicle_id' => $bookingData['vehicle_id'],
                'service_type' => $bookingData['service_type'],
                'pickup_date' => $bookingData['pickup_date'],
                'pickup_time' => $bookingData['pickup_time'],
                'return_date' => $bookingData['return_date'],
                'return_time' => $bookingData['return_time'],
                'pickup_location' => $bookingData['pickup_location'],
                'dropoff_location' => $bookingData['dropoff_location'],
                'passengers' => $bookingData['passengers'],
                'luggage' => $bookingData['luggage'] ?? 0,
                'special_requirements' => $personalDetails['special_requests'] ?? null,
                'duration' => $duration,
                'base_price' => $bookingData['base_price'],
                'service_charge' => $bookingData['service_charge'],
                'total_price' => $bookingData['total_price'],
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'booking_date' => date('Y-m-d H:i:s')
            ];

            // Create booking
            $bookingModel = new TransportBooking();
            $result = $bookingModel->createBooking($this->db, $finalBookingData);

            if ($result) {
                // Clear session data
                unset($_SESSION['transport_booking_temp']);
                unset($_SESSION['transport_personal_details']);
                unset($_SESSION['transport_payment_details']);

                $this->sendResponse(true, [
                    'booking_id' => $bookingId,
                    'message' => 'Transport booking completed successfully'
                ]);
            } else {
                $this->sendResponse(false, [], ['general' => 'Failed to complete booking']);
            }

        } catch (Exception $e) {
            error_log('Complete transport booking error: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
