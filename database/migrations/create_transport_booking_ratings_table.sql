CREATE TABLE IF NOT EXISTS transport_booking_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    vehicle_id INT NULL,
    rating TINYINT NOT NULL,
    review TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_transport_booking_ratings_rating CHECK (rating BETWEEN 1 AND 5),
    UNIQUE KEY uq_transport_booking_ratings_booking_user (booking_id, user_id),
    INDEX idx_transport_booking_ratings_user (user_id),
    INDEX idx_transport_booking_ratings_vehicle (vehicle_id)
);
