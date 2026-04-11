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

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$requestPath = rtrim($requestPath, '/');
$currentRoute = strtolower(basename($requestPath));

if ($currentRoute === '' || $currentRoute === 'public' || $currentRoute === 'index.php') {
    if ($role === 'traveller') {
        $currentRoute = 'homet';
    } elseif ($role === 'transport') {
        $currentRoute = 'tr_dashboard';
    } elseif ($role === 'accommodation') {
        $currentRoute = 'ac_dashboard';
    } else {
        $currentRoute = 'home';
    }
}

$homeRouteByRole = [
    'traveller' => 'homet',
    'transport' => 'tr_dashboard',
    'accommodation' => 'ac_dashboard',
    'admin' => 'ad_dashboard'
];

$homeRoute = $homeRouteByRole[$role] ?? 'home';
$routeMeta = [
    'homet' => ['label' => 'Home'],
    'dashboard' => ['label' => 'My Profile'],
    'profile' => ['label' => 'Profile'],
    'profilesetting' => ['label' => 'Profile Settings', 'parent' => 'profile'],
    'favdestination' => ['label' => 'Destinations'],
    'destinationview' => ['label' => 'Destination Details', 'parent' => 'favdestination'],
    'favactivity' => ['label' => 'Activities'],
    'activityview' => ['label' => 'Activity Details', 'parent' => 'favactivity'],
    'accommodation' => ['label' => 'Accommodations'],
    'accommodationdetail' => ['label' => 'Accommodation Details', 'parent' => 'accommodation'],
    'booking_availability' => ['label' => 'Availability', 'parent' => 'accommodationdetail'],
    'booking_details' => ['label' => 'Booking Details', 'parent' => 'booking_availability'],
    'booking_payment' => ['label' => 'Payment', 'parent' => 'booking_details'],
    'booking_finish' => ['label' => 'Booking Confirmed', 'parent' => 'booking_payment'],
    'transport' => ['label' => 'Transport'],
    'transportdetails' => ['label' => 'Transport Details', 'parent' => 'transport'],
    'transport_booking_details' => ['label' => 'Booking Details', 'parent' => 'transportdetails'],
    'transport_booking_payment' => ['label' => 'Payment', 'parent' => 'transport_booking_details'],
    'transport_booking_finish' => ['label' => 'Booking Confirmed', 'parent' => 'transport_booking_payment'],
    'mybookings' => ['label' => 'My Bookings'],
    'mybooking_details' => ['label' => 'Booking Details', 'parent' => 'mybookings'],
    'mytransportbookings' => ['label' => 'Transport Bookings'],
    'feed' => ['label' => 'Vlog'],
    'blog' => ['label' => 'Blog'],
    'about' => ['label' => 'About Us'],
    'contact' => ['label' => 'Contact Us']
];

$buildBreadcrumb = function ($route) use (&$buildBreadcrumb, $routeMeta) {
    if (!isset($routeMeta[$route])) {
        return [];
    }

    $meta = $routeMeta[$route];
    $items = [];

    if (!empty($meta['parent'])) {
        $items = $buildBreadcrumb($meta['parent']);
    }

    $items[] = [
        'route' => $route,
        'label' => $meta['label']
    ];

    return $items;
};

$directionItems = [];
if ($role === 'traveller') {
    $directionItems[] = ['route' => 'homet', 'label' => 'Home'];
    $dynamicItems = $buildBreadcrumb($currentRoute);

    if (!empty($dynamicItems)) {
        foreach ($dynamicItems as $item) {
            if ($item['route'] === 'homet') {
                continue;
            }
            $directionItems[] = $item;
        }
    } elseif ($currentRoute !== 'homet') {
        $directionItems[] = [
            'route' => $currentRoute,
            'label' => ucwords(str_replace(['_', '-'], ' ', $currentRoute))
        ];
    }
}
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
                    <li><a href="home" class="<?php echo $currentRoute === 'home' ? 'active' : ''; ?>">Home</a></li>
                <?php else: ?>
                    <?php if ($role === 'traveller'): ?>
                        <li><a href="homet" class="<?php echo $currentRoute === 'homet' ? 'active' : ''; ?>">Home</a></li>
                    <?php elseif ($role === 'transport'): ?>
                        <li><a href="tr_dashboard" class="<?php echo $currentRoute === 'tr_dashboard' ? 'active' : ''; ?>">Home</a></li>
                    <?php elseif ($role === 'accommodation'): ?>
                        <li><a href="ac_dashboard" class="<?php echo $currentRoute === 'ac_dashboard' ? 'active' : ''; ?>">Home</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li><a href="about" class="<?php echo $currentRoute === 'about' ? 'active' : ''; ?>">About Us</a></li>
                <li><a href="contact" class="<?php echo $currentRoute === 'contact' ? 'active' : ''; ?>">Contact Us</a></li>
                <?php if ($role === 'traveller'): ?>
                    <li><a href="feed" class="<?php echo $currentRoute === 'feed' ? 'active' : ''; ?>">Vlog</a></li>
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
                    <?php 
                    $rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
                    $profileImgHeader = !empty($_SESSION['user']['profile_image']) ? $rootUrl . '/' . $_SESSION['user']['profile_image'] : 'assets/images/profile.jpg';
                    ?>
                    <img src="<?php echo htmlspecialchars($profileImgHeader); ?>" class="user-icon" alt="User Icon" id="userMenuBtn">
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

    <?php if ($role === 'traveller' && $isLoggedIn): ?>
        <div class="page-direction-wrap">
            <div class="page-direction" aria-label="Current page direction">
                <span class="direction-label">You are here</span>
                <div class="direction-path">
                    <?php foreach ($directionItems as $index => $item): ?>
                        <?php if ($index > 0): ?>
                            <span class="direction-separator">›</span>
                        <?php endif; ?>

                        <?php if ($index === count($directionItems) - 1): ?>
                            <span class="direction-current"><?php echo htmlspecialchars($item['label']); ?></span>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($item['route']); ?>" class="direction-link">
                                <?php echo htmlspecialchars($item['label']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
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