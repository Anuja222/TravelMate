<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config for ROOT constant
require_once __DIR__ . '/../../core/config.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Travel Mate</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Traveller/homet.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Traveller/usermain.css">
</head>

<body>

    <?php include __DIR__ . '/../traveller/header.view.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($firstName); ?>!</h1>
            <p>Ready for your next adventure? Discover amazing destinations, book comfortable stays, and find the best
                transport options.</p>
        </div>

        <!-- Popular Destinations Section -->
        <section class="destinations-section">
            <div class="section-header">
                <div class="title-area">
                    <h2>Popular Destinations</h2>
                    <p>Discover the most loved travel spots around Sri Lanka</p>
                </div>
                <a href="<?= ROOT ?>/favdestination" class="see-all-btn">See All Destinations</a>
            </div>

            <div class="destinations-grid" id="popularDestinations">
                <!-- dynamic loaded -->
                <p>Loading destinations...</p>
            </div>
        </section>

        <section class="destinations-section">
            <div class="section-header">
                <div class="title-area">
                    <h2>Popular Activities</h2>
                    <p>Discover thrilling activities and create unforgettable memories in Sri Lanka</p>
                </div>
                <a href="favactivity" class="see-all-btn">See All Activities</a>
            </div>
            <div class="destinations-grid">
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/surfing.png" alt="Surfing">
                        <div class="card-overlay">
                            <a href="surfing" class="explore-btn">Explore</a>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Surfing</h3>
                        <p>Catch the waves, feel the rhythm of the ocean — surfing is where balance meets pure freedom.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/waterafting.png" alt="Water Rafting">
                        <div class="card-overlay">
                            <button class="explore-btn">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Water Rafting</h3>
                        <p>Thrilling rapids, splashing waves, and pure adrenaline — water rafting is where adventure
                            flows wild and free.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/birdwatching.png" alt="Bird Watching">
                        <div class="card-overlay">
                            <button class="explore-btn">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Bird watching</h3>
                        <p>Gentle trails, quiet moments, and wings in flight — bird watching is nature’s calmest
                            spectacle.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/safari.png" alt="Safari">
                        <div class="card-overlay">
                            <button class="explore-btn">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Safari</h3>
                        <p>Golden plains, roaming wildlife, and untamed beauty — a safari is the closest you’ll get to
                            nature’s wild heart.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Accommodation Section -->
        <section class="accommodation-section">
            <div class="section-header">
                <div class="title-area">
                    <h2>Featured Accommodations</h2>
                    <p>Handpicked hotels and resorts for your perfect stay</p>
                </div>
                <a href="accommodation" class="see-all-btn">See All Accommodations</a>
            </div>
            <div class="accommodation-grid">
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/luxuryhotel.png" alt="Luxury Beach Resort">
                        <div class="card-overlay">
                            <a href="accommodationdetail" class="explore-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Luxury Beach Resort</h3>
                        <p>5-star beachfront resort with private pools, spa services, and world-class dining.
                            All-inclusive packages available.</p>
                        <span class="price-tag">Rs.45000/night</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/boutiquehotel.png" alt="Boutique City Hotel">
                        <div class="card-overlay">
                            <button class="explore-btn">Book Now</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Boutique City Hotel</h3>
                        <p>Stylish hotel in the heart of the city. Modern amenities, rooftop bar, and walking distance
                            to major attractions.</p>
                        <span class="price-tag">Rs.18000/night</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/mountainlodge.png" alt="Mountain Lodge">
                        <div class="card-overlay">
                            <button class="explore-btn">Book Now</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Mountain Lodge</h3>
                        <p>Cozy lodge with stunning mountain views. Perfect for hiking enthusiasts and nature lovers
                            seeking tranquility.</p>
                        <span class="price-tag">Rs.12000/night</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/backpackerhostel.png" alt="Budget Hostel">
                        <div class="card-overlay">
                            <button class="explore-btn">Book Now</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Backpacker Hostel</h3>
                        <p>Clean, safe, and social environment for budget travelers. Free WiFi, kitchen facilities, and
                            organized tours.</p>
                        <span class="price-tag">Rs.18000/night</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Transport Section -->
        <section class="transport-section">
            <div class="section-header">
                <div class="title-area">
                    <h2>Transportation Options</h2>
                    <p>Get around with ease using our trusted transport partners</p>
                </div>
                <a href="transport" class="see-all-btn">See All Transport</a>
            </div>
            <div class="transport-grid">
                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/van.png" alt="Van Rental">
                        <div class="card-overlay">
                            <button class="explore-btn">Search Vans</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Van Rental</h3>
                        <div class="rating">⭐⭐⭐⭐⭐ 4.7 (3,421 reviews)</div>
                        <p>Spacious vans perfect for families or small groups. Available in economy and premium models for any journey.</p>
                        <span class="price-tag">Best Deals</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/car.png" alt="Car Rental">
                        <div class="card-overlay">
                            <button class="explore-btn">Rent Car</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Car Rental</h3>
                        <div class="rating">⭐⭐⭐⭐ 4.5 (1,876 reviews)</div>
                        <p>Wide selection of vehicles from economy to luxury. Pick up at airports or city locations
                            worldwide.</p>
                        <span class="price-tag">From $25/day</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/wheel.png" alt="Wheel Rental">
                        <div class="card-overlay">
                            <button class="explore-btn">Book Wheel</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Wheel Rental</h3>
                        <div class="rating">⭐⭐⭐⭐ 4.4 (982 reviews)</div>
                        <p>Easy and affordable rides for city exploration. Choose from scooters or bikes for quick and flexible travel.</p>
                        <span class="price-tag">From $45</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="assets/images/bus.png" alt="Bus Travel">
                        <div class="card-overlay">
                            <button class="explore-btn">Book Bus</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Bus Rental</h3>
                        <div class="rating">⭐⭐⭐⭐ 4.2 (654 reviews)</div>
                        <p>Comfortable buses for group travel. Ideal for tours, events, and airport transfers with professional drivers.</p>
                        <span class="price-tag">From $15</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('popularDestinations');

            const baseApi = (function () {
                const origin = window.location.origin;
                const path = window.location.pathname;
                const publicIndex = path.indexOf('/public');
                if (publicIndex !== -1) return origin + path.substring(0, publicIndex + 7);
                const match = path.match(/(\/[^\/]+\/public)/);
                if (match) return origin + match[1];
                return origin + '/TravelMate/public';
            })();

            fetch(baseApi + '/api/destination/list', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(resp => {
                    if (!resp.success) { container.innerHTML = '<p>Failed to load destinations</p>'; return; }
                    const rows = resp.data || [];
                    container.innerHTML = rows.slice(0, 4).map(d => {
                        const baseUrl = window.location.origin + '/TravelMate/public';
                        const img = d.image ? baseUrl + d.image : 'assets/images/default-dest.png';
                        return `
            <div class="card">
              <div class="card-image">
                <img src="${img}" alt="${escapeHtml(d.title)}">
                <div class="card-overlay">
                  <a href="destination/places?id=${d.id}" class="explore-btn">Explore</a>
                </div>
              </div>
              <div class="card-content">
                <h3>${escapeHtml(d.title)}</h3>
                <p>${escapeHtml((d.description || '').substring(0, 120))}</p>
              </div>
            </div>
          `;
                    }).join('');
                }).catch(err => {
                    console.error(err);
                    container.innerHTML = '<p>Error loading destinations</p>';
                });

            function escapeHtml(text) {
                if (!text) return '';
                return String(text).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": "&#039;" }[m]));
            }
        });

        // Add interactivity to cards and see all buttons
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.card');
            const actionCards = document.querySelectorAll('.action-card');
            const seeAllBtns = document.querySelectorAll('.see-all-btn');

            cards.forEach(card => {
                card.addEventListener('click', function () {
                    const button = this.querySelector('.explore-btn');
                    if (button) {
                        console.log('Clicked:', button.textContent);
                        // Add navigation logic here
                    }
                });
            });

            actionCards.forEach(card => {
                card.addEventListener('click', function () {
                    const title = this.querySelector('h4').textContent;
                    console.log('Quick action:', title);
                    // Add quick action logic here
                });
            });

            seeAllBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    // e.preventDefault();
                    const href = this.getAttribute('href');
                    console.log('See all clicked:', href);
                    // Add navigation logic here
                    // window.location.href = href;
                });
            });
        });
    </script>
</body>

</html>