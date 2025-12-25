<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Property - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/viewProperty.css">
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
                <li><a href="/TravelMate/Accomodation_provider/dashboard">Dashboard</a></li>
                <li><a href="/TravelMate/about">About Us</a></li>
                <li><a href="/TravelMate/contact">Contact Us</a></li>
                <li><a href="/TravelMate/feed">Blog</a></li>
            </ul>
            <div class="nav-actions">
                <a href="/TravelMate/Accomodation_provider/profilesetting">
                    <img src="/TravelMate/public/assets/images/profile.jpg" class="user-icon" alt="User Icon">
                </a>
            </div>
        </nav>
    </header>

    <div class="main-container">
            <div class="property-header">
            <h1>ABC Villa</h1>
            <a class="delete-btn" href="/TravelMate/Accomodation_provider/deleteProperty">Delete Property</a>
        </div>
        
        <div class="image-gallery">
            <div class="gallery-container">
                <div class="main-image">
                    <img src="/TravelMate/public/assets/images/property1.jpg" alt="Main Property Image">
                </div>
                <div class="image-grid">
                    <img src="/TravelMate/public/assets/images/property2.jpg" alt="Property Image 2">
                    <img src="/TravelMate/public/assets/images/property3.jpg" alt="Property Image 3">
                    <img src="/TravelMate/public/assets/images/property4.jpg" alt="Property Image 4">
                </div>
                <button class="nav-btn prev">❮</button>
                <button class="nav-btn next">❯</button>
            </div>
        </div>

        <div class="property-content">
            <section class="property-details">
                <h2>Property Details</h2>
                <div class="sleeping-area">
                    <h3>Sleeping area</h3>
                    <div class="bedroom-info">
                        <div class="bedroom">
                            <span>Bedroom 1</span>
                            <span>2 Twin Beds</span>
                        </div>
                        <div class="bedroom">
                            <span>Bedroom 2</span>
                            <span>2 Single Beds</span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Maximum guests</span>
                        <span>5</span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Bathroom count</span>
                        <span>5</span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span>Children allow</span>
                        <span>Yes</span>
                    </div>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/Accomodation_provider/editPropertyDetails">Edit</a>
                </div>
            </section>

            <section class="property-things">
                <h2>Property Things</h2>
                <ul>
                    <li>Air conditioning</li>
                    <li>Free Wifi</li>
                    <li>Kitchen</li>
                    <li>Swimming pool</li>
                    <li>Balcony</li>
                </ul>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/Accomodation_provider/editAccommodationFeatures">Edit</a>
                </div>                
            </section>

            <section class="services">
                <h2>Services</h2>
                <ul>
                    <li>Breakfast</li>
                    <li>Parking</li>
                </ul>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/Accomodation_provider/editServices">Edit</a>
                </div>                
            </section>

            <section class="prices">
                <h2>Prices</h2>
                <div class="price-row">
                    <span>Price per night</span>
                    <span>3000 LKR</span>
                </div>
                <div class="price-row">
                    <span>Price per guests</span>
                    <span>3000 LKR</span>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/Accomodation_provider/editPrice">Edit</a>
                </div>                
            </section>

            <section class="house-rules">
                <h2>House Rules</h2>
                <div class="rules-list">
                    <div class="rule-row">
                        <span>Smoking Allowed</span>
                        <span>Yes</span>
                    </div>
                    <div class="rule-row">
                        <span>Pets Allowed</span>
                        <span>Yes</span>
                    </div>
                    <div class="rule-row">
                        <span>Check-in</span>
                        <span>15:00 - 18:00</span>
                    </div>
                    <div class="rule-row">
                        <span>Check-out</span>
                        <span>08:00 - 11:00</span>
                    </div>
                </div>
                <div class="Edit">
                    <a class="edit-button" href="/TravelMate/Accomodation_provider/editHouseRules">Edit</a>
                </div>                
            </section>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <!-- <footer>
        <div class="footer-content">
            <div class="footer-section company">
                <h4>TravelMate</h4>
                <p>Your trusted partner for exploring Sri Lanka. Create memories that last a lifetime.</p>
            </div>
            <div class="footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Help Center</a></li>
                </ul>
            </div>
            <div class="footer-section support">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Live Chat</a></li>
                </ul>
            </div>
            <div class="footer-section connect">
                <h4>Contact Info</h4>
                <p>+94 11 434 4340<br>info@travelmate.lk</p>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; 2024 TravelMate Sri Lanka. All rights reserved.</span>
        </div>
    </footer> -->

    <script src="/TravelMate/public/assets/js/Accomodation_provider/viewProperty.js"></script>
</body>
</html>
