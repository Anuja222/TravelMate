<aside class="sidebar">
  <ul class="nav-top">
    <li>
      <a href="tr_dashboard" class="<?php echo (isset($active_page) && $active_page === 'dashboard') ? 'active' : ''; ?>">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="bookingnew" class="<?php echo (isset($active_page) && $active_page === 'bookings') ? 'active' : ''; ?>">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span>Bookings</span>
      </a>
    </li>
    <li>
      <a href="tr_revenue" class="<?php echo (isset($active_page) && $active_page === 'revenue') ? 'active' : ''; ?>">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="20" x2="18" y2="10"></line>
          <line x1="12" y1="20" x2="12" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="14"></line>
        </svg>
        <span>Revenue</span>
      </a>
    </li>
  </ul>
  <ul class="nav-bottom">
    <li>
      <a href="tr_setting" class="<?php echo (isset($active_page) && $active_page === 'settings') ? 'active' : ''; ?>">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="3"></circle>
          <path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m0 6l-4.24 4.24M23 12h-6m-6 0H1m18.66 5.66l-4.24-4.24m0-6l-4.24-4.24"></path>
        </svg>
        <span>Settings</span>
      </a>
    </li>
    <li>
      <a href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/logout.php" style="color: #e74c3c;">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="stroke: #e74c3c;">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside>