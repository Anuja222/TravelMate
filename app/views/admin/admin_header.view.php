<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config for ROOT constant
require_once __DIR__ . '/../../core/config.php';

// Check if user is logged in and is admin
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$role = $isLoggedIn ? $_SESSION['user']['role'] : '';
?>

<header>
    <nav class="navbar">
        <div class="logo-container">
            <img src="<?= ROOT ?>/assets/images/logo.jpg" class="logo" alt="TravelMate Logo">
            <h2>TravelMate</h2>
        </div>
        <ul class="nav-links">
            <li><a href="<?= ROOT ?>/home">Home</a></li>
            <li><a href="<?= ROOT ?>/ad_dashboard">Dashboard</a></li>
            <li><a href="<?= ROOT ?>/about">About Us</a></li>
            <li><a href="<?= ROOT ?>/contact">Contact Us</a></li>
        </ul>

        <?php if ($isLoggedIn): ?>
            <div class="nav-actions">
                <div class="notification-container">
                    <div class="notification-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="notification-badge"></span>
                    </div>
                </div>

                <div class="user-menu">
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" class="user-icon" alt="User Icon" id="userMenuBtn">
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-info">
                            <strong><?php echo htmlspecialchars($firstName); ?>
                                <?php echo htmlspecialchars($lastName); ?></strong>
                            <span><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                        </div>
                        <hr>
                        <a href="<?= ROOT ?>/ad_dashboard">My Profile</a>
                        <hr>
                        <a href="<?= ROOT ?>/logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });

            document.addEventListener('click', function () {
                userDropdown.classList.remove('active');
            });

            userDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    });
</script>
