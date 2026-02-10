-- Create activities table
CREATE TABLE IF NOT EXISTS `activities` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `image` VARCHAR(500),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create activity_places table (locations where activities are available)
CREATE TABLE IF NOT EXISTS `activity_places` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `activity_id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `image` VARCHAR(500),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_id` (`activity_id`),
  CONSTRAINT `activity_places_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some sample activities
INSERT INTO `activities` (`title`, `slug`, `description`, `image`, `created_at`) VALUES
('Water Rafting', 'water-rafting', 'Experience thrilling white water rafting adventures through scenic rivers with professional guides.', NULL, NOW()),
('Surfing', 'surfing', 'Ride the waves at some of the best surfing spots with experienced instructors for all skill levels.', NULL, NOW()),
('Bird Watching', 'bird-watching', 'Discover diverse bird species in their natural habitats with guided tours and expert ornithologists.', NULL, NOW()),
('Scuba Diving', 'scuba-diving', 'Explore underwater wonders including coral reefs and marine life with certified diving instructors.', NULL, NOW()),
('Rock Climbing', 'rock-climbing', 'Challenge yourself with rock climbing adventures suitable for beginners to advanced climbers.', NULL, NOW()),
('Kayaking', 'kayaking', 'Paddle through calm waters or challenging rapids in beautiful natural settings.', NULL, NOW()),
('Zip Lining', 'zip-lining', 'Soar through the treetops and enjoy breathtaking views on exciting zip line courses.', NULL, NOW()),
('Wildlife Safari', 'wildlife-safari', 'Observe wildlife in their natural habitat on guided safari tours with experienced naturalists.', NULL, NOW()),
('Paragliding', 'paragliding', 'Experience the thrill of flying and enjoy panoramic views with tandem or solo paragliding.', NULL, NOW()),
('Mountain Biking', 'mountain-biking', 'Ride challenging trails through mountains and forests on guided biking adventures.', NULL, NOW());

-- Insert sample activity places (locations for activities)
INSERT INTO `activity_places` (`activity_id`, `title`, `slug`, `description`, `image`, `created_at`) VALUES
(1, 'Kelani River', 'kelani-river', 'One of the most popular white water rafting destinations in Sri Lanka with varying rapids.', NULL, NOW()),
(1, 'Kithulgala', 'kithulgala', 'Famous rafting location with class 2-3 rapids, surrounded by lush rainforest.', NULL, NOW()),
(2, 'Arugam Bay', 'arugam-bay', 'World-renowned surfing spot with consistent waves perfect for all levels.', NULL, NOW()),
(2, 'Hikkaduwa', 'hikkaduwa', 'Popular beach destination with great surfing conditions and surf schools.', NULL, NOW()),
(3, 'Sinharaja Forest', 'sinharaja-forest', 'UNESCO World Heritage Site with incredible biodiversity and endemic bird species.', NULL, NOW()),
(3, 'Bundala National Park', 'bundala-national-park', 'Important wetland habitat for migratory and resident bird species.', NULL, NOW()),
(4, 'Hikkaduwa Marine Sanctuary', 'hikkaduwa-marine-sanctuary', 'Excellent diving spot with coral reefs and diverse marine life.', NULL, NOW()),
(4, 'Pigeon Island', 'pigeon-island', 'Protected marine national park with crystal clear waters and abundant sea life.', NULL, NOW());
