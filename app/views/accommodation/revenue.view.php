<?php
// Start session if not already started
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
  <link rel="stylesheet" href="assets/css/accommodation/setting.css">
  <link rel="stylesheet" href="assets/css/accommodation/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>      /* Sidebar Active State */
      .sidebar ul li a.active {
          background: #e9f6ee;
          color: #1abc5b;
          font-weight: 600;
      }    .filter-container {
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
      color: #64748b;
      font-weight: 600;
    }
    .property-table tr:last-child td {
      border-bottom: none;
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- MAIN CONTENT -->
  <main>
    <!-- SIDEBAR -->
    <?php 
    $active_page = 'revenue';
    include __DIR__ . '/sidebar.view.php'; 
    ?>

    <div class="content">
        <div class="page-title">
          <h1>Revenue Analytics</h1>
          <p>Track your earnings and business performance over time</p>
        </div>

        <div class="filter-container">
            <form action="acc_revenue" method="GET" class="filter-form">
                <select name="filter" id="filterSelect" onchange="this.form.submit()">
                    <option value="week" <?php echo (isset($filter) && $filter === 'week') ? 'selected' : ''; ?>>This Week</option>
                    <option value="month" <?php echo (isset($filter) && $filter === 'month') ? 'selected' : ''; ?>>This Month</option>
                    <option value="year" <?php echo (isset($filter) && $filter === 'year') ? 'selected' : ''; ?>>This Year</option>
                </select>
            </form>
        </div>

        <div class="revenue-grid">
            <!-- Overall Revenue for Selected Period -->
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

            <!-- Total Bookings for Selected Period -->
            <div class="revenue-card">
                <h3>Total Bookings (<?php echo htmlspecialchars($period_label ?? 'Current Period'); ?>)</h3>
                <div class="revenue-amount"><?php echo number_format($total_bookings ?? 0); ?></div>
                <div class="improvement neutral">
                    <i class="fas fa-check-circle"></i> Completed and Confirmed
                </div>
            </div>
        </div>

        <!-- Property Breakdown Table -->
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

</body>
</html>
