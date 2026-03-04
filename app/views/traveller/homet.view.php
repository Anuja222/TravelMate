<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="stylesheet" href="assets/css/Traveller/homet.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

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
                <a href="favdestination" class="see-all-btn">See All Destinations</a>
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
            <div class="destinations-grid" id="popularActivities">
                <!-- dynamic loaded -->
                <p>Loading activities...</p>
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
            <div class="accommodation-grid" id="featuredAccommodations">
                <!-- dynamic loaded -->
                <p>Loading accommodations...</p>
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
                    if (rows.length === 0) {
                        container.innerHTML = '<p>No destinations available</p>';
                        return;
                    }
                    container.style.display = 'grid';
                    container.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                    container.style.gap = '2rem';
                    container.innerHTML = rows.slice(0, 4).map(d => {
                        const baseUrl = window.location.origin + '/TravelMate/public';
                        const img = d.image ? baseUrl + d.image : 'assets/images/default-dest.png';
                        return `
            <div class="card" style="width: 100%; max-width: 100%;">
              <div class="card-image">
                <img src="${img}" alt="${escapeHtml(d.title)}">
                <div class="card-overlay">
                  <a href="destinationview?id=${d.id}" class="explore-btn">Explore</a>
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

            // Load Activities
            const activityContainer = document.getElementById('popularActivities');

            fetch(baseApi + '/api/activity/list', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(resp => {
                    if (!resp.success) { activityContainer.innerHTML = '<p>Failed to load activities</p>'; return; }
                    const activities = resp.data || [];
                    if (activities.length === 0) {
                        activityContainer.innerHTML = '<p>No activities available</p>';
                        return;
                    }
                    activityContainer.style.display = 'grid';
                    activityContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                    activityContainer.style.gap = '2rem';
                    activityContainer.innerHTML = activities.slice(0, 4).map(a => {
                        const baseUrl = window.location.origin + '/TravelMate/public';
                        const img = a.image ? baseUrl + a.image : 'assets/images/default-activity.png';
                        return `
            <div class="card" style="width: 100%; max-width: 100%;">
              <div class="card-image">
                <img src="${img}" alt="${escapeHtml(a.title)}">
                <div class="card-overlay">
                  <a href="activityview?id=${a.id}" class="explore-btn">Explore</a>
                </div>
              </div>
              <div class="card-content">
                <h3>${escapeHtml(a.title)}</h3>
                <p>${escapeHtml((a.description || '').substring(0, 120))}</p>
              </div>
            </div>
          `;
                    }).join('');
                }).catch(err => {
                    console.error(err);
                    activityContainer.innerHTML = '<p>Error loading activities</p>';
                });

            // Load Accommodations
            const accommodationContainer = document.getElementById('featuredAccommodations');

            fetch(baseApi + '/api/accommodation/listAll', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(resp => {
                    if (!resp.success) { accommodationContainer.innerHTML = '<p>Failed to load accommodations</p>'; return; }
                    const accommodations = resp.data || [];
                    if (accommodations.length === 0) {
                        accommodationContainer.innerHTML = '<p>No accommodations available</p>';
                        return;
                    }
                    accommodationContainer.style.display = 'grid';
                    accommodationContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                    accommodationContainer.style.gap = '2rem';
                    accommodationContainer.innerHTML = accommodations.slice(0, 4).map(acc => {
                        const baseUrl = window.location.origin + '/TravelMate/public';
                        const img = acc.main_image ? baseUrl + '/' + acc.main_image : 'assets/images/default-accommodation.png';
                        const price = acc.price_per_night || 0;
                        const formattedPrice = parseFloat(price).toLocaleString('en-US');
                        const description = acc.description || 'Experience comfort and luxury at this amazing property';
                                                const ratingCount = parseInt(acc.rating_count || 0, 10) || 0;
                                                const avgRatingValue = parseFloat(acc.avg_rating || 0);
                                                const ratingStarsHtml = (() => {
                                                    let stars = '';
                                                    for (let index = 1; index <= 5; index++) {
                                                        if (avgRatingValue >= index) {
                                                            stars += '<i class="fas fa-star"></i>';
                                                        } else if (avgRatingValue >= index - 0.5) {
                                                            stars += '<i class="fas fa-star-half-alt"></i>';
                                                        } else {
                                                            stars += '<i class="far fa-star"></i>';
                                                        }
                                                    }
                                                    return stars;
                                                })();
                                                const ratingText = ratingCount > 0 ? `${avgRatingValue.toFixed(1)} (${ratingCount})` : 'Not yet rated';
                        return `
            <div class="card" style="width: 100%; max-width: 100%;">
              <div class="card-image">
                <img src="${img}" alt="${escapeHtml(acc.title)}" onerror="this.src='assets/images/default-accommodation.png'">
                <div class="card-overlay">
                  <a href="accommodationdetail?id=${acc.id}" class="explore-btn">Book Now</a>
                </div>
              </div>
              <div class="card-content">
                <h3>${escapeHtml(acc.title)}</h3>
                <p>${escapeHtml(description.substring(0, 120))}${description.length > 120 ? '...' : ''}</p>
                                <p style="margin:0 0 8px 0; font-size:13px; color:#6b7280; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="color:#f59e0b; display:inline-flex; gap:2px;">${ratingStarsHtml}</span> ${escapeHtml(ratingText)}</p>
                <span class="price-tag">Rs.${formattedPrice}/night</span>
              </div>
            </div>
          `;
                    }).join('');
                }).catch(err => {
                    console.error(err);
                    accommodationContainer.innerHTML = '<p>Error loading accommodations</p>';
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