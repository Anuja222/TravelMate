<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Property - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/viewProperty.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
</head>
<body>
    <!-- Header/Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="/TravelMate/public/assets/images/logo.jpg" class="logo" alt="TravelMate Logo">
                <h2>TravelMate</h2>
            </div>
            <ul class="nav-links">
                <li><a href="/TravelMate/public/ac_dashboard">Dashboard</a></li>
                <li><a href="/TravelMate/about">About Us</a></li>
                <li><a href="/TravelMate/contact">Contact Us</a></li>
                <li><a href="/TravelMate/feed">Blog</a></li>
            </ul>
            <div class="nav-actions">
                <a href="/TravelMate/public/acc_setting">
                    <img src="/TravelMate/public/assets/images/profile.jpg" class="user-icon" alt="User Icon">
                </a>
            </div>
        </nav>
    </header>

    <div class="main-container">
<?php
    // Fetch accommodation data from database
    global $pdo;
    require_once __DIR__ . '/../../models/Accommodation.php';
    
    use App\Models\Accommodation;
    
    // Get accommodation ID from route
    if (!isset($accommodationId) || empty($accommodationId)) {
        echo '<p style="text-align: center; padding: 40px; color: #d32f2f;">Property not found</p>';
        echo '</div></body></html>';
        exit;
    }
    
    // Fetch property details
    $accommodation = Accommodation::findById($pdo, $accommodationId);
    
    if (!$accommodation) {
        echo '<p style="text-align: center; padding: 40px; color: #d32f2f;">Property not found</p>';
        echo '</div></body></html>';
        exit;
    }
    
    // Fetch property images
    $images = Accommodation::getImages($pdo, $accommodationId);
    $mainImage = !empty($images) ? $images[0]['image_path'] : '/public/assets/images/default-property.jpg';
?>
        <div class="property-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button onclick="window.location.href='/TravelMate/public/ac_dashboard'" style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">← Back to Dashboard</button>
                <h1 style="margin: 0;"><?php echo htmlspecialchars($accommodation['title']); ?></h1>
            </div>
            <a class="delete-btn" href="/TravelMate/public/deleteAccommodation/<?php echo htmlspecialchars($accommodationId); ?>" onclick="return confirm('Are you sure you want to delete this property?');">Delete Property</a>
        </div>
        
        <div class="image-gallery">
            <div class="gallery-container">
                <div class="main-image">
                    <img id="mainImage" src="/TravelMate/<?php echo htmlspecialchars($mainImage); ?>" alt="<?php echo htmlspecialchars($accommodation['title']); ?>" onerror="this.src='/TravelMate/public/assets/images/default-property.jpg'">
                </div>
                <div class="image-grid">
                    <?php 
                        // Display all images as thumbnails (skip first if there are more)
                        $imagesToShow = count($images) > 1 ? array_slice($images, 1, 3) : [];
                        if (count($imagesToShow) < 3 && !empty($images)) {
                            // If we don't have 3 thumbnails, include the main image too
                            $imagesToShow = array_slice($images, 0, min(3, count($images)));
                        }
                        
                        foreach ($imagesToShow as $image):
                    ?>
                        <img src="/TravelMate/<?php echo htmlspecialchars($image['image_path']); ?>" 
                             alt="Property image"
                             onclick="document.getElementById('mainImage').src='/TravelMate/<?php echo htmlspecialchars($image['image_path']); ?>'"
                             style="cursor: pointer;"
                             onerror="this.src='/TravelMate/public/assets/images/default-property.jpg'">
                    <?php endforeach; ?>
                </div>
                <button class="nav-btn prev" onclick="scrollImages('left')" style="display: <?php echo count($images) > 4 ? 'block' : 'none'; ?>">❮</button>
                <button class="nav-btn next" onclick="scrollImages('right')" style="display: <?php echo count($images) > 4 ? 'block' : 'none'; ?>">❯</button>
            </div>
        </div>

        <div class="property-content">
            <section class="property-details">
                <h2>Property Details</h2>
                <p style="margin-bottom: 20px; color: #666;"><?php echo htmlspecialchars($accommodation['description']); ?></p>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Property Type</span>
                        <span><?php echo htmlspecialchars(ucfirst($accommodation['property_type'])); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Location</span>
                        <span><?php echo htmlspecialchars($accommodation['location']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Bedrooms</span>
                        <span><?php echo htmlspecialchars($accommodation['rooms']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Maximum guests</span>
                        <span><?php echo htmlspecialchars($accommodation['max_guests']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Bathroom count</span>
                        <span><?php echo htmlspecialchars($accommodation['bathrooms']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Children allowed</span>
                        <span><?php echo $accommodation['pets'] === 'yes' ? 'Yes' : 'No'; ?></span>
                    </div>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/public/editAccommodationFeatures">Edit</a>
                </div>
            </section>

            <section class="property-things">
                <h2>Property Features & Amenities</h2>
                <?php
                    // Define feature mapping organized by categories
                    $featureCategories = [
                        'General' => [
                            'feature_air_conditioning' => 'Air conditioning',
                            'feature_heating' => 'Heating',
                            'feature_wifi' => 'Free Wifi',
                            'feature_ev_charging' => 'EV charging station',
                            'feature_pool' => 'Swimming pool',
                        ],
                        'Cooking and Cleaning' => [
                            'feature_kitchen' => 'Kitchen',
                            'feature_kitchenette' => 'Kitchenette',
                            'feature_washing_machine' => 'Washing machine',
                        ],
                        'Entertainment' => [
                            'feature_tv' => 'Flat-screen TV',
                            'feature_entertainment_pool' => 'Entertainment pool',
                            'feature_hot_tub' => 'Hot tub',
                            'feature_minibar' => 'Minibar',
                            'feature_sauna' => 'Sauna',
                        ],
                        'Outside and View' => [
                            'feature_balcony' => 'Balcony',
                            'feature_garden_view' => 'Garden view',
                            'feature_terrace' => 'Terrace',
                            'feature_view' => 'View',
                        ],
                        'Safety and Security' => [
                            'feature_cctv' => 'CCTV',
                            'feature_security_guards' => 'Security guards',
                            'feature_first_aid_kit' => 'First aid kit',
                        ],
                        'Living Area' => [
                            'feature_living_room' => 'Living Room',
                        ]
                    ];
                    
                    $hasAnyFeature = false;
                    
                    foreach ($featureCategories as $category => $features) {
                        $categoryHasFeatures = false;
                        $categoryHtml = '<div style="margin-bottom: 25px;">';
                        $categoryHtml .= '<h3 style="font-size: 16px; margin-bottom: 10px; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 8px;">' . htmlspecialchars($category) . '</h3>';
                        $categoryHtml .= '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">';
                        
                        foreach ($features as $key => $label) {
                            if (isset($accommodation[$key]) && $accommodation[$key] == 1) {
                                $categoryHtml .= '<div style="padding: 10px; background: #e8f4f8; border-left: 4px solid #007bff; border-radius: 4px; display: flex; align-items: center; gap: 8px;">
                                    <span style="color: #007bff; font-size: 18px; font-weight: bold;">✓</span>
                                    <span>' . htmlspecialchars($label) . '</span>
                                </div>';
                                $categoryHasFeatures = true;
                                $hasAnyFeature = true;
                            }
                        }
                        
                        $categoryHtml .= '</div></div>';
                        
                        if ($categoryHasFeatures) {
                            echo $categoryHtml;
                        }
                    }
                    
                    if (!$hasAnyFeature) {
                        echo '<p style="color: #666; padding: 15px; background: #f5f5f5; border-radius: 4px;">No features listed yet.</p>';
                    }
                ?>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/public/editAccommodationFeatures">Edit</a>
                </div>                
            </section>

            <section class="prices">
                <h2>Prices</h2>
                <div class="price-row">
                    <span>Price per night</span>
                    <span><?php echo htmlspecialchars($accommodation['price_per_night']); ?> LKR</span>
                </div>
                <div class="price-row">
                    <span>Price per guest</span>
                    <span><?php echo htmlspecialchars($accommodation['price_per_guest']); ?> LKR</span>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/public/editPrice">Edit</a>
                </div>                
            </section>

            <section class="house-rules">
                <h2>House Rules</h2>
                <div class="rules-list">
                    <div class="rule-row">
                        <span>Smoking Allowed</span>
                        <span><?php echo $accommodation['smoking'] ? 'Yes' : 'No'; ?></span>
                    </div>
                    <div class="rule-row">
                        <span>Parties Allowed</span>
                        <span><?php echo $accommodation['parties'] ? 'Yes' : 'No'; ?></span>
                    </div>
                    <div class="rule-row">
                        <span>Pets Allowed</span>
                        <span><?php echo htmlspecialchars(ucfirst($accommodation['pets'])); ?></span>
                    </div>
                    <div class="rule-row">
                        <span>Check-in</span>
                        <span><?php echo htmlspecialchars($accommodation['check_in_start']); ?> - <?php echo htmlspecialchars($accommodation['check_in_end']); ?></span>
                    </div>
                    <div class="rule-row">
                        <span>Check-out</span>
                        <span><?php echo htmlspecialchars($accommodation['check_out_time']); ?></span>
                    </div>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/public/editHouseRules">Edit</a>
                </div>                
            </section>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script src="/TravelMate/public/assets/js/Accommodation/viewProperty.js"></script>
</body>
</html>
