<?php
// Get current page for active state
$currentPage = basename($_SERVER['REQUEST_URI']);
$currentPage = explode('?', $currentPage)[0]; // Remove query strings
?>

<aside class="sidebar">
    <ul>
        <li>
            <a href="<?= ROOT ?>/ad_dashboard" class="<?= $currentPage == 'ad_dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/Users" class="<?= ($currentPage == 'Users' || $currentPage == 'viewtraveller' || $currentPage == 'viewprovider') ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/content" class="<?= $currentPage == 'content' ? 'active' : '' ?>">
                <i class="fas fa-blog"></i> Blogs
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/admin/destinations" class="<?= strpos($currentPage, 'destinations') !== false ? 'active' : '' ?>">
                <i class="fas fa-map-marked-alt"></i> Destinations
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/admin/accommodations" class="<?= strpos($currentPage, 'accommodations') !== false || strpos($_SERVER['REQUEST_URI'], 'accommodations') !== false ? 'active' : '' ?>">
                <i class="fas fa-hotel"></i> Accommodations
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/admin/transport" class="<?= strpos($currentPage, 'transport') !== false || strpos($_SERVER['REQUEST_URI'], 'transport') !== false ? 'active' : '' ?>">
                <i class="fas fa-car-side"></i> Transport
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/notifications" class="<?= $currentPage == 'notifications' ? 'active' : '' ?>">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/announcement" class="<?= $currentPage == 'announcement' ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i> Announcements
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/report" class="<?= $currentPage == 'report' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/ad_setting" class="<?= $currentPage == 'ad_setting' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
    </ul>
</aside>
