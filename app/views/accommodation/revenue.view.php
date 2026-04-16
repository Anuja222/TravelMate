<?php
// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Revenue</title>
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/dashboard.css">
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/setting.css">
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/revenue.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- mAIN CONTENT -->
  <main>
    <!-- sIDEBAR -->
    <?php 
    $active_page = 'revenue';
    include __DIR__ . '/sidebar.view.php'; 
    ?>

    <div class="content dashboard-content">
        <!-- hidden PDF Header (Only visible in PDF) -->
        <div id="pdfHeader" style="display: none; padding: 20px; border-bottom: 2px solid #1abc5b; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="<?= ROOT ?? '' ?>/assets/images/logo.jpg" onerror="this.src='assets/images/logo.jpg'" alt="TravelMate Logo" style="height: 50px; object-fit: contain;">
                    <div>
                        <h2 style="margin: 0; color: #0f172a; font-size: 26px; font-weight: 800;">TravelMate</h2>
                        <p style="margin: 3px 0 0 0; color: #1abc5b; font-size: 14px; font-weight: 600;">Your Trusted Travel Partner</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h3 style="margin: 0; color: #0f172a; font-size: 18px;">Revenue Report</h3>
                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;"><strong>Date:</strong> <?php echo date('F j, Y'); ?></p>
                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;"><strong>Provider:</strong> <?php echo htmlspecialchars($_SESSION['USER']->name ?? $_SESSION['user_name'] ?? 'Accommodation Provider'); ?></p>
                </div>
            </div>

            <!-- activity Summary Section (For PDF) -->
            <div style="margin-top: 30px; padding: 25px; background: #f0fdf4; border-radius: 12px; text-align: center;">
                <h3 style="margin: 0 0 20px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Activity Summary (<?php echo htmlspecialchars($period_label ?? 'Current Period'); ?>)</h3>
                <div style="display: flex; justify-content: space-around; flex-wrap: wrap;">
                    <div style="text-align: center; padding: 10px;">
                        <div style="font-size: 36px; font-weight: 800; color: #1abc5b; margin-bottom: 5px;"><?php echo count($properties ?? []); ?></div>
                        <div style="color: #475569; font-size: 15px; font-weight: 500;">Active Listings</div>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <div style="font-size: 36px; font-weight: 800; color: #1abc5b; margin-bottom: 5px;"><?php 
                            $properties_booked = 0;
                            if(!empty($properties)) {
                                foreach($properties as $p) {
                                    if(isset($p['property_bookings']) && $p['property_bookings'] > 0) $properties_booked++;
                                }
                            }
                            echo $properties_booked;
                        ?></div>
                        <div style="color: #475569; font-size: 15px; font-weight: 500;">Listings Booked</div>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <div style="font-size: 36px; font-weight: 800; color: #1abc5b; margin-bottom: 5px;"><?php echo number_format($total_bookings ?? 0); ?></div>
                        <div style="color: #475569; font-size: 15px; font-weight: 500;">Bookings Received</div>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <div style="font-size: 36px; font-weight: 800; color: #1abc5b; margin-bottom: 5px;">N/A</div>
                        <div style="color: #475569; font-size: 15px; font-weight: 500;">Overall Rating</div>
                        <div style="color: #94a3b8; font-size: 12px; margin-top: 4px;">For selected period</div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px; padding: 12px 15px; background: #f8fafc; border-radius: 6px; border-left: 4px solid #1abc5b;">
                <p style="margin: 0; color: #334155; font-size: 14px; line-height: 1.5;">
                    <strong>Dashboard Activity Summary:</strong> Official documented revenue, booking activity, and statistics for your verified accommodations on the TravelMate platform for the period of <strong><?php echo htmlspecialchars($period_label ?? 'Current Period'); ?></strong>.
                </p>
            </div>
        </div>

        <div class="page-header">
          <h1><i class="fas fa-chart-line"></i> Revenue Analytics</h1>
          <p>Track your earnings and business performance over time</p>
        </div>

        <div class="filter-container" style="display: flex; justify-content: space-between; align-items: center;">
            <button onclick="downloadPDF()" class="btn-download" style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem;">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
            <form action="acc_revenue" method="GET" class="filter-form">
                <select name="filter" id="filterSelect" onchange="this.form.submit()">
                    <option value="week" <?php echo (isset($filter) && $filter === 'week') ? 'selected' : ''; ?>>This Week</option>
                    <option value="month" <?php echo (isset($filter) && $filter === 'month') ? 'selected' : ''; ?>>This Month</option>
                    <option value="year" <?php echo (isset($filter) && $filter === 'year') ? 'selected' : ''; ?>>This Year</option>
                </select>
            </form>
        </div>

        <div class="revenue-grid">
            <!-- overall Revenue for Selected Period -->
            <div class="revenue-card">
                <h3>Total Revenue (<?php echo htmlspecialchars($period_label ?? 'Current Period'); ?>)</h3>
                <div class="revenue-amount">Rs. <?php echo number_format($total_revenue ?? 0, 2); ?></div>
                
                <?php 
                $imp = $improvement ?? 0;
                $i_class = $imp > 0 ? 'positive' : ($imp < 0 ? 'negative' : 'neutral');
                $i_icon = $imp > 0 ? 'fa-arrow-up' : ($imp < 0 ? 'fa-arrow-down' : 'fa-minus');
                $i_text = $imp > 0 ? 'Improvement over last period' : ($imp < 0 ? 'Decline from last period' : 'No change');
                ?>
                <div class="improvement <?php echo $i_class; ?>">
                    <i class="fas <?php echo $i_icon; ?>"></i> 
                    <?php echo abs($imp); ?>% <?php echo $i_text; ?>
                </div>
            </div>

            <!-- total Bookings for Selected Period -->
            <div class="revenue-card">
                <h3>Total Bookings (<?php echo htmlspecialchars($period_label ?? 'Current Period'); ?>)</h3>
                <div class="revenue-amount"><?php echo number_format($total_bookings ?? 0); ?></div>
                <div class="improvement neutral">
                    <i class="fas fa-check-circle"></i> Completed and Confirmed
                </div>
            </div>
        </div>

        <!-- property Breakdown Table -->
        <div class="property-list">
            <h2>Individual Property Performance (<?php echo htmlspecialchars($period_label ?? 'Current Period'); ?>)</h2>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Property Title</th>
                        <th>Total Bookings</th>
                        <th>Revenue Earned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($properties)): ?>
                        <?php foreach($properties as $prop): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prop['title']); ?></td>
                                <td><?php echo htmlspecialchars($prop['property_bookings']); ?></td>
                                <td><strong>Rs. <?php echo number_format($prop['property_revenue'], 2); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #64748b;">No bookings found for the selected period.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
  </main>

<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

<!-- html2pdf Library for PDF Generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/js/Accommodation/revenue.js"></script>

</body>
</html>
