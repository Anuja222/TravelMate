<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../Validation/Validator.php';
require_once __DIR__ . '/../../config/database.php';

use App\Models\Booking;
use App\Validation\Validator;

class BookingController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Create a new booking
    public function createBooking()
    {
        global $pdo;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to complete booking']);
            return;
        }

        // Validate required fields
        $errors = $this->validator->validateRequiredFields($data, [
            'bookingId',
            'roomId',
            'roomName',
            'checkinDate',
            'checkoutDate',
            'adults',
            'nights',
            'roomPrice',
            'basePrice',
            'taxes',
            'totalPrice',
            'paymentStatus'
        ]);

        if (!empty($errors)) {
            $this->sendResponse(false, $errors);
            return;
        }

        // Business logic validations
        if ($data['adults'] < 1)
            $errors['adults'] = 'At least one adult is required';
        if ($data['nights'] < 1)
            $errors['nights'] = 'Number of nights is required';
        if ($data['roomPrice'] <= 0)
            $errors['roomPrice'] = 'Room price must be greater than 0';
        if ($data['basePrice'] <= 0)
            $errors['basePrice'] = 'Base price must be greater than 0';
        if ($data['totalPrice'] <= 0)
            $errors['totalPrice'] = 'Total price must be greater than 0';

        if (!empty($errors)) {
            $this->sendResponse(false, $errors);
            return;
        }

        $booking = new Booking();
        $result = $booking->createBooking($pdo, [
            'user_id' => $data['userId'],
            'booking_id' => $data['bookingId'],
            'room_id' => $data['roomId'],
            'room_name' => $data['roomName'],
            'checkin_date' => $data['checkinDate'],
            'checkout_date' => $data['checkoutDate'],
            'adults' => $data['adults'],
            'children' => $data['children'] ?? 0,
            'nights' => $data['nights'],
            'room_price' => $data['roomPrice'],
            'base_price' => $data['basePrice'],
            'taxes' => $data['taxes'],
            'total_price' => $data['totalPrice'],
            'booking_status' => $data['bookingStatus'] ?? 'confirmed',
            'payment_status' => $data['paymentStatus'],
            'booking_date' => $data['bookingDate'] ?? date('Y-m-d H:i:s')
        ]);

        if ($result) {
            $this->sendResponse(true, [], ['bookingId' => $data['bookingId']]);
        } else {
            $this->sendResponse(false, ['general' => 'Failed to create booking. Please try again.']);
        }
    }

    // Get all bookings for logged-in user
    public function getUserBookings()
    {
        global $pdo;

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to view bookings']);
            return;
        }

        $booking = new Booking();
        $bookings = $booking->getBookingsByUserId($pdo, $_SESSION['user']['id']);
        $stats = $booking->getBookingStats($pdo, $_SESSION['user']['id']);

        $this->sendResponse(true, [], [
            'bookings' => $bookings,
            'stats' => $stats
        ]);
    }

    // Get single booking
    public function getBooking($bookingId)
    {
        global $pdo;

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to view booking']);
            return;
        }

        $booking = new Booking();
        $result = $booking->getBookingById($pdo, $bookingId, $_SESSION['user']['id']);

        if ($result) {
            $this->sendResponse(true, [], ['booking' => $result]);
        } else {
            $this->sendResponse(false, ['general' => 'Booking not found']);
        }
    }

    // Update booking
    public function updateBooking()
    {
        global $pdo;

        // Ensure JSON response even on errors
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($_SESSION['user']['id'])) {
                $this->sendResponse(false, ['auth' => 'Please login to update booking']);
                return;
            }

            if (empty($data['bookingId'])) {
                $this->sendResponse(false, ['general' => 'Booking ID is required']);
                return;
            }

            // Validate required fields
            $errors = [];

            if (empty($data['checkinDate'])) {
                $errors['checkinDate'] = 'Check-in date is required';
            }

            if (empty($data['checkoutDate'])) {
                $errors['checkoutDate'] = 'Check-out date is required';
            }

            if (!isset($data['adults']) || $data['adults'] < 1) {
                $errors['adults'] = 'At least one adult is required';
            }

            if (!isset($data['nights']) || $data['nights'] < 1) {
                $errors['nights'] = 'Number of nights must be at least 1';
            }

            // Validate dates
            if (!empty($data['checkinDate']) && !empty($data['checkoutDate'])) {
                $checkin = strtotime($data['checkinDate']);
                $checkout = strtotime($data['checkoutDate']);

                if ($checkout <= $checkin) {
                    $errors['checkoutDate'] = 'Check-out date must be after check-in date';
                }
            }

            if (!empty($errors)) {
                $this->sendResponse(false, $errors);
                return;
            }

            // Prepare update data
            $updateData = [
                'checkin_date' => $data['checkinDate'],
                'checkout_date' => $data['checkoutDate'],
                'adults' => $data['adults'],
                'children' => $data['children'] ?? 0,
                'nights' => $data['nights'],
                'booking_status' => $data['bookingStatus'] ?? 'confirmed'
            ];

            // Include pricing if provided (to prevent zeroing out)
            if (isset($data['roomPrice'])) {
                $updateData['room_price'] = $data['roomPrice'];
            }
            if (isset($data['basePrice'])) {
                $updateData['base_price'] = $data['basePrice'];
            }
            if (isset($data['taxes'])) {
                $updateData['taxes'] = $data['taxes'];
            }
            if (isset($data['totalPrice'])) {
                $updateData['total_price'] = $data['totalPrice'];
            }

            $booking = new Booking();
            $result = $booking->updateBooking($pdo, $data['bookingId'], $_SESSION['user']['id'], $updateData);

            if ($result) {
                $this->sendResponse(true, [], ['message' => 'Booking updated successfully']);
            } else {
                $this->sendResponse(false, ['general' => 'Failed to update booking. Please check if the booking exists.']);
            }

        } catch (Exception $e) {
            error_log('Booking update error: ' . $e->getMessage());
            $this->sendResponse(false, ['general' => 'An error occurred while updating the booking']);
        }
    }

    // Update booking status
    public function updateBookingStatus()
    {
        global $pdo;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to update booking']);
            return;
        }

        if (empty($data['bookingId']) || empty($data['status'])) {
            $this->sendResponse(false, ['general' => 'Booking ID and status are required']);
            return;
        }

        $booking = new Booking();
        $result = $booking->updateBookingStatus($pdo, $data['bookingId'], $data['status'], $_SESSION['user']['id']);

        if ($result) {
            $this->sendResponse(true, [], ['message' => 'Booking status updated successfully']);
        } else {
            $this->sendResponse(false, ['general' => 'Failed to update booking status']);
        }
    }

    // Cancel booking
    public function cancelBooking()
    {
        global $pdo;
        
        try {
            // Check session first
            if (!isset($_SESSION['user']['id'])) {
                $this->sendResponse(false, ['auth' => 'Please login to cancel booking']);
                return;
            }
            
            // Get raw input
            $rawInput = file_get_contents('php://input');
            
            // Validate raw input
            if (empty($rawInput)) {
                $this->sendResponse(false, ['general' => 'No data received']);
                return;
            }
            
            // Decode JSON
            $data = json_decode($rawInput, true);
            
            // Check if JSON decode was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendResponse(false, ['general' => 'Invalid JSON: ' . json_last_error_msg()]);
                return;
            }
            
            // Check if data is array
            if (!is_array($data)) {
                $this->sendResponse(false, ['general' => 'Invalid request format']);
                return;
            }
            
            // Check if bookingId exists and is not empty
            if (!isset($data['bookingId']) || empty(trim($data['bookingId']))) {
                $this->sendResponse(false, ['general' => 'Booking ID is required']);
                return;
            }
            
            // Get and validate booking ID
            $bookingId = trim($data['bookingId']);
            
            // Check if booking ID is the string "null" or "undefined"
            if ($bookingId === 'null' || $bookingId === 'undefined') {
                $this->sendResponse(false, ['general' => 'Invalid booking ID']);
                return;
            }
            
            // Try to cancel the booking
            $booking = new Booking();
            $result = $booking->cancelBooking($pdo, $bookingId, $_SESSION['user']['id']);
            
            if ($result) {
                $this->sendResponse(true, [], ['message' => 'Booking cancelled successfully']);
            } else {
                $this->sendResponse(false, ['general' => 'Failed to cancel booking. Booking may not exist or you do not have permission.']);
            }
            
        } catch (Exception $e) {
            $this->sendResponse(false, ['general' => 'Server error: ' . $e->getMessage()]);
        }
    }

    // Delete booking
    public function deleteBooking()
    {
        global $pdo;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to delete booking']);
            return;
        }

        if (empty($data['bookingId'])) {
            $this->sendResponse(false, ['general' => 'Booking ID is required']);
            return;
        }

        $booking = new Booking();
        $result = $booking->deleteBooking($pdo, $data['bookingId'], $_SESSION['user']['id']);

        if ($result) {
            $this->sendResponse(true, [], ['message' => 'Booking deleted successfully']);
        } else {
            $this->sendResponse(false, ['general' => 'Failed to delete booking']);
        }
    }

    // Get bookings by filter
    public function getFilteredBookings()
    {
        global $pdo;

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to view bookings']);
            return;
        }

        $filter = $_GET['filter'] ?? 'all';
        $booking = new Booking();
        $bookings = [];

        switch ($filter) {
            case 'upcoming':
                $bookings = $booking->getUpcomingBookings($pdo, $_SESSION['user']['id']);
                break;
            case 'past':
                $bookings = $booking->getPastBookings($pdo, $_SESSION['user']['id']);
                break;
            case 'current':
                $bookings = $booking->getCurrentBookings($pdo, $_SESSION['user']['id']);
                break;
            case 'confirmed':
                $bookings = $booking->getBookingsByStatus($pdo, $_SESSION['user']['id'], 'confirmed');
                break;
            case 'pending':
                $bookings = $booking->getBookingsByStatus($pdo, $_SESSION['user']['id'], 'pending');
                break;
            case 'cancelled':
                $bookings = $booking->getBookingsByStatus($pdo, $_SESSION['user']['id'], 'cancelled');
                break;
            default:
                $bookings = $booking->getBookingsByUserId($pdo, $_SESSION['user']['id']);
        }

        $this->sendResponse(true, [], ['bookings' => $bookings]);
    }

    // Search bookings
    public function searchBookings()
    {
        global $pdo;

        if (!isset($_SESSION['user']['id'])) {
            $this->sendResponse(false, ['auth' => 'Please login to search bookings']);
            return;
        }

        $searchTerm = $_GET['search'] ?? '';
        $booking = new Booking();
        $bookings = $booking->searchBookings($pdo, $_SESSION['user']['id'], $searchTerm);

        $this->sendResponse(true, [], ['bookings' => $bookings]);
    }

    private function sendResponse($success, $errors = [], $data = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ]);
        exit;
    }
}