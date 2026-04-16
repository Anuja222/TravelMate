<?php
require_once __DIR__ . '/../config/database.php';

global $pdo;

echo "<h2>Checking accommodation images</h2>";

// Get all accommodations
$stmt = $pdo->query("SELECT id, title FROM accommodations ORDER BY id DESC LIMIT 5");
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Recent Accommodations:</h3>";
foreach ($accommodations as $acc) {
    echo "<hr>";
    echo "<strong>ID: {$acc['id']} - {$acc['title']}</strong><br>";
    
    // Get images for this accommodation
    $stmt2 = $pdo->prepare("SELECT * FROM accommodation_images WHERE accommodation_id = ?");
    $stmt2->execute([$acc['id']]);
    $images = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($images)) {
        echo "<span style='color: red;'>No images found!</span><br>";
    } else {
        echo "Found " . count($images) . " images:<br>";
        foreach ($images as $img) {
            $fullPath = __DIR__ . '/../public/' . $img['image_path'];
            $exists = file_exists($fullPath) ? '✓ exists' : '✗ missing';
            echo "- {$img['image_path']} (is_main: {$img['is_main']}) $exists<br>";
            echo "  Full path: $fullPath<br>";
        }
    }
}
