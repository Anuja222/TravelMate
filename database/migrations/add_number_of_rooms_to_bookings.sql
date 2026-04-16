-- Add number_of_rooms column to bookings table
-- This allows tracking how many rooms were booked for each reservation

ALTER TABLE bookings 
ADD COLUMN number_of_rooms INT DEFAULT 1 NOT NULL AFTER room_name;

-- Update existing records to have 1 room (default)
UPDATE bookings SET number_of_rooms = 1 WHERE number_of_rooms IS NULL;
