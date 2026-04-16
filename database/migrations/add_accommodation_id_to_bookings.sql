-- Add accommodation_id column to bookings table
ALTER TABLE bookings 
ADD COLUMN accommodation_id INT NULL AFTER booking_id,
ADD INDEX idx_accommodation_id (accommodation_id);

-- Optionally add foreign key if accommodations table exists
-- ALTER TABLE bookings 
-- ADD CONSTRAINT fk_bookings_accommodation 
-- FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) 
-- ON DELETE SET NULL;
