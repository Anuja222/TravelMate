<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - TravelMate</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/admin_header.view.php'; ?>

<?php
  // BUG-02: Null safety — guarantee all variables exist with safe defaults
  $stats = $stats ?? (object)[
      'totalUsers' => 0, 'activeUsers' => 0, 'totalAccommodations' => 0,
      'totalVehicles' => 0, 'totalBookings' => 0, 'totalDestinations' => 0,
      'totalBlogs' => 0, 'pendingBlogs' => 0,
      'userChange' => 0, 'accommodationChange' => 0,
      'vehicleChange' => 0, 'bookingChange' => 0,
      'usersLastMonth' => 0, 'accommodationsLastMonth' => 0,
      'vehiclesLastMonth' => 0, 'bookingsLastMonth' => 0
  ];
  $growth = $growth ?? (object)[
      'newToday' => 0, 'newThisWeek' => 0, 'newThisMonth' => 0,
      'newAccommodationsMonth' => 0, 'newVehiclesMonth' => 0, 'newListingsMonth' => 0
  ];
  $userDistribution = $userDistribution ?? [];
  $chartData = $chartData ?? [];
  $recentUsers = $recentUsers ?? [];
  $pendingApprovals = $pendingApprovals ?? (object)['total' => 0, 'blogs' => 0, 'accommodations' => 0, 'vehicles' => 0];
  $systemHealth = $systemHealth ?? (object)[
      'phpVersion' => 'N/A', 'serverTime' => 'N/A', 'timezone' => '',
      'serverSoftware' => 'N/A', 'dbVersion' => 'N/A', 'dbStatus' => 'Unknown',
      'diskFree' => 0, 'diskTotal' => 0, 'diskUsedPercent' => 0,
      'memoryUsage' => 0, 'memoryLimit' => 'N/A'
  ];
  $csrfToken = $csrfToken ?? '';
  $adminDisplayName = $adminDisplayName ?? 'Admin';
  $todayFormatted = $todayFormatted ?? date('l, F j, Y');
  $diskFreeFormatted = $diskFreeFormatted ?? '0 B';
  $diskTotalFormatted = $diskTotalFormatted ?? '0 B';
  $memoryUsageFormatted = $memoryUsageFormatted ?? '0 B';
?>

<div class="page-container">
  <?php include 'sidebar.view.php'; ?>

  <div class="content">
    <?php include __DIR__ . '/flash_messages.php'; ?>

    <!-- W-04: CSRF token available for any future AJAX/POST -->
    <input type="hidden" id="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <!-- Dashboard Header -->
    <div class="dashboard-header">
      <div class="page-title">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <!-- W-06: Admin name passed from controller, not from SessionHelper -->
        <p class="page-subtitle">Welcome back, <?= $adminDisplayName ?>. Here's what's happening today.</p>
      </div>
      <div class="header-actions">
        <!-- W-05: Date pre-formatted in controller -->
        <span class="header-date"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($todayFormatted) ?></span>
      </div>
    </div>

    <!-- Main Stats Grid — 6 cards -->
    <div class="dash-stats-grid">
      <!-- Total Users -->
      <div class="dash-stat-card border-blue">
        <div class="dash-stat-icon bg-blue"><i class="fas fa-users"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($stats->totalUsers ?? 0) ?></span>
          <span class="dash-stat-label">Total Users</span>
          <span class="dash-stat-change <?= ($stats->userChange ?? 0) >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-arrow-<?= ($stats->userChange ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
            <?= abs($stats->userChange ?? 0) ?>% <small>vs last month</small>
          </span>
        </div>
      </div>

      <!-- Active Users -->
      <div class="dash-stat-card border-green">
        <div class="dash-stat-icon bg-green"><i class="fas fa-user-check"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($stats->activeUsers ?? 0) ?></span>
          <span class="dash-stat-label">Active Users</span>
          <span class="dash-stat-change positive">
            <i class="fas fa-shield-alt"></i>
            <?php
              // BUG-01: Null coalescing on both operands to prevent division warnings
              $totalU = (int)($stats->totalUsers ?? 0);
              $activeU = (int)($stats->activeUsers ?? 0);
              $activePercent = ($totalU > 0) ? round(($activeU / $totalU) * 100, 1) : 0;
            ?>
            <?= $activePercent ?>% <small>of total</small>
          </span>
        </div>
      </div>

      <!-- New Users This Month -->
      <div class="dash-stat-card border-orange">
        <div class="dash-stat-icon bg-orange"><i class="fas fa-user-plus"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($growth->newThisMonth ?? 0) ?></span>
          <span class="dash-stat-label">New This Month</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-calendar-day"></i>
            <?= number_format($growth->newToday ?? 0) ?> today &middot; <?= number_format($growth->newThisWeek ?? 0) ?> this week
          </span>
        </div>
      </div>

      <!-- Accommodations -->
      <div class="dash-stat-card border-purple">
        <div class="dash-stat-icon bg-purple"><i class="fas fa-hotel"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($stats->totalAccommodations ?? 0) ?></span>
          <span class="dash-stat-label">Accommodations</span>
          <span class="dash-stat-change <?= ($stats->accommodationChange ?? 0) >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-arrow-<?= ($stats->accommodationChange ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
            <?= abs($stats->accommodationChange ?? 0) ?>% <small>vs last month</small>
          </span>
        </div>
      </div>

      <!-- Vehicles -->
      <div class="dash-stat-card border-teal">
        <div class="dash-stat-icon bg-teal"><i class="fas fa-car-side"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($stats->totalVehicles ?? 0) ?></span>
          <span class="dash-stat-label">Vehicles</span>
          <span class="dash-stat-change <?= ($stats->vehicleChange ?? 0) >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-arrow-<?= ($stats->vehicleChange ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
            <?= abs($stats->vehicleChange ?? 0) ?>% <small>vs last month</small>
          </span>
        </div>
      </div>

      <!-- Destinations -->
      <div class="dash-stat-card border-red">
        <div class="dash-stat-icon bg-red"><i class="fas fa-map-marked-alt"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($stats->totalDestinations ?? 0) ?></span>
          <span class="dash-stat-label">Destinations</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-globe-asia"></i>
            <?= number_format($stats->totalBlogs ?? 0) ?> blog posts
          </span>
        </div>
      </div>
    </div>

    <!-- Pending Approvals Alert -->
    <?php if (isset($pendingApprovals) && ($pendingApprovals->total ?? 0) > 0): ?>
    <div class="pending-alert-bar">
      <div class="pending-alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
      <div class="pending-alert-content">
        <strong><?= (int)$pendingApprovals->total ?> Pending Approval<?= $pendingApprovals->total > 1 ? 's' : '' ?></strong>
        <span>
          <?php $parts = []; ?>
          <?php if (($pendingApprovals->blogs ?? 0) > 0) $parts[] = (int)$pendingApprovals->blogs . ' blog' . ($pendingApprovals->blogs > 1 ? 's' : ''); ?>
          <?php if (($pendingApprovals->accommodations ?? 0) > 0) $parts[] = (int)$pendingApprovals->accommodations . ' accommodation' . ($pendingApprovals->accommodations > 1 ? 's' : ''); ?>
          <?php if (($pendingApprovals->vehicles ?? 0) > 0) $parts[] = (int)$pendingApprovals->vehicles . ' vehicle' . ($pendingApprovals->vehicles > 1 ? 's' : ''); ?>
          <?= implode(' &middot; ', $parts) ?>
        </span>
      </div>
      <div class="pending-alert-actions">
        <?php if (($pendingApprovals->blogs ?? 0) > 0): ?>
          <a href="<?= ROOT ?>/content" class="btn-alert-action">Review Blogs</a>
        <?php endif; ?>
        <?php if (($pendingApprovals->accommodations ?? 0) > 0): ?>
          <a href="<?= ROOT ?>/admin/accommodations" class="btn-alert-action">Review Accommodations</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Quick Action Cards -->
    <div class="quick-actions-section">
      <h3 class="section-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
      <div class="quick-actions-grid">
        <a href="<?= ROOT ?>/Users" class="quick-action-card">
          <div class="qa-icon bg-blue"><i class="fas fa-users-cog"></i></div>
          <div class="qa-body">
            <strong>Manage Users</strong>
            <small>View, edit & manage accounts</small>
          </div>
          <i class="fas fa-chevron-right qa-arrow"></i>
        </a>
        <a href="<?= ROOT ?>/content" class="quick-action-card">
          <div class="qa-icon bg-orange"><i class="fas fa-newspaper"></i></div>
          <div class="qa-body">
            <strong>Content Moderation</strong>
            <small>Review pending blog posts</small>
          </div>
          <i class="fas fa-chevron-right qa-arrow"></i>
        </a>
        <a href="<?= ROOT ?>/admin/accommodations" class="quick-action-card">
          <div class="qa-icon bg-purple"><i class="fas fa-building"></i></div>
          <div class="qa-body">
            <strong>Accommodations</strong>
            <small>Manage hotel listings</small>
          </div>
          <i class="fas fa-chevron-right qa-arrow"></i>
        </a>
        <a href="<?= ROOT ?>/admin/transport" class="quick-action-card">
          <div class="qa-icon bg-teal"><i class="fas fa-shuttle-van"></i></div>
          <div class="qa-body">
            <strong>Transport</strong>
            <small>Manage vehicle fleet</small>
          </div>
          <i class="fas fa-chevron-right qa-arrow"></i>
        </a>
      </div>
    </div>

    <!-- Charts Section — Two columns -->
    <div class="charts-row">
      <!-- Registration Trend (Bar Chart) -->
      <div class="chart-card">
        <div class="chart-card-header">
          <h3><i class="fas fa-chart-bar"></i> User Registration Trend</h3>
          <span class="chart-badge">Last 12 months</span>
        </div>
        <div class="chart-body">
          <?php
            $maxCount = 1;
            if (!empty($chartData)) {
              foreach ($chartData as $d) {
                if (($d->count ?? 0) > $maxCount) $maxCount = $d->count;
              }
            }
          ?>
          <div class="bar-chart">
            <?php if (!empty($chartData)): ?>
              <?php foreach ($chartData as $index => $d): ?>
                <?php $heightPercent = ($maxCount > 0) ? round((($d->count ?? 0) / $maxCount) * 100) : 0; ?>
                <div class="bar-column">
                  <div class="bar-tooltip"><?= (int)($d->count ?? 0) ?> users</div>
                  <div class="bar-fill" style="height: <?= max($heightPercent, 3) ?>%;" data-delay="<?= $index * 60 ?>"></div>
                  <span class="bar-label"><?= htmlspecialchars($d->month ?? '') ?></span>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="chart-empty"><i class="fas fa-chart-bar"></i><p>No data available</p></div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- User Distribution (Donut Chart) -->
      <div class="chart-card">
        <div class="chart-card-header">
          <h3><i class="fas fa-chart-pie"></i> User Distribution</h3>
          <span class="chart-badge"><?= number_format($stats->totalUsers ?? 0) ?> total</span>
        </div>
        <div class="chart-body donut-chart-body">
          <?php
            $totalForDonut = 0;
            $roleColors = [
              'traveller' => '#3498db',
              'accommodation' => '#9b59b6',
              'transport' => '#1abc9c',
              'admin' => '#e74c3c'
            ];
            $roleIcons = [
              'traveller' => 'fa-hiking',
              'accommodation' => 'fa-hotel',
              'transport' => 'fa-car-side',
              'admin' => 'fa-user-shield'
            ];
            $roleLabels = [
              'traveller' => 'Travellers',
              'accommodation' => 'Accommodation',
              'transport' => 'Transport',
              'admin' => 'Admins'
            ];
            if (!empty($userDistribution)) {
              foreach ($userDistribution as $ud) {
                $totalForDonut += ($ud->count ?? 0);
              }
            }
            // Build conic-gradient segments
            $gradientParts = [];
            $cumulative = 0;
            if ($totalForDonut > 0 && !empty($userDistribution)) {
              foreach ($userDistribution as $ud) {
                $percent = (($ud->count ?? 0) / $totalForDonut) * 100;
                $color = $roleColors[$ud->role ?? ''] ?? '#95a5a6';
                $gradientParts[] = "{$color} {$cumulative}% " . ($cumulative + $percent) . "%";
                $cumulative += $percent;
              }
            } else {
              $gradientParts[] = '#e0e0e0 0% 100%';
            }
            $gradient = implode(', ', $gradientParts);
          ?>
          <div class="donut-chart-container">
            <div class="donut-ring" style="background: conic-gradient(<?= $gradient ?>);">
              <div class="donut-hole">
                <span class="donut-total"><?= number_format($totalForDonut) ?></span>
                <span class="donut-total-label">Users</span>
              </div>
            </div>
            <div class="donut-legend">
              <?php if (!empty($userDistribution)): ?>
                <?php foreach ($userDistribution as $ud): ?>
                  <?php
                    $color = $roleColors[$ud->role ?? ''] ?? '#95a5a6';
                    $icon = $roleIcons[$ud->role ?? ''] ?? 'fa-user';
                    $label = $roleLabels[$ud->role ?? ''] ?? ucfirst($ud->role ?? 'Unknown');
                    $pct = ($totalForDonut > 0) ? round((($ud->count ?? 0) / $totalForDonut) * 100, 1) : 0;
                  ?>
                  <div class="legend-item">
                    <span class="legend-dot" style="background: <?= $color ?>;"></span>
                    <span class="legend-label"><i class="fas <?= $icon ?>"></i> <?= htmlspecialchars($label) ?></span>
                    <span class="legend-value"><?= number_format($ud->count ?? 0) ?></span>
                    <span class="legend-pct"><?= $pct ?>%</span>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="chart-empty"><p>No data available</p></div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Users Table — U-06: Unified class names with Users.view.php -->
    <div class="recent-users-section">
      <div class="section-header">
        <h3><i class="fas fa-clock"></i> Recent Registrations</h3>
        <a href="<?= ROOT ?>/Users" class="btn-view-all">View All <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="users-table-container">
        <table class="users-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentUsers)): ?>
              <?php foreach ($recentUsers as $user): ?>
                <?php
                  $fullName = htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                  $roleClass = strtolower($user->role ?? 'traveller');
                  $statusClass = strtolower($user->status ?? 'active');
                  $roleIcon = match($user->role ?? 'traveller') {
                    'traveller' => 'fa-hiking',
                    'accommodation' => 'fa-hotel',
                    'transport' => 'fa-car-side',
                    'admin' => 'fa-user-shield',
                    default => 'fa-user'
                  };
                  $roleDisplay = match($user->role ?? 'traveller') {
                    'traveller' => 'Traveller',
                    'accommodation' => 'Accommodation',
                    'transport' => 'Transport',
                    'admin' => 'Admin',
                    default => ucfirst($user->role ?? 'User')
                  };
                  $viewPage = ($user->role === 'traveller') ? 'viewtraveller' : 'viewprovider';
                ?>
                <tr>
                  <td>
                    <div class="user-info">
                      <div class="profile-pic">
                        <img src="<?= ROOT ?>/assets/images/default-avatar.png" alt="<?= $fullName ?>" onerror="this.src='<?= ROOT ?>/assets/images/default-avatar.png'">
                      </div>
                      <div class="user-details">
                        <strong><?= $fullName ?></strong>
                        <small>#<?= str_pad($user->id, 5, '0', STR_PAD_LEFT) ?></small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="email"><i class="fas fa-envelope"></i> <?= htmlspecialchars($user->email ?? '') ?></span>
                    </div>
                  </td>
                  <td>
                    <!-- U-06: Using user-type-badge (same as Users.view.php) -->
                    <span class="user-type-badge <?= $roleClass ?>">
                      <i class="fas <?= $roleIcon ?>"></i> <?= $roleDisplay ?>
                    </span>
                  </td>
                  <td>
                    <!-- W-05: Using pre-formatted dates from controller -->
                    <div class="date-info">
                      <span class="date"><?= htmlspecialchars($user->formattedDate ?? '') ?></span>
                      <small><?= htmlspecialchars($user->formattedTime ?? '') ?></small>
                    </div>
                  </td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= ucfirst($user->status ?? 'Active') ?></span></td>
                  <td>
                    <div class="action-buttons">
                      <!-- U-06: Using btn-action btn-view (same as Users.view.php) -->
                      <button class="btn-action btn-view" onclick="window.location.href='<?= ROOT ?>/<?= $viewPage ?>?id=<?= (int)$user->id ?>'" title="View Profile">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="table-empty-state">
                  <i class="fas fa-inbox"></i>
                  <p>No recent registrations</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- System Health Panel -->
    <div class="system-health-section">
      <h3 class="section-title"><i class="fas fa-server"></i> System Health</h3>
      <div class="system-health-grid">
        <!-- PHP Version -->
        <div class="health-card">
          <div class="health-icon"><i class="fab fa-php"></i></div>
          <div class="health-body">
            <span class="health-label">PHP Version</span>
            <span class="health-value"><?= htmlspecialchars($systemHealth->phpVersion ?? 'N/A') ?></span>
          </div>
        </div>

        <!-- MySQL Version -->
        <div class="health-card">
          <div class="health-icon"><i class="fas fa-database"></i></div>
          <div class="health-body">
            <span class="health-label">MySQL</span>
            <span class="health-value"><?= htmlspecialchars($systemHealth->dbVersion ?? 'N/A') ?></span>
            <span class="health-status <?= ($systemHealth->dbStatus ?? '') === 'Connected' ? 'ok' : 'error' ?>">
              <i class="fas fa-<?= ($systemHealth->dbStatus ?? '') === 'Connected' ? 'check-circle' : 'times-circle' ?>"></i>
              <?= htmlspecialchars($systemHealth->dbStatus ?? 'Unknown') ?>
            </span>
          </div>
        </div>

        <!-- Server -->
        <div class="health-card">
          <div class="health-icon"><i class="fas fa-globe"></i></div>
          <div class="health-body">
            <span class="health-label">Server</span>
            <span class="health-value"><?= htmlspecialchars($systemHealth->serverSoftware ?? 'N/A') ?></span>
          </div>
        </div>

        <!-- Server Time -->
        <div class="health-card">
          <div class="health-icon"><i class="fas fa-clock"></i></div>
          <div class="health-body">
            <span class="health-label">Server Time</span>
            <span class="health-value"><?= htmlspecialchars($systemHealth->serverTime ?? 'N/A') ?></span>
            <small class="health-sub"><?= htmlspecialchars($systemHealth->timezone ?? '') ?></small>
          </div>
        </div>

        <!-- Disk Usage — W-01: Using pre-formatted values from controller -->
        <div class="health-card">
          <div class="health-icon"><i class="fas fa-hdd"></i></div>
          <div class="health-body">
            <span class="health-label">Disk Usage</span>
            <div class="health-bar-container">
              <div class="health-bar">
                <div class="health-bar-fill <?= ($systemHealth->diskUsedPercent ?? 0) > 85 ? 'danger' : (($systemHealth->diskUsedPercent ?? 0) > 60 ? 'warning' : '') ?>" style="width: <?= (float)($systemHealth->diskUsedPercent ?? 0) ?>%;"></div>
              </div>
              <span class="health-bar-label">
                <?= htmlspecialchars($diskFreeFormatted) ?> free of <?= htmlspecialchars($diskTotalFormatted) ?>
                (<?= (float)($systemHealth->diskUsedPercent ?? 0) ?>% used)
              </span>
            </div>
          </div>
        </div>

        <!-- Memory — W-01: Using pre-formatted value from controller -->
        <div class="health-card">
          <div class="health-icon"><i class="fas fa-memory"></i></div>
          <div class="health-body">
            <span class="health-label">Memory Usage</span>
            <span class="health-value"><?= htmlspecialchars($memoryUsageFormatted) ?></span>
            <small class="health-sub">Limit: <?= htmlspecialchars($systemHealth->memoryLimit ?? 'N/A') ?></small>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
