<?php
/**
 * Database Migration Runner
 * Run this file once to add location and price_per_night columns to accommodations table
 */

require_once __DIR__ . '/../../config/database.php';

try {
    echo "Starting migration...\n";
    
    $migrationFile = __DIR__ . '/../migrations/add_location_price_to_accommodations.sql';
    $sql = file_get_contents($migrationFile);
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
        } catch (PDOException $e) {
            // If column already exists, that's okay
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "⊘ Column already exists, skipping...\n";
            } else {
                throw $e;
            }
        }
    }
    
    echo "\n✅ Migration completed successfully!\n";
    echo "The accommodations table now has 'location' and 'price_per_night' columns.\n";
    
} catch (Exception $e) {
    echo "\n❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
