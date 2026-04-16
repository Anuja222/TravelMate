<?php
namespace App\Models;

use PDO;

class Booking {
    private $userId;
    private $bookingId;
    private $roomId;
    private $roomName;
    private $checkinDate;
    private $checkoutDate;
    private $adults;
    private $children;
    private $nights;
    private $roomPrice;
    private $basePrice;
    private $taxes;
    private $totalPrice;
    private $bookingStatus;
    private $paymentStatus;
    private $bookingDate;

    public function __construct($data = null) {
        if ($data) {
            $this->userId        = $data['user_id'] ?? null;
            $this->bookingId     = $data['booking_id'] ?? null;
            $this->roomId        = $data['room_id'] ?? null;
            $this->roomName      = $data['room_name'] ?? null;
            $this->checkinDate   = $data['checkin_date'] ?? null;
            $this->checkoutDate  = $data['checkout_date'] ?? null;
            $this->adults        = $data['adults'] ?? 1;
            $this->children      = $data['children'] ?? 0;
            $this->nights        = $data['nights'] ?? 1;
            $this->roomPrice     = $data['room_price'] ?? 0;
            $this->basePrice     = $data['base_price'] ?? 0;
            $this->taxes         = $data['taxes'] ?? 0;
            $this->totalPrice    = $data['total_price'] ?? 0;
            $this->bookingStatus = $data['booking_status'] ?? 'confirmed';
            $this->paymentStatus = $data['payment_status'] ?? 'pending';
            $this->bookingDate   = $data['booking_date'] ?? date('Y-m-d H:i:s');
        }
    }

    // Create booking
    public function createBooking($conn, $data) {
        $sql = "INSERT INTO bookings 
                (user_id, booking_id, room_id, room_name, checkin_date, checkout_date, 
                 adults, children, nights, room_price, base_price, taxes, total_price, 
                 booking_status, payment_status, booking_date, created_at) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['booking_id'],
            $data['room_id'],
            $data['room_name'],
            $data['checkin_date'],
            $data['checkout_date'],
            $data['adults'],
            $data['children'],
            $data['nights'],
            $data['room_price'],
            $data['base_price'],
            $data['taxes'],
            $data['total_price'],
            $data['booking_status'],
            $data['payment_status'],
            $data['booking_date']
        ]);
    }

    // Get all bookings by user
    public function getBookingsByUserId($conn, $userId) {
        $hasRatingsTable = false;

        try {
            $tableCheck = $conn->query("SHOW TABLES LIKE 'booking_ratings'");
            $hasRatingsTable = $tableCheck && $tableCheck->fetch(PDO::FETCH_NUM);
        } catch (\Exception $e) {
            $hasRatingsTable = false;
        }

        if ($hasRatingsTable) {
            $sql = "SELECT 
                        b.*,
                        (
                            SELECT ai.image_path
                            FROM accommodation_images ai
                            WHERE ai.accommodation_id = b.accommodation_id
                            ORDER BY ai.is_main DESC, ai.id ASC
                            LIMIT 1
                        ) AS accommodation_photo,
                        br.rating AS user_rating,
                        br.review AS user_review,
                        br.created_at AS rating_created_at
                    FROM bookings b
                    LEFT JOIN booking_ratings br
                        ON BINARY br.booking_id = BINARY b.booking_id
                        AND br.user_id = b.user_id
                        AND (
                            (br.accommodation_id IS NULL AND b.accommodation_id IS NULL)
                            OR br.accommodation_id = b.accommodation_id
                        )
                    WHERE b.user_id = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT 
                    b.*,
                    (
                        SELECT ai.image_path
                        FROM accommodation_images ai
                        WHERE ai.accommodation_id = b.accommodation_id
                        ORDER BY ai.is_main DESC, ai.id ASC
                        LIMIT 1
                    ) AS accommodation_photo,
                    NULL AS user_rating,
                    NULL AS user_review,
                    NULL AS rating_created_at
                FROM bookings b
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single booking
    public function getBookingById($conn, $bookingId, $userId) {
        $sql = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$bookingId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update booking status
    public function updateBookingStatus($conn, $bookingId, $status, $userId) {
        $sql = "UPDATE bookings 
                SET booking_status = ?, updated_at = NOW() 
                WHERE booking_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$status, $bookingId, $userId]);
    }

    // Update booking details
    public function updateBooking($conn, $bookingId, $userId, $data) {
        $sql = "UPDATE bookings 
                SET checkin_date = ?, checkout_date = ?, adults = ?, children = ?, 
                    nights = ?, room_price = ?, base_price = ?, taxes = ?, 
                    total_price = ?, updated_at = NOW() 
                WHERE booking_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['checkin_date'],
            $data['checkout_date'],
            $data['adults'],
            $data['children'],
            $data['nights'],
            $data['room_price'],
            $data['base_price'],
            $data['taxes'],
            $data['total_price'],
            $bookingId,
            $userId
        ]);
    }

    // Cancel booking
    public function cancelBooking($conn, $bookingId, $userId) {
        $sql = "UPDATE bookings 
                SET booking_status = 'cancelled', updated_at = NOW() 
                WHERE booking_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$bookingId, $userId]);
    }

    // Delete booking
    public function deleteBooking($conn, $bookingId, $userId) {
        $sql = "DELETE FROM bookings WHERE booking_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$bookingId, $userId]);
    }

    // Get bookings by status
    public function getBookingsByStatus($conn, $userId, $status) {
        $sql = "SELECT * FROM bookings 
                WHERE user_id = ? AND booking_status = ? 
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get upcoming bookings
    public function getUpcomingBookings($conn, $userId) {
        $sql = "SELECT * FROM bookings 
                WHERE user_id = ? 
                AND checkin_date >= CURDATE() 
                AND booking_status != 'cancelled' 
                ORDER BY checkin_date ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get past bookings
    public function getPastBookings($conn, $userId) {
        $sql = "SELECT * FROM bookings 
                WHERE user_id = ? 
                AND checkout_date < CURDATE() 
                ORDER BY checkout_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get current bookings (active)
    public function getCurrentBookings($conn, $userId) {
        $sql = "SELECT * FROM bookings 
                WHERE user_id = ? 
                AND checkin_date <= CURDATE() 
                AND checkout_date >= CURDATE() 
                AND booking_status = 'confirmed'
                ORDER BY checkin_date ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get booking statistics
    public function getBookingStats($conn, $userId) {
        $sql = "SELECT 
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(total_price) as total_spent
                FROM bookings 
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Search bookings
    public function searchBookings($conn, $userId, $searchTerm) {
        $sql = "SELECT * FROM bookings 
                WHERE user_id = ? 
                AND (room_name LIKE ? OR booking_id LIKE ?)
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $searchTerm . '%';
        $stmt->execute([$userId, $searchParam, $searchParam]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveBookingRating($conn, $bookingId, $userId, $accommodationId, $rating, $review = null) {
        $hasRatingsTable = false;

        try {
            $tableCheck = $conn->query("SHOW TABLES LIKE 'booking_ratings'");
            $hasRatingsTable = $tableCheck && $tableCheck->fetch(PDO::FETCH_NUM);
        } catch (\Exception $e) {
            $hasRatingsTable = false;
        }

        if (!$hasRatingsTable) {
            return false;
        }

        $sql = "INSERT INTO booking_ratings (booking_id, user_id, accommodation_id, rating, review, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    rating = VALUES(rating),
                    review = VALUES(review),
                    updated_at = NOW()";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $bookingId,
            $userId,
            $accommodationId,
            $rating,
            $review
        ]);
    }
}