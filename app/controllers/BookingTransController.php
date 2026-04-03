<?php
namespace App\Controllers;

use App\Models\bookingTrans;
use PDO;

class BookingTransController
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
    
    private function checkAuth()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'transport') {
            $this->sendResponse(false, [], ['auth' => 'Unauthorized access']);
        }
        return $_SESSION['user']['id'];
    }
    
    // Get all bookings for transporter
    public function getTransporterBookings()
    {
        try {
            $transporterId = $this->checkAuth();
            
            $sql = "SELECT 
                        b.*,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        v.vehicle_model,
                        v.vehicle_type,
                        v.vehicle_number,
                        v.ac_type,
                        v.passenger_count,
                        v.working_district
                    FROM transport_bookings b
                    INNER JOIN users u ON b.user_id = u.id
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.user_id = ?
                    ORDER BY b.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$transporterId]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
           // Get stats - Only Pending and Completed
           $stats = [
                'pending' => 0,
                'completed' => 0,
                'total' => count($bookings)
            ];

                foreach ($bookings as $booking) {
                    $status = $booking['booking_status'] ?? 'pending';
                    if ($status === 'pending') $stats['pending']++;
                    if ($status === 'completed') $stats['completed']++;
                }
            
            $this->sendResponse(true, $bookings, ['stats' => $stats]);
            
        } catch (\Exception $e) {
            error_log('Error in getTransporterBookings: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to load bookings']);
        }
    }
    
    // Get single booking details for transporter
    public function getTransporterBookingDetails($bookingCode)
    {
        try {
            $transporterId = $this->checkAuth();
            error_log("API called - getTransporterBookingDetails - BookingCode: $bookingCode, TransporterId: $transporterId");
            
            if (empty($bookingCode)) {
                $this->sendResponse(false, [], ['general' => 'Booking ID required']);
            }
            
            // Get the booking details with user info and vehicle details
            $sql = "SELECT 
                        b.*,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        u.profile_image,
                        v.vehicle_model,
                        v.vehicle_number,
                        v.vehicle_type,
                        v.ac_type,
                        v.passenger_count,
                        v.working_district,
                        v.vehicle_year,
                        v.vehicle_color,
                        v.status as vehicle_status
                    FROM transport_bookings b
                    INNER JOIN users u ON b.user_id = u.id
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE b.booking_id = ? AND v.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$bookingCode, $transporterId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$booking) {
                error_log("Booking not found or unauthorized: $bookingCode");
                $this->sendResponse(false, [], ['general' => 'Booking not found or unauthorized']);
            }
            
            error_log("Booking found: " . json_encode($booking));
            
            // Format dates for better display
            $pickupDate = !empty($booking['pickup_date']) ? date('Y-m-d', strtotime($booking['pickup_date'])) : '';
            $returnDate = !empty($booking['return_date']) ? date('Y-m-d', strtotime($booking['return_date'])) : '';
            $bookingDate = !empty($booking['created_at']) ? date('Y-m-d H:i:s', strtotime($booking['created_at'])) : '';
            
            // Determine trip type
            $tripType = (!empty($booking['return_date']) && $booking['return_date'] != '0000-00-00') ? 'round_trip' : 'one_way';
            
            // Return the formatted booking data
            $data = [
                // Booking identifiers
                'booking_id' => $booking['booking_id'] ?? '',
                'booking_reference' => $booking['booking_id'] ?? '',
                
                // Customer information
                'first_name' => $booking['first_name'] ?? '',
                'last_name' => $booking['last_name'] ?? '',
                'customer_name' => trim(($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? '')),
                'email' => $booking['email'] ?? '',
                'phone' => $booking['phone'] ?? '',
                'profile_image' => $booking['profile_image'] ?? null,
                
                // Trip details (from transport_bookings)
                'pickup_location' => $booking['pickup_location'] ?? '',
                'dropoff_location' => $booking['dropoff_location'] ?? '',
                'pickup_date' => $pickupDate,
                'pickup_time' => $booking['pickup_time'] ?? '',
                'return_date' => $returnDate,
                'return_time' => $booking['return_time'] ?? '',
                'trip_type' => $tripType,
                'passengers' => intval($booking['passengers'] ?? 1),
                'luggage' => intval($booking['luggage'] ?? 0),
                'special_requirements' => $booking['special_requirements'] ?? '',
                'duration' => intval($booking['duration'] ?? 1),
                'service_type' => $booking['service_type'] ?? 'transport',
                
                // Vehicle details (from vehicles table)
                'vehicle_id' => $booking['vehicle_id'] ?? null,
                'vehicle_model' => $booking['vehicle_model'] ?? 'N/A',
                'vehicle_type' => $booking['vehicle_type'] ?? 'N/A',
                'vehicle_number' => $booking['vehicle_number'] ?? 'N/A',
                'ac_type' => $booking['ac_type'] ?? 'N/A',
                'passenger_capacity' => intval($booking['passenger_count'] ?? 0),
                'working_district' => $booking['working_district'] ?? 'N/A',
                'vehicle_year' => $booking['vehicle_year'] ?? null,
                'vehicle_color' => $booking['vehicle_color'] ?? 'N/A',
                'vehicle_status' => $booking['vehicle_status'] ?? 'active',
                
                // Status information (from transport_bookings)
                'booking_status' => $booking['booking_status'] ?? 'pending',
                'payment_status' => $booking['payment_status'] ?? 'pending',
                'booking_date' => $bookingDate,
                'created_at' => $bookingDate,
                
                // Pricing information (from transport_bookings)
                'base_price' => floatval($booking['base_price'] ?? 0),
                'service_charge' => floatval($booking['service_charge'] ?? 0),
                'total_price' => floatval($booking['total_price'] ?? 0),
                'currency' => 'LKR'
            ];
            
            error_log("Returning formatted data for booking: " . $bookingCode);
            $this->sendResponse(true, $data);
            
        } catch (\Exception $e) {
            error_log('API Error in getTransporterBookingDetails: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->sendResponse(false, [], ['general' => 'Failed to load booking details: ' . $e->getMessage()]);
        }
    }
    
    // Update booking status
    public function updateBookingStatus()
    {
        try {
            $transporterId = $this->checkAuth();
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['booking_id']) || !isset($input['status'])) {
                $this->sendResponse(false, [], ['general' => 'Invalid request data']);
            }
            
            $bookingId = $input['booking_id'];
            $status = $input['status'];
            
            // Verify booking belongs to this transporter
            $sql = "SELECT b.id FROM transport_bookings b
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE b.booking_id = ? AND v.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$bookingId, $transporterId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$booking) {
                $this->sendResponse(false, [], ['general' => 'Booking not found or unauthorized']);
            }
            
            // Update status
            $sql = "UPDATE transport_bookings SET booking_status = ? WHERE booking_id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$status, $bookingId]);
            
            if ($result) {
                // Log the status change
                error_log("Booking $bookingId status updated to $status by transporter $transporterId");
                $this->sendResponse(true, [
                    'booking_id' => $bookingId, 
                    'new_status' => $status
                ], ['message' => 'Status updated successfully']);
            } else {
                $this->sendResponse(false, [], ['general' => 'Failed to update status']);
            }
            
        } catch (\Exception $e) {
            error_log('Error in updateBookingStatus: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to update status']);
        }
    }
    
    // Optional: Get booking statistics
    public function getBookingStats()
    {
        try {
            $transporterId = $this->checkAuth();
            
            $sql = "SELECT 
                        COUNT(*) as total_bookings,
                        SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                        SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                        SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
                        SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                        SUM(total_price) as total_revenue
                    FROM transport_bookings b
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$transporterId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->sendResponse(true, $stats);
            
        } catch (\Exception $e) {
            error_log('Error in getBookingStats: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to load statistics']);
        }
    }
}