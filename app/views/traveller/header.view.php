<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$role = $isLoggedIn ? $_SESSION['user']['role'] : '';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<header>
    <nav class="navbar">
        <div class="logo-container">
            <img src="assets/images/logo.jpg" class="logo" alt="TravelMate Logo">
            <h2>TravelMate</h2>
        </div>
        <ul class="nav-links">
            <?php if ($role !== 'admin'): ?>
                <?php if (!$isLoggedIn): ?>
                    <li><a href="home">Home</a></li>
                <?php else: ?>
                    <?php if ($role === 'traveller'): ?>
                        <li><a href="homet">Home</a></li>
                    <?php elseif ($role === 'transport'): ?>
                        <li><a href="tr_dashboard">Home</a></li>
                    <?php elseif ($role === 'accommodation'): ?>
                        <li><a href="ac_dashboard">Home</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li><a href="about">About Us</a></li>
                <li><a href="contact">Contact Us</a></li>
                <?php if ($role === 'traveller'): ?>
                    <li><a href="feed">Vlog</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <?php if (!$isLoggedIn): ?>
            <!-- Show when user is NOT logged in -->
            <div class="nav-actions">
                <a href="signupmodel" class="btn signup">Sign Up</a>
                <a href="login" class="btn login">Login</a>
            </div>
        <?php else: ?>
            <!-- Show when user IS logged in -->
            <div class="nav-actions">
                <div class="notification-container">
                    <div class="notification-icon">
                        <?php if ($role === 'admin'): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="notification-badge"></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="user-menu">
                    <img src="assets/images/profile.jpg" class="user-icon" alt="User Icon" id="userMenuBtn">
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-info">
                            <strong><?php echo htmlspecialchars($firstName); ?>
                                <?php echo htmlspecialchars($lastName); ?></strong>
                            <span><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                        </div>
                        <hr>
                        <?php if ($role === 'traveller'): ?>
                            <a href="dashboard">My Profile</a>
                        <?php elseif ($role === 'transport'): ?>
                            <a href="tr_dashboard">My Profile</a>
                        <?php elseif ($role === 'admin'): ?>
                            <a href="ad_dashboard">My Profile</a>
                        <?php elseif ($role === 'accommodation'): ?>
                        <li><a href="ac_dashboard">My Profile</a></li>
                        <?php endif; ?>
                        <hr>
                        <a href="logout" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </nav>
</header>

<style>
    .user-menu {
        position: relative;
    }

    .user-icon {
        cursor: pointer;
    }

    .user-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 10px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        z-index: 1000;
    }

    .user-dropdown.active {
        display: block;
    }

    .user-info {
        padding: 15px;
        display: flex;
        flex-direction: column;
    }

    .user-info strong {
        font-size: 16px;
        color: #333;
        margin-bottom: 4px;
    }

    .user-info span {
        font-size: 13px;
        color: #666;
    }

    .user-dropdown hr {
        margin: 0;
        border: none;
        border-top: 1px solid #eee;
    }

    .user-dropdown a {
        display: block;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
    }

    .user-dropdown a:hover {
        background: #f5f5f5;
    }

    .logout-link {
        color: #e74c3c !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function () {
                userDropdown.classList.remove('active');
            });

            // Prevent dropdown from closing when clicking inside it
            userDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    });
</script>