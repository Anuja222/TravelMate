<?php
// initialize database connection and fetch all bookings
require_once __DIR__ . '/../../core/config.php';
require_once __DIR__ . '/../../core/Database.php';

class AdminBookingDB {
    use Database;
}

$db = new AdminBookingDB();

// fetch accommodation bookings joining users and accommodations manually
$accBookingsQuery = "
    SELECT b.*, u.first_name, u.last_name, u.email as user_email, 
           a.title as accommodation_name, a.property_type as accommodation_type, a.location as district
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    LEFT JOIN accommodations a ON b.accommodation_id = a.id
    ORDER BY b.booking_date DESC
";
$rawAccBookings = $db->query($accBookingsQuery, []) ?: [];

// fetch transport bookings joining users and vehicles manually
$transBookingsQuery = "
    SELECT tb.*, u.first_name, u.last_name, u.email as user_email,
           v.vehicle_model, v.vehicle_type, v.vehicle_number
    FROM transport_bookings tb
    LEFT JOIN users u ON tb.user_id = u.id
    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
    ORDER BY tb.booking_date DESC
";
$rawTransBookings = $db->query($transBookingsQuery, []) ?: [];

// helper functions for displaying status tags
function getStatusLabel($status) {
    $s = strtolower((string)$status);
    if ($s === 'confirmed') return 'Accepted';
    return ucfirst($s);
}

function normalizeStatusClass($status) {
    $s = strtolower((string)$status);
    if ($s === 'confirmed') return 'status-accepted';
    if ($s === 'pending') return 'status-pending';
    if ($s === 'rejected' || $s === 'cancelled') return 'status-rejected';
    if ($s === 'completed') return 'status-completed';
    return 'status-default';
}

function isAccommodationExpired($checkinDate) {
    if (!$checkinDate || is_numeric($checkinDate)) return false;
    $date = new DateTime($checkinDate);
    $today = new DateTime();
    $date->setTime(0, 0, 0, 0);
    $today->setTime(0, 0, 0, 0);
    return $date < $today;
}

function isTransportExpired($pickupDate) {
    if (!$pickupDate) return false;
    $date = new DateTime($pickupDate);
    $today = new DateTime();
    $date->setTime(0, 0, 0, 0);
    $today->setTime(0, 0, 0, 0);
    return $date < $today;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Bookings - TravelMate Admin</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .page-container { display: flex; }
    .content { padding: 0em 20px; flex: 1; }
    
    .page-title {
        margin-top: 2px;
        margin-bottom: 24px;
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .page-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .page-icon svg { width: 24px; height: 24px; }
    .page-title-text h1 { margin: 0; font-size: 24px; color: #2c3e50; }
    .page-title-text p { margin: 4px 0 0 0; color: #7f8c8d; font-size: 14px; }

    /* bookings layout */
    .bookings-wrapper {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .booking-section h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8eef4;
    }

    .bookings-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    th, td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid #edf2f7;
    }

    th {
        background: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: #f8fafc;
    }

    .sub-text {
        font-size: 12px;
        color: #718096;
        display: block;
        margin-top: 4px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-accepted, .status-completed { background: #d1fae5; color: #047857; }
    .status-pending { background: #fef3c7; color: #b45309; }
    .status-rejected { background: #fee2e2; color: #be123c; }
    .status-expired { background: #f3f4f6; color: #475569; }
    .status-default { background: #e2e8f0; color: #1e293b; }

    .tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        background: white;
        padding: 10px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .tab-btn {
        padding: 10px 20px;
        background: transparent;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        color: #718096;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .tab-btn.active {
        background: #1abc5b;
        color: white;
    }

    .tab-btn:hover:not(.active) {
        background: #f1f5f9;
        color: #2c3e50;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
  </style>
</head>
<body>

  <!-- ensure header exists -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <!-- include the sidebar we just updated -->
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>All Bookings Dashboard</h1>
            <p>Monitor all Accommodations and Transport Bookings platform-wide</p>
          </div>
        </div>
      </div>

      <div class="tabs">
          <button class="tab-btn active" onclick="switchTab('accommodations')"><i class="fas fa-hotel"></i> Accommodation Bookings</button>
          <button class="tab-btn" onclick="switchTab('transports')"><i class="fas fa-car"></i> Transport Bookings</button>
      </div>

      <div class="bookings-wrapper">
        
        <!-- accommodation tab -->
        <div id="tab-accommodations" class="tab-content active">
            <div class="booking-section">
                <h2>Accommodation Bookings</h2>
                <div class="bookings-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID & Date</th>
                            <th>Customer</th>
                            <th>Accommodation</th>
                            <th>Check-in/Out</th>
                            <th>Total (LKR)</th>
                            <th>Status (Lifecycle)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($rawAccBookings)): ?>
                            <tr><td colspan="6" style="text-align:center; padding:30px;">No accommodation bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach($rawAccBookings as $b): 
                                $expired = isAccommodationExpired($b->checkin_date);
                                $actualStatus = strtolower($b->booking_status);
                                $badgeClass = normalizeStatusClass($actualStatus);
                                $badgeLabel = getStatusLabel($actualStatus);

                                // if its expired, it overrides the visual presentation so admin knows it's a past travel.
                                if($expired && ($actualStatus === 'pending' || $actualStatus === 'confirmed')) {
                                    $badgeClass = 'status-expired';
                                    $badgeLabel = getStatusLabel($actualStatus) . ' (History/Exp)';
                                } else if ($expired) {
                                    $badgeLabel .= ' (History)';
                                }
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($b->booking_id ?? $b->id ?? '--') ?></strong>
                                    <span class="sub-text">Booked: <?= date('Y-m-d', strtotime($b->booking_date ?? $b->created_at ?? 'now')) ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars(($b->first_name ?? '') . ' ' . ($b->last_name ?? 'Traveller')) ?>
                                    <span class="sub-text"><?= htmlspecialchars($b->user_email ?? '--') ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($b->accommodation_name ?? 'Unknown Property') ?>
                                    <span class="sub-text"><?= htmlspecialchars(ucfirst($b->accommodation_type ?? '')) ?> - <?= htmlspecialchars($b->district ?? '') ?></span>
                                </td>
                                <td>
                                    <strong>In:</strong> <?= htmlspecialchars($b->checkin_date ?? '--') ?><br>
                                    <strong>Out:</strong> <?= htmlspecialchars($b->checkout_date ?? '--') ?>
                                </td>
                                <td>
                                    <strong><?= number_format($b->total_price ?? 0, 2) ?></strong>
                                    <span class="sub-text">Paid: <?= htmlspecialchars($b->payment_status ?? 'pending') ?></span>
                                </td>
                                <td>
                                    <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeLabel) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <!-- tRANSPORTS TAB -->
        <div id="tab-transports" class="tab-content">
            <div class="booking-section">
                <h2>Transport Bookings</h2>
                <div class="bookings-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID & Date</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Journey info</th>
                            <th>Total (LKR)</th>
                            <th>Status (Lifecycle)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($rawTransBookings)): ?>
                            <tr><td colspan="6" style="text-align:center; padding:30px;">No transport bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach($rawTransBookings as $tb): 
                                $expired = isTransportExpired($tb->pickup_date);
                                $actualStatus = strtolower($tb->booking_status);
                                $badgeClass = normalizeStatusClass($actualStatus);
                                $badgeLabel = getStatusLabel($actualStatus);

                                // if its expired, override visualization
                                if($expired && ($actualStatus === 'pending' || $actualStatus === 'confirmed')) {
                                    $badgeClass = 'status-expired';
                                    $badgeLabel = getStatusLabel($actualStatus) . ' (History/Exp)';
                                } else if ($expired) {
                                    $badgeLabel .= ' (History)';
                                }
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($tb->booking_id ?? $tb->id ?? '--') ?></strong>
                                    <span class="sub-text">Booked: <?= date('Y-m-d', strtotime($tb->booking_date ?? $tb->created_at ?? 'now')) ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars(($tb->first_name ?? '') . ' ' . ($tb->last_name ?? 'Traveller')) ?>
                                    <span class="sub-text"><?= htmlspecialchars($tb->user_email ?? '--') ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($tb->vehicle_model ?? 'Unknown Vehicle') ?>
                                    <span class="sub-text">Type: <?= htmlspecialchars(ucfirst($tb->vehicle_type ?? '')) ?></span>
                                </td>
                                <td>
                                    <strong>From:</strong> <?= htmlspecialchars($tb->pickup_location ?? '--') ?> <br>
                                    <strong>To:</strong> <?= htmlspecialchars($tb->dropoff_location ?? '--') ?> <br>
                                    <span class="sub-text">Pickup: <?= htmlspecialchars($tb->pickup_date ?? '--') ?> at <?= htmlspecialchars($tb->pickup_time ?? '--') ?></span>
                                </td>
                                <td>
                                    <strong><?= number_format($tb->total_price ?? 0, 2) ?></strong>
                                    <span class="sub-text">Paid: <?= htmlspecialchars($tb->payment_status ?? 'pending') ?></span>
                                </td>
                                <td>
                                    <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeLabel) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        document.querySelector(`.tab-btn[onclick="switchTab('${tabId}')"]`).classList.add('active');
        document.getElementById(`tab-${tabId}`).classList.add('active');
    }
  </script>

</body>
</html>
