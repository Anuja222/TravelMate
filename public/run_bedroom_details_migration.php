<?php
/**
 * Migration Script: Add bedroom_details column to accommodations table
 * Run this file once to update your database schema
 */

require_once __DIR__ . '/../config/database.php';

try {
    global $pdo;
    
    echo "Starting migration: Add bedroom_details column...\n";
    
    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/../database/migrations/add_bedroom_details_to_accommodations.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "✓ Migration completed successfully!\n";
    echo "The bedroom_details column has been added to the accommodations table.\n";
    
} catch (PDOException $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
