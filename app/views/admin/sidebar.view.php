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
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <span>Destination</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="ViewActivities">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <polygon points="10 8 16 12 10 16 10 8"></polygon>
        </svg>
        <span>Activities</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="accommodations">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <span>Accommodation</span>
      </a>
    </li>
    
    <li class="menu-item">
      <a href="transports">
        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="1" y="3" width="15" height="13"></rect>
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
          <circle cx="5.5" cy="18.5" r="2.5"></circle>
          <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
        <span>Transport</span>
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