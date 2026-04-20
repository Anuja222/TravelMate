<?php

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
  <link rel="stylesheet" href="assets/css/Transpoter/setting.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .filter-container {
      margin-top: 20px;
      margin-bottom: 20px;
      display: flex;
      justify-content: flex-end;
    }
    .filter-form select {
      padding: 8px 16px;
      width: 9em;
      border: 1px solid #e1e8f0;
      border-radius: 6px;
      margin-right: 10px;
      font-size: 1rem;
      color: #334155;
      cursor: pointer;
    }
    .revenue-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .revenue-card {
      background-color: white;
      border-radius: 8px;
      padding: 24px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      border: 1px solid #e1e8f0;
      text-align: center;
    }
    .revenue-card h3 {
      font-size: 1.2rem;
      color: #64748b;
      margin-bottom: 15px;
    }
    .revenue-amount {
      font-size: 2.5rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 10px;
    }
    .improvement {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.9rem;
    }
    .improvement.positive {
      color: #10b981;
      background-color: #ecfdf5;
    }
    .improvement.negative {
      color: #ef4444;
      background-color: #fef2f2;
    }
    .improvement.neutral {
      color: #64748b;
      background-color: #f1f5f9;
    }
    .property-list {
      margin-top: 40px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      border: 1px solid #e1e8f0;
      overflow: hidden;
    }
    .property-list h2 {
      padding: 20px;
      border-bottom: 1px solid #e1e8f0;
      font-size: 1.2rem;
      color: #0f172a;
    }
    .property-table {
      width: 100%;
      border-collapse: collapse;
    }
    .property-table th, .property-table td {
      padding: 15px 20px;
      text-align: left;
      border-bottom: 1px solid #e1e8f0;
    }
    .property-table th {
      background-color: #f8fafc;
      font-weight: 600;
      color: #475569;
    }
    .property-table tr:hover {
      background-color: #f8fafc;
    }
    .property-table tr {
      page-break-inside: avoid;
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <main>
   
    <?php
      $active_page = 'revenue';
      include __DIR__ . '/sidebar.view.php';
    ?>

    <div class="content">
       
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
                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;"><strong>Provider:</strong> <?php echo htmlspecialchars($_SESSION['USER']->name ?? $_SESSION['user_name'] ?? 'Transport Provider'); ?></p>
                </div>
            </div>
            <div style="margin-top: 20px; padding: 12px 15px; background: #f8fafc; border-radius: 6px; border-left: 4px solid #1abc5b;">
                <p style="margin: 0; color: #334155; font-size: 14px; line-height: 1.5;">
                    <strong>Dashboard Activity Summary:</strong> Official documented revenue, booking activity, and statistics for your verified vehicles on the TravelMate platform for the period of <strong><?php echo htmlspecialchars($period_label ?? 'Current Period'); ?></strong>.
                </p>
            </div>
        </div>

      <div class="page-title">
        <h1><i class="fas fa-chart-line"></i> Revenue Dashboard</h1>
        <p>Monitor your earnings and vehicle performance</p>
      </div>

      <div class="filter-container" style="display: flex; justify-content: space-between; align-items: center;">
        <button onclick="downloadPDF()" class="btn-download" style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem;">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
        <form class="filter-form" method="GET" action="tr_revenue" id="filterForm">
          <select name="filter" onchange="document.getElementById('filterForm').submit()">
            <option value="week" <?= isset($filter) && $filter == 'week' ? 'selected' : '' ?>>This Week</option>
            <option value="month" <?= isset($filter) && $filter == 'month' ? 'selected' : '' ?>>This Month</option>
            <option value="year" <?= isset($filter) && $filter == 'year' ? 'selected' : '' ?>>This Year</option>
          </select>
        </form>
      </div>

      <div class="revenue-grid">
        <div class="revenue-card">
          <h3><?= htmlspecialchars($period_label ?? 'This Month') ?> Revenue</h3>
          <div class="revenue-amount">Rs. <?= number_format($total_revenue ?? 0, 2) ?></div>
          
          <?php 
            $imp = $improvement ?? 0;
            $class = 'neutral';
            $icon = 'minus';
            $text = 'No change';
            
            if ($imp > 0) {
                $class = 'positive';
                $icon = 'arrow-up';
                $text = '+' . $imp . '% vs last period';
            } elseif ($imp < 0) {
                $class = 'negative';
                $icon = 'arrow-down';
                $text = $imp . '% vs last period';
            }
          ?>
          <div class="improvement <?= $class ?>">
            <i class="fas fa-<?= $icon ?>"></i> <?= $text ?>
          </div>
        </div>

        <div class="revenue-card">
          <h3>Total Bookings <?= htmlspecialchars($period_label ?? 'This Month') ?></h3>
          <div class="revenue-amount"><?= number_format($total_bookings ?? 0) ?></div>
          <div style="color: #64748b; font-size: 0.9rem; margin-top: 10px;">Completed/Confirmed bookings</div>
        </div>
      </div>

      <div class="property-list">
        <h2>Vehicle Revenue Breakdown</h2>
        <?php if (!empty($properties)): ?>
            <div style="overflow-x: auto;">
                <table class="property-table">
                  <thead>
                    <tr>
                      <th>Vehicle</th>
                      <th>Number Plate</th>
                      <th>Bookings (<?= htmlspecialchars($period_label ?? 'This Month') ?>)</th>
                      <th>Revenue (<?= htmlspecialchars($period_label ?? 'This Month') ?>)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($properties as $prop): ?>
                        <tr>
                          <td><strong><?= htmlspecialchars($prop['title']) ?></strong></td>
                          <td><?= htmlspecialchars($prop['vehicle_number'] ?? 'N/A') ?></td>
                          <td><?= number_format($prop['property_bookings']) ?></td>
                          <td>Rs. <?= number_format($prop['property_revenue'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="padding: 30px; text-align: center; color: #64748b;">
                <i class="fas fa-car" style="font-size: 3rem; margin-bottom: 15px; color: #cbd5e1;"></i>
                <p>No revenue data found for this period.</p>
            </div>
        <?php endif; ?>
      </div>

    </div>
  </main>

<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const element = document.querySelector('.content');
    const filterContainer = document.querySelector('.filter-container');
    const pdfHeader = document.getElementById('pdfHeader');
    

    if(filterContainer) filterContainer.style.display = 'none';
    if(pdfHeader) pdfHeader.style.display = 'block';
    
    const opt = {
        margin:       [0.5, 0.5, 0.5, 0.5],
        filename:     'TravelMate_Transport_Revenue_<?php echo date('Y-m-d'); ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
        pagebreak:    { mode: ['css', 'legacy'] }
    };

    // generate pdf
    html2pdf().set(opt).from(element).save().then(() => {
        if(filterContainer) filterContainer.style.display = 'flex';
        if(pdfHeader) pdfHeader.style.display = 'none';
    });
}
</script>

</body>
</html>
