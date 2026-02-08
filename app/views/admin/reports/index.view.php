<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports & Analytics - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/reports.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../admin_header.view.php'; ?>

<?php
  // Null-safe defaults
  $overview       = $overview       ?? (object)['total_users'=>0,'active_users'=>0,'new_users'=>0,'active_accommodations'=>0,'active_vehicles'=>0,'total_listings'=>0,'total_destinations'=>0,'total_bookings'=>0,'confirmed_bookings'=>0,'completed_bookings'=>0,'cancelled_bookings'=>0,'cancellation_rate'=>0,'total_revenue'=>0,'platform_commission'=>0,'provider_earnings'=>0,'avg_booking_value'=>0,'total_reviews'=>0,'avg_rating'=>0];
  $userStats      = $userStats      ?? (object)['registration_trend'=>[],'role_distribution'=>[],'growth_percent'=>0,'current_period_users'=>0,'previous_period_users'=>0];
  $listingStats   = $listingStats   ?? (object)['accommodations_by_type'=>[],'vehicles_by_type'=>[],'new_accommodations'=>0,'new_vehicles'=>0];
  $bookingStats   = $bookingStats   ?? (object)['by_status'=>[],'daily_trend'=>[],'by_payment'=>[]];
  $revenueStats   = $revenueStats   ?? (object)['daily_revenue'=>[],'current_revenue'=>0,'previous_revenue'=>0,'growth_percent'=>0];
  $popularCities  = $popularCities  ?? [];
  $recentBookings = $recentBookings ?? [];
  $filters        = $filters        ?? ['start_date' => date('Y-m-d', strtotime('-90 days')), 'end_date' => date('Y-m-d')];
?>

<div class="page-container">
  <?php include __DIR__ . '/../sidebar.view.php'; ?>

  <div class="content">
    <?php include __DIR__ . '/../flash_messages.php'; ?>

    <!-- ═══════════ PAGE HEADER ═══════════ -->
    <div class="dashboard-header">
      <div class="page-title">
        <h1><i class="fas fa-chart-line"></i> Reports & Analytics</h1>
        <p class="page-subtitle">Comprehensive performance insights from your database</p>
      </div>
      <div class="header-actions">
        <button class="btn-action" onclick="location.reload()" title="Refresh">
          <i class="fas fa-sync-alt"></i> Refresh
        </button>
      </div>
    </div>

    <!-- ═══════════ DATE FILTER ═══════════ -->
    <div class="rpt-filter-panel">
      <form method="GET" action="<?= ROOT ?>/report" class="rpt-filter-form">
        <div class="rpt-filter-group">
          <label><i class="fas fa-calendar-alt"></i> From</label>
          <input type="date" name="start_date" value="<?= htmlspecialchars($filters['start_date']) ?>">
        </div>
        <div class="rpt-filter-group">
          <label><i class="fas fa-calendar-alt"></i> To</label>
          <input type="date" name="end_date" value="<?= htmlspecialchars($filters['end_date']) ?>">
        </div>
        <button type="submit" class="rpt-btn-primary"><i class="fas fa-filter"></i> Apply</button>
        <a href="<?= ROOT ?>/report" class="rpt-btn-secondary"><i class="fas fa-undo"></i> Reset</a>
      </form>
      <div class="rpt-filter-info">
        <i class="fas fa-info-circle"></i>
        Showing data from <strong><?= date('M d, Y', strtotime($filters['start_date'])) ?></strong> to <strong><?= date('M d, Y', strtotime($filters['end_date'])) ?></strong>
      </div>
    </div>

    <!-- ═══════════ KEY METRICS CARDS (reusing dashboard card style) ═══════════ -->
    <div class="dash-stats-grid">
      <!-- Total Users -->
      <div class="dash-stat-card border-blue">
        <div class="dash-stat-icon bg-blue"><i class="fas fa-users"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($overview->total_users) ?></span>
          <span class="dash-stat-label">Total Users</span>
          <span class="dash-stat-change <?= ($userStats->growth_percent >= 0) ? 'positive' : 'negative' ?>">
            <i class="fas fa-user-plus"></i>
            <?= number_format($overview->new_users) ?> <small>new in period</small>
          </span>
        </div>
      </div>

      <!-- Active Listings -->
      <div class="dash-stat-card border-purple">
        <div class="dash-stat-icon bg-purple"><i class="fas fa-list"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($overview->total_listings) ?></span>
          <span class="dash-stat-label">Active Listings</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-hotel"></i> <?= number_format($overview->active_accommodations) ?> &middot;
            <i class="fas fa-car"></i> <?= number_format($overview->active_vehicles) ?>
          </span>
        </div>
      </div>

      <!-- Total Bookings -->
      <div class="dash-stat-card border-green">
        <div class="dash-stat-icon bg-green"><i class="fas fa-calendar-check"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= number_format($overview->total_bookings) ?></span>
          <span class="dash-stat-label">Total Bookings</span>
          <span class="dash-stat-change <?= ($overview->cancellation_rate > 20) ? 'negative' : 'positive' ?>">
            <i class="fas fa-<?= ($overview->cancellation_rate > 20) ? 'exclamation-triangle' : 'check-circle' ?>"></i>
            <?= $overview->cancellation_rate ?>% <small>cancellation</small>
          </span>
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="dash-stat-card border-orange">
        <div class="dash-stat-icon bg-orange"><i class="fas fa-money-bill-wave"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value">LKR <?= number_format($overview->total_revenue, 2) ?></span>
          <span class="dash-stat-label">Total Revenue</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-chart-line"></i>
            Avg: LKR <?= number_format($overview->avg_booking_value, 2) ?>
          </span>
        </div>
      </div>

      <!-- Platform Commission -->
      <div class="dash-stat-card border-teal">
        <div class="dash-stat-icon bg-teal"><i class="fas fa-percentage"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value">LKR <?= number_format($overview->platform_commission, 2) ?></span>
          <span class="dash-stat-label">Platform Commission</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-coins"></i> 10% <small>of revenue</small>
          </span>
        </div>
      </div>

      <!-- Average Rating -->
      <div class="dash-stat-card border-red">
        <div class="dash-stat-icon bg-red"><i class="fas fa-star"></i></div>
        <div class="dash-stat-body">
          <span class="dash-stat-value"><?= $overview->avg_rating ?> <small style="font-size:16px;">/ 5</small></span>
          <span class="dash-stat-label">Average Rating</span>
          <span class="dash-stat-change neutral">
            <i class="fas fa-comment-dots"></i> <?= number_format($overview->total_reviews) ?> <small>reviews</small>
          </span>
        </div>
      </div>
    </div>

    <!-- ═══════════ TAB NAVIGATION ═══════════ -->
    <div class="rpt-tabs">
      <button class="rpt-tab active" data-tab="overview" onclick="switchTab('overview', this)">
        <i class="fas fa-th-large"></i> Overview
      </button>
      <button class="rpt-tab" data-tab="users" onclick="switchTab('users', this)">
        <i class="fas fa-users"></i> Users
      </button>
      <button class="rpt-tab" data-tab="listings" onclick="switchTab('listings', this)">
        <i class="fas fa-building"></i> Listings
      </button>
      <button class="rpt-tab" data-tab="bookings" onclick="switchTab('bookings', this)">
        <i class="fas fa-calendar-check"></i> Bookings
      </button>
      <button class="rpt-tab" data-tab="revenue" onclick="switchTab('revenue', this)">
        <i class="fas fa-dollar-sign"></i> Revenue
      </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: OVERVIEW
         ══════════════════════════════════════════════════════════════════ -->
    <div id="tab-overview" class="rpt-tab-content active">

      <!-- Popular Cities -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-map-marked-alt"></i> Popular Cities</h3>
          <span class="rpt-section-badge"><?= count($popularCities) ?> cities</span>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>#</th>
                <th>City</th>
                <th>District</th>
                <th>Listings</th>
                <th>Total Views</th>
                <th>Avg Price/Night</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($popularCities)): ?>
                <?php foreach ($popularCities as $i => $city): ?>
                <tr>
                  <td><span class="rpt-rank rpt-rank-<?= $i + 1 ?>"><?= $i + 1 ?></span></td>
                  <td><strong><?= htmlspecialchars($city->city ?? 'N/A') ?></strong></td>
                  <td><?= htmlspecialchars($city->district ?? '—') ?></td>
                  <td><span class="rpt-badge blue"><?= number_format($city->listing_count) ?></span></td>
                  <td><?= number_format($city->total_views) ?></td>
                  <td><strong>LKR <?= number_format($city->avg_price, 2) ?></strong></td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="rpt-empty"><i class="fas fa-inbox"></i> No city data available yet</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Bookings -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-history"></i> Recent Bookings</h3>
          <span class="rpt-section-badge"><?= count($recentBookings) ?> latest</span>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Nights</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentBookings)): ?>
                <?php foreach ($recentBookings as $b): ?>
                <tr>
                  <td><code class="rpt-code"><?= htmlspecialchars($b->booking_code) ?></code></td>
                  <td>
                    <div class="rpt-user-cell">
                      <strong><?= htmlspecialchars($b->user_name ?? 'Unknown') ?></strong>
                      <small><?= htmlspecialchars($b->user_email ?? '') ?></small>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($b->room_name) ?></td>
                  <td><?= date('M d, Y', strtotime($b->checkin_date)) ?></td>
                  <td><?= (int)$b->nights ?></td>
                  <td><strong>LKR <?= number_format($b->total_price, 2) ?></strong></td>
                  <td><span class="rpt-status rpt-status-<?= strtolower($b->booking_status) ?>"><?= ucfirst($b->booking_status) ?></span></td>
                  <td><span class="rpt-status rpt-pay-<?= strtolower($b->payment_status) ?>"><?= ucfirst($b->payment_status) ?></span></td>
                  <td><small><?= $b->formatted_date ?></small></td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="9" class="rpt-empty"><i class="fas fa-inbox"></i> No bookings found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: USERS
         ══════════════════════════════════════════════════════════════════ -->
    <div id="tab-users" class="rpt-tab-content">

      <!-- User Growth Summary -->
      <div class="rpt-summary-row">
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon blue"><i class="fas fa-user-plus"></i></div>
          <div>
            <span class="rpt-summary-value"><?= number_format($userStats->current_period_users) ?></span>
            <span class="rpt-summary-label">New Users (Current Period)</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon gray"><i class="fas fa-user-clock"></i></div>
          <div>
            <span class="rpt-summary-value"><?= number_format($userStats->previous_period_users) ?></span>
            <span class="rpt-summary-label">New Users (Previous Period)</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon <?= ($userStats->growth_percent >= 0) ? 'green' : 'red' ?>">
            <i class="fas fa-<?= ($userStats->growth_percent >= 0) ? 'arrow-up' : 'arrow-down' ?>"></i>
          </div>
          <div>
            <span class="rpt-summary-value"><?= $userStats->growth_percent ?>%</span>
            <span class="rpt-summary-label">Growth Rate</span>
          </div>
        </div>
      </div>

      <!-- User Distribution by Role -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-users-cog"></i> User Distribution by Role</h3>
        </div>
        <div class="rpt-role-cards">
          <?php if (!empty($userStats->role_distribution)): ?>
            <?php foreach ($userStats->role_distribution as $role):
              $roleIcon = match(strtolower($role->role)) {
                'traveller'     => 'fa-hiking',
                'accommodation' => 'fa-hotel',
                'transport'     => 'fa-car-side',
                'admin'         => 'fa-user-shield',
                default         => 'fa-user'
              };
              $roleColor = match(strtolower($role->role)) {
                'traveller'     => 'blue',
                'accommodation' => 'purple',
                'transport'     => 'teal',
                'admin'         => 'red',
                default         => 'gray'
              };
            ?>
            <div class="rpt-role-card rpt-role-<?= $roleColor ?>">
              <div class="rpt-role-icon"><i class="fas <?= $roleIcon ?>"></i></div>
              <div class="rpt-role-body">
                <span class="rpt-role-name"><?= ucfirst(htmlspecialchars($role->role)) ?>s</span>
                <span class="rpt-role-count"><?= number_format($role->count) ?></span>
                <div class="rpt-role-bar">
                  <div class="rpt-role-bar-fill" style="width: <?= $role->percentage ?>%"></div>
                </div>
                <span class="rpt-role-meta"><?= number_format($role->active_count) ?> active &middot; <?= $role->percentage ?>%</span>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="rpt-empty-inline">No user data</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Registration Trend -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-chart-line"></i> Registration Trend</h3>
          <span class="rpt-section-badge"><?= count($userStats->registration_trend) ?> days</span>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>New Users</th>
                <th>Visual</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($userStats->registration_trend)):
                $maxReg = max(array_map(fn($r) => $r->count, $userStats->registration_trend));
              ?>
                <?php foreach ($userStats->registration_trend as $day): ?>
                <tr>
                  <td><?= htmlspecialchars($day->formatted_date) ?></td>
                  <td><strong><?= number_format($day->count) ?></strong></td>
                  <td>
                    <div class="rpt-bar">
                      <div class="rpt-bar-fill blue" style="width:<?= $maxReg > 0 ? round(($day->count / $maxReg) * 100) : 0 ?>%"></div>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="3" class="rpt-empty"><i class="fas fa-inbox"></i> No registrations in this period</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: LISTINGS
         ══════════════════════════════════════════════════════════════════ -->
    <div id="tab-listings" class="rpt-tab-content">

      <!-- New Listings Summary -->
      <div class="rpt-summary-row">
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon purple"><i class="fas fa-hotel"></i></div>
          <div>
            <span class="rpt-summary-value"><?= number_format($listingStats->new_accommodations) ?></span>
            <span class="rpt-summary-label">New Accommodations (Period)</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon teal"><i class="fas fa-car-side"></i></div>
          <div>
            <span class="rpt-summary-value"><?= number_format($listingStats->new_vehicles) ?></span>
            <span class="rpt-summary-label">New Vehicles (Period)</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon orange"><i class="fas fa-map-pin"></i></div>
          <div>
            <span class="rpt-summary-value"><?= number_format($overview->total_destinations) ?></span>
            <span class="rpt-summary-label">Active Destinations</span>
          </div>
        </div>
      </div>

      <!-- Accommodations by Type -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-hotel"></i> Accommodations by Type</h3>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Total</th>
                <th>Active</th>
                <th>Views</th>
                <th>Avg Price/Night</th>
                <th>Fill Rate</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($listingStats->accommodations_by_type)): ?>
                <?php foreach ($listingStats->accommodations_by_type as $t): ?>
                <tr>
                  <td><strong><?= ucfirst(htmlspecialchars($t->type)) ?></strong></td>
                  <td><?= number_format($t->total_count) ?></td>
                  <td><span class="rpt-badge green"><?= number_format($t->active_count) ?></span></td>
                  <td><?= number_format($t->total_views) ?></td>
                  <td><strong>LKR <?= number_format($t->avg_price, 2) ?></strong></td>
                  <td>
                    <div class="rpt-bar">
                      <div class="rpt-bar-fill purple" style="width:<?= $t->total_count > 0 ? round(($t->active_count / $t->total_count) * 100) : 0 ?>%"></div>
                    </div>
                    <small><?= $t->total_count > 0 ? round(($t->active_count / $t->total_count) * 100) : 0 ?>%</small>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="rpt-empty"><i class="fas fa-inbox"></i> No accommodations</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vehicles by Type -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-car"></i> Vehicles by Type</h3>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Total</th>
                <th>Active</th>
                <th>Views</th>
                <th>Avg Price/Day</th>
                <th>Availability</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($listingStats->vehicles_by_type)): ?>
                <?php foreach ($listingStats->vehicles_by_type as $t): ?>
                <tr>
                  <td><strong><?= ucfirst(htmlspecialchars($t->vehicle_type)) ?></strong></td>
                  <td><?= number_format($t->total_count) ?></td>
                  <td><span class="rpt-badge green"><?= number_format($t->active_count) ?></span></td>
                  <td><?= number_format($t->total_views) ?></td>
                  <td><strong>LKR <?= number_format($t->avg_price, 2) ?></strong></td>
                  <td>
                    <div class="rpt-bar">
                      <div class="rpt-bar-fill teal" style="width:<?= $t->total_count > 0 ? round(($t->active_count / $t->total_count) * 100) : 0 ?>%"></div>
                    </div>
                    <small><?= $t->total_count > 0 ? round(($t->active_count / $t->total_count) * 100) : 0 ?>%</small>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="rpt-empty"><i class="fas fa-inbox"></i> No vehicles</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: BOOKINGS
         ══════════════════════════════════════════════════════════════════ -->
    <div id="tab-bookings" class="rpt-tab-content">

      <!-- Booking Status Summary -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-clipboard-list"></i> Bookings by Status</h3>
        </div>
        <?php if (!empty($bookingStats->by_status)): ?>
        <div class="rpt-status-cards">
          <?php foreach ($bookingStats->by_status as $st):
            $statusColor = match(strtolower($st->status)) {
              'confirmed' => 'blue',
              'completed' => 'green',
              'pending'   => 'orange',
              'cancelled' => 'red',
              default     => 'gray'
            };
            $statusIcon = match(strtolower($st->status)) {
              'confirmed' => 'fa-check-circle',
              'completed' => 'fa-flag-checkered',
              'pending'   => 'fa-clock',
              'cancelled' => 'fa-times-circle',
              default     => 'fa-circle'
            };
          ?>
          <div class="rpt-status-card rpt-sc-<?= $statusColor ?>">
            <div class="rpt-sc-top">
              <i class="fas <?= $statusIcon ?>"></i>
              <span class="rpt-sc-label"><?= ucfirst($st->status) ?></span>
            </div>
            <span class="rpt-sc-count"><?= number_format($st->count) ?></span>
            <span class="rpt-sc-pct"><?= $st->percentage ?>%</span>
            <div class="rpt-sc-bar">
              <div class="rpt-sc-bar-fill" style="width:<?= $st->percentage ?>%"></div>
            </div>
            <span class="rpt-sc-revenue">LKR <?= number_format($st->revenue, 2) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
          <p class="rpt-empty-inline"><i class="fas fa-inbox"></i> No bookings in this period</p>
        <?php endif; ?>
      </div>

      <!-- Daily Booking Trend -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-chart-bar"></i> Daily Booking Trend</h3>
          <span class="rpt-section-badge"><?= count($bookingStats->daily_trend) ?> days</span>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Bookings</th>
                <th>Revenue</th>
                <th>Avg Value</th>
                <th>Visual</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($bookingStats->daily_trend)):
                $maxBookings = max(array_map(fn($d) => $d->count, $bookingStats->daily_trend));
              ?>
                <?php foreach ($bookingStats->daily_trend as $day): ?>
                <tr>
                  <td><?= htmlspecialchars($day->formatted_date) ?></td>
                  <td><strong><?= number_format($day->count) ?></strong></td>
                  <td><strong>LKR <?= number_format($day->revenue, 2) ?></strong></td>
                  <td>LKR <?= $day->count > 0 ? number_format($day->revenue / $day->count, 2) : '0.00' ?></td>
                  <td>
                    <div class="rpt-bar">
                      <div class="rpt-bar-fill green" style="width:<?= $maxBookings > 0 ? round(($day->count / $maxBookings) * 100) : 0 ?>%"></div>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="rpt-empty"><i class="fas fa-inbox"></i> No bookings in this period</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Payment Status Breakdown -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-credit-card"></i> Payment Status Breakdown</h3>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Payment Status</th>
                <th>Count</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($bookingStats->by_payment)): ?>
                <?php foreach ($bookingStats->by_payment as $pm): ?>
                <tr>
                  <td><span class="rpt-status rpt-pay-<?= strtolower($pm->payment_status) ?>"><?= ucfirst($pm->payment_status) ?></span></td>
                  <td><strong><?= number_format($pm->count) ?></strong></td>
                  <td><strong>LKR <?= number_format($pm->revenue, 2) ?></strong></td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="3" class="rpt-empty"><i class="fas fa-inbox"></i> No payment data</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: REVENUE
         ══════════════════════════════════════════════════════════════════ -->
    <div id="tab-revenue" class="rpt-tab-content">

      <!-- Revenue Growth Comparison -->
      <div class="rpt-summary-row">
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon green"><i class="fas fa-money-bill-wave"></i></div>
          <div>
            <span class="rpt-summary-value">LKR <?= number_format($revenueStats->current_revenue, 2) ?></span>
            <span class="rpt-summary-label">Current Period Revenue</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon gray"><i class="fas fa-history"></i></div>
          <div>
            <span class="rpt-summary-value">LKR <?= number_format($revenueStats->previous_revenue, 2) ?></span>
            <span class="rpt-summary-label">Previous Period Revenue</span>
          </div>
        </div>
        <div class="rpt-summary-card">
          <div class="rpt-summary-icon <?= ($revenueStats->growth_percent >= 0) ? 'green' : 'red' ?>">
            <i class="fas fa-<?= ($revenueStats->growth_percent >= 0) ? 'arrow-up' : 'arrow-down' ?>"></i>
          </div>
          <div>
            <span class="rpt-summary-value"><?= $revenueStats->growth_percent ?>%</span>
            <span class="rpt-summary-label">Revenue Growth</span>
          </div>
        </div>
      </div>

      <!-- Daily Revenue Breakdown -->
      <div class="rpt-section">
        <div class="rpt-section-header">
          <h3><i class="fas fa-chart-area"></i> Daily Revenue Breakdown</h3>
          <span class="rpt-section-badge"><?= count($revenueStats->daily_revenue) ?> days</span>
        </div>
        <div class="rpt-table-wrap">
          <table class="rpt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Total Revenue</th>
                <th>Commission (10%)</th>
                <th>Provider Earnings</th>
                <th>Bookings</th>
                <th>Visual</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($revenueStats->daily_revenue)):
                $maxRev = max(array_map(fn($r) => (float)$r->total_revenue, $revenueStats->daily_revenue));
              ?>
                <?php foreach ($revenueStats->daily_revenue as $day): ?>
                <tr>
                  <td><?= htmlspecialchars($day->formatted_date) ?></td>
                  <td><strong>LKR <?= number_format($day->total_revenue, 2) ?></strong></td>
                  <td><span class="rpt-badge orange">LKR <?= number_format($day->commission, 2) ?></span></td>
                  <td><span class="rpt-badge green">LKR <?= number_format($day->provider_earnings, 2) ?></span></td>
                  <td><?= number_format($day->booking_count) ?></td>
                  <td>
                    <div class="rpt-bar">
                      <div class="rpt-bar-fill orange" style="width:<?= $maxRev > 0 ? round(((float)$day->total_revenue / $maxRev) * 100) : 0 ?>%"></div>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="rpt-empty"><i class="fas fa-inbox"></i> No revenue data for this period</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Revenue Summary Box -->
      <div class="rpt-revenue-summary">
        <div class="rpt-rev-item">
          <span class="rpt-rev-label">Total Revenue (Period)</span>
          <span class="rpt-rev-value">LKR <?= number_format($overview->total_revenue, 2) ?></span>
        </div>
        <div class="rpt-rev-divider"></div>
        <div class="rpt-rev-item">
          <span class="rpt-rev-label">Platform Commission (10%)</span>
          <span class="rpt-rev-value orange">LKR <?= number_format($overview->platform_commission, 2) ?></span>
        </div>
        <div class="rpt-rev-divider"></div>
        <div class="rpt-rev-item">
          <span class="rpt-rev-label">Provider Earnings (90%)</span>
          <span class="rpt-rev-value green">LKR <?= number_format($overview->provider_earnings, 2) ?></span>
        </div>
        <div class="rpt-rev-divider"></div>
        <div class="rpt-rev-item">
          <span class="rpt-rev-label">Avg Booking Value</span>
          <span class="rpt-rev-value">LKR <?= number_format($overview->avg_booking_value, 2) ?></span>
        </div>
      </div>
    </div>

  </div><!-- /.content -->
</div><!-- /.page-container -->

<script>
function switchTab(tabName, btn) {
  document.querySelectorAll('.rpt-tab-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.rpt-tab').forEach(el => el.classList.remove('active'));
  document.getElementById('tab-' + tabName).classList.add('active');
  btn.classList.add('active');
}
</script>

</body>
</html>
