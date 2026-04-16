-- Add location and price_per_night columns to accommodations table
-- This migration adds essential fields for property listings

-- Add location column if it doesn't exist
ALTER TABLE accommodations 
ADD COLUMN IF NOT EXISTS location VARCHAR(255) NULL AFTER description;

-- Add price_per_night column if it doesn't exist
ALTER TABLE accommodations 
ADD COLUMN IF NOT EXISTS price_per_night DECIMAL(10, 2) NULL DEFAULT 0 AFTER max_guests;

-- Add index for location-based searches
ALTER TABLE accommodations 
ADD INDEX IF NOT EXISTS idx_location (location);

-- Add index for price-based searches
ALTER TABLE accommodations 
ADD INDEX IF NOT EXISTS idx_price (price_per_night);
