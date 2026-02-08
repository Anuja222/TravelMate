  <aside class="sidebar">
  <link rel="stylesheet" href="common.css">
  
  <ul class="sidebar-menu">
    <!-- Main Section -->
    <li class="menu-item">
      <a href="ad_dashboard">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        <span>Dashboard</span>
      </a>
    </li>
    
    <li class="menu-separator"></li>
    
    <!-- Management Section -->
    <li class="menu-item">
      <a href="Users">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Users</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="content">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
        </svg>
        <span>Blogs</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="ViewListing">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"></line>
          <line x1="8" y1="12" x2="21" y2="12"></line>
          <line x1="8" y1="18" x2="21" y2="18"></line>
          <line x1="3" y1="6" x2="3.01" y2="6"></line>
          <line x1="3" y1="12" x2="3.01" y2="12"></line>
          <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        <span>Listing</span>
      </a>
    </li>
    
    <li class="menu-separator"></li>
    
    <!-- Communication Section -->
    <li class="menu-item">
      <a href="notifications">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span>Notifications</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="announcement">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
        </svg>
        <span>Announcements</span>
      </a>
    </li>
    
    <li class="menu-separator"></li>
    
    <!-- System Section -->
    <li class="menu-item">
      <a href="report">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="20" x2="18" y2="10"></line>
          <line x1="12" y1="20" x2="12" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="14"></line>
        </svg>
        <span>Reports</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="ad_setting">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="3"></circle>
          <path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m0 6l-4.24 4.24M23 12h-6m-6 0H1m18.66 5.66l-4.24-4.24m0-6l-4.24-4.24"></path>
        </svg>
        <span>Settings</span>
      </a>
    </li>
  </ul>
  
  <script>
    // Auto-highlight active menu item based on current URL
    document.addEventListener('DOMContentLoaded', function() {
      const currentPath = window.location.pathname;
      const menuLinks = document.querySelectorAll('.menu-item a');
      
      menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPath.includes(href)) {
          link.classList.add('active');
        }
      });
    });
  </script>
</aside>