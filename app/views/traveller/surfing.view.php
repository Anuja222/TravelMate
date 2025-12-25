<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Surfing</title>
    <link rel="stylesheet" href="assets/css/Traveller/surfing.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background"></div>
        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Surfing in Sri Lanka</h1>
                <p>Experience world-class surf breaks and pristine beaches along Sri Lanka's stunning coastline</p>
            </div>
        </div>
    </section>

    <!-- Surfing Spots Section -->
    <section class="beaches-section">
        <div class="container">
            <div class="section-header">
                <h2>Premier Surf Spots</h2>
            </div>
            
            <div class="beaches-grid">
                <div class="card" onclick="openModal('hikkaduwa')">
                    <div class="card-image">
                        <img src="assets/images/surfinghikkaduwa.png" alt="Hikkaduwa Surfing">
                        <div class="card-overlay">
                            <button class="explore-btn" onclick="exploreBeach('hikkaduwa')">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Hikkaduwa</h3>
                        <p>Surfing in Hikkaduwa offers warm waters, gentle to moderate waves, and a vibrant beach atmosphere, making it ideal for both beginners and experienced surfers.</p>
                    </div>
                </div>

                <div class="card" onclick="openModal('arugam')">
                    <div class="card-image">
                        <img src="assets/images/surfingarugambay.png" alt="Arugam Bay Surfing">
                        <div class="card-overlay">
                            <button class="explore-btn" onclick="exploreBeach('arugam')">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Arugam Bay</h3>
                        <p>Surfing in Arugambay is world-famous for its long, consistent waves, attracting surfers from around the globe to ride in a laid-back, tropical beach setting.</p>
                    </div>
                </div>

                <div class="card" onclick="openModal('mirissa')">
                    <div class="card-image">
                        <img src="assets/images/surfingmirissa.png" alt="Mirissa Surfing">
                        <div class="card-overlay">
                            <button class="explore-btn" onclick="exploreBeach('mirissa')">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Mirissa</h3>
                        <p>Surfing in Mirissa features gentle waves and a relaxed beach vibe, perfect for beginners and those looking to enjoy a scenic coastal ride.</p>
                    </div>
                </div>

                <div class="card" onclick="openModal('ahangama')">
                    <div class="card-image">
                        <img src="assets/images/surfingahangama.png" alt="Ahangama Surfing">
                        <div class="card-overlay">
                            <button class="explore-btn" onclick="exploreBeach('ahangama')">Explore</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Ahangama</h3>
                        <p>Surfing in Ahangama Beach offers consistent waves and a calm atmosphere, making it a great spot for both beginners and experienced surfers.</p>
                    </div>
                </div>
            </div>

            <!-- <div class="back-button-container">
                <button class="back-btn" onclick="goBack()">
                    <span class="back-icon">←</span>
                    Back
                </button>
            </div> -->

            <!-- <div style="text-align: center; margin-top: 3em;">
                <button class="user-btn" style="padding: 1em 2.5em; font-size: 1.1em;">
                    See Accommodations →
                </button>
            </div> -->
        </div>
    </section>

    <!-- Modals for each surf spot -->
    <div id="hikkaduwa-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Hikkaduwa Surf Spot</h3>
                <button class="close-btn" onclick="closeModal('hikkaduwa')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="beach-details">
                    <div class="beach-image">
                        <img src="images/hikkaduwa-surf.jpg" alt="Hikkaduwa">
                    </div>
                    <div class="beach-info">
                        <h4>Hikkaduwa Beach</h4>
                        <p>Hikkaduwa is one of Sri Lanka's most famous surf destinations, known for its consistent waves and vibrant beach culture. Perfect for both beginners and intermediate surfers, this spot offers excellent conditions year-round with the best waves from November to April.</p>
                        
                        <div class="beach-features">
                            <h5>What to Expect:</h5>
                            <ul>
                                <li>Consistent 2-6 foot waves</li>
                                <li>Sandy bottom with coral reef</li>
                                <li>Multiple surf schools available</li>
                                <li>Vibrant nightlife and restaurants</li>
                                <li>Equipment rental readily available</li>
                                <li>Great for beginners to intermediate</li>
                            </ul>
                        </div>
                        
                        <div class="modal-actions">
                            <button class="user-btn">Book Surf Lesson</button>
                            <button class="user-btn" style="background: #f0f0f0; color: #333;">View Accommodations</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="arugam-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Arugam Bay Surf Spot</h3>
                <button class="close-btn" onclick="closeModal('arugam')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="beach-details">
                    <div class="beach-image">
                        <img src="images/arugam-bay-surf.jpg" alt="Arugam Bay">
                    </div>
                    <div class="beach-info">
                        <h4>Arugam Bay</h4>
                        <p>Arugam Bay is considered the crown jewel of Sri Lankan surf spots. This world-class right-hand point break attracts surfers from around the globe with its powerful, consistent waves and laid-back atmosphere. Best surfed from April to October.</p>
                        
                        <div class="beach-features">
                            <h5>What to Expect:</h5>
                            <ul>
                                <li>World-class right-hand point break</li>
                                <li>Powerful waves up to 8+ feet</li>
                                <li>Rocky/reef bottom</li>
                                <li>International surf community</li>
                                <li>Multiple surf breaks nearby</li>
                                <li>Best for intermediate to advanced</li>
                            </ul>
                        </div>
                        
                        <div class="modal-actions">
                            <button class="user-btn">Book Surf Guide</button>
                            <button class="user-btn" style="background: #f0f0f0; color: #333;">Find Accommodation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mirissa-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Mirissa Surf Spot</h3>
                <button class="close-btn" onclick="closeModal('mirissa')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="beach-details">
                    <div class="beach-image">
                        <img src="images/mirissa-surf.jpg" alt="Mirissa">
                    </div>
                    <div class="beach-info">
                        <h4>Mirissa Beach</h4>
                        <p>Mirissa offers a perfect blend of surfing and relaxation. Known for its crescent-shaped bay and palm-fringed beaches, this spot provides gentle waves ideal for beginners while offering stunning sunsets and whale watching opportunities.</p>
                        
                        <div class="beach-features">
                            <h5>What to Expect:</h5>
                            <ul>
                                <li>Gentle, beginner-friendly waves</li>
                                <li>Beautiful crescent-shaped bay</li>
                                <li>Sandy bottom</li>
                                <li>Whale watching tours available</li>
                                <li>Stunning sunset views</li>
                                <li>Perfect for beginners</li>
                            </ul>
                        </div>
                        
                        <div class="modal-actions">
                            <button class="user-btn">Book Beginner Lesson</button>
                            <button class="user-btn" style="background: #f0f0f0; color: #333;">Whale Watching Tour</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="coconut-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Coconut Tree Hill Surf Spot</h3>
                <button class="close-btn" onclick="closeModal('coconut')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="beach-details">
                    <div class="beach-image">
                        <img src="images/coconut-tree-surf.jpg" alt="Coconut Tree Hill">
                    </div>
                    <div class="beach-info">
                        <h4>Coconut Tree Hill</h4>
                        <p>Located near Mirissa, Coconut Tree Hill offers a more secluded surfing experience with Instagram-worthy scenery. The iconic coconut palm trees create a tropical paradise backdrop while you enjoy consistent waves in a less crowded setting.</p>
                        
                        <div class="beach-features">
                            <h5>What to Expect:</h5>
                            <ul>
                                <li>Secluded surf spot</li>
                                <li>Instagram-perfect scenery</li>
                                <li>Less crowded waves</li>
                                <li>Iconic coconut palm backdrop</li>
                                <li>Good for photos and surfing</li>
                                <li>Suitable for all levels</li>
                            </ul>
                        </div>
                        
                        <div class="modal-actions">
                            <button class="user-btn">Book Photo Session</button>
                            <button class="user-btn" style="background: #f0f0f0; color: #333;">Private Surf Lesson</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>
        // Modal functionality
        function openModal(spotName) {
            const modal = document.getElementById(spotName + '-modal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(spotName) {
            const modal = document.getElementById(spotName + '-modal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Back button functionality
        function goBack() {
            window.history.back();
        }

        // Add scroll animations
        function addScrollAnimations() {
            const cards = document.querySelectorAll('.beach-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('fade-in');
                        }, index * 100);
                    }
                });
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            addScrollAnimations();
            
            // Add keyboard navigation for modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const activeModal = document.querySelector('.modal.active');
                    if (activeModal) {
                        activeModal.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                }
            });
        });

        // Add hover effects for cards
        document.querySelectorAll('.beach-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>