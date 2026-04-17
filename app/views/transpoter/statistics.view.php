<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a transporter
$isTransporter = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'transport';
if (!$isTransporter) {
    header('Location: /TravelMate/public/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Statistics</title>
    <link rel="stylesheet" href="assets/css/Transpoter/statistics.css">
    <link rel="stylesheet" href="assets/css/Transpoter/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<!-- MAIN CONTENT -->
<main>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-menu">
                <a href="/TravelMate/public/tr_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="/TravelMate/public/bookingnew"><i class="fas fa-calendar-alt"></i> Bookings</a>
                <a href="/TravelMate/public/payment-history"><i class="fas fa-credit-card"></i> Payment History</a>
                <a href="/TravelMate/public/statistics"><i class="fas fa-chart-line"></i> Statistics</a>
                <a href="/TravelMate/public/setting"><i class="fas fa-cog"></i> Settings</a>
            </div>
        </div>
    </aside>

    <div class="content">
        <div class="page-title">
            <h1><i class="fas fa-chart-line"></i> Statistics</h1>
            <p>Analyze your business performance and trends for each of your vehicle</p>
        </div>

        <!-- Period Selector -->
        <div class="period-selector">
            <button class="period-btn active" data-period="week">This Week</button>
            <button class="period-btn" data-period="month">This Month</button>
            <button class="period-btn" data-period="quarter">This Quarter</button>
            <button class="period-btn" data-period="year">This Year</button>
            <button class="period-btn" data-period="custom">Custom Range</button>
        </div>

        <!-- Key Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Total Bookings</span>
                    <span class="metric-value">156</span>
                    <span class="metric-change positive">
                        <i class="fas fa-arrow-up"></i> +12.5%
                    </span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Total Revenue</span>
                    <span class="metric-value">LKR 4,85,000</span>
                    <span class="metric-change positive">
                        <i class="fas fa-arrow-up"></i> +8.3%
                    </span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Unique Customers</span>
                    <span class="metric-value">89</span>
                    <span class="metric-change positive">
                        <i class="fas fa-arrow-up"></i> +15.2%
                    </span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Average Rating</span>
                    <span class="metric-value">4.8</span>
                    <span class="metric-change positive">
                        <i class="fas fa-arrow-up"></i> +0.3
                    </span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Completion Rate</span>
                    <span class="metric-value">94%</span>
                    <span class="metric-change positive">
                        <i class="fas fa-arrow-up"></i> +2.1%
                    </span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Avg. Response Time</span>
                    <span class="metric-value">2.4 hrs</span>
                    <span class="metric-change negative">
                        <i class="fas fa-arrow-down"></i> -0.5 hrs
                    </span>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Revenue Overview</h3>
                    <select class="chart-select">
                        <option>Daily</option>
                        <option>Weekly</option>
                        <option selected>Monthly</option>
                    </select>
                </div>
                <div class="chart-container">
                    <div class="chart-bars">
                        <div class="bar-item">
                            <div class="bar" style="height: 120px;"></div>
                            <span>Jan</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 150px;"></div>
                            <span>Feb</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 90px;"></div>
                            <span>Mar</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 180px;"></div>
                            <span>Apr</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 210px;"></div>
                            <span>May</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 160px;"></div>
                            <span>Jun</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 190px;"></div>
                            <span>Jul</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 220px;"></div>
                            <span>Aug</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Booking Distribution</h3>
                    <select class="chart-select">
                        <option>By Vehicle</option>
                        <option>By Location</option>
                        <option selected>By Type</option>
                    </select>
                </div>
                <div class="pie-chart-container">
                    <div class="pie-chart">
                        <div class="pie-segment" style="--percentage: 45; --color: #1abc5b;"></div>
                        <div class="pie-segment" style="--percentage: 30; --color: #3498db;"></div>
                        <div class="pie-segment" style="--percentage: 15; --color: #f39c12;"></div>
                        <div class="pie-segment" style="--percentage: 10; --color: #e74c3c;"></div>
                    </div>
                    <div class="pie-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #1abc5b;"></span>
                            <span class="legend-label">Car (45%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #3498db;"></span>
                            <span class="legend-label">Van (30%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #f39c12;"></span>
                            <span class="legend-label">Bus (15%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #e74c3c;"></span>
                            <span class="legend-label">Other (10%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Booking Trends</h3>
                    <select class="chart-select">
                        <option>Last 7 days</option>
                        <option selected>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <div class="line-chart">
                    <div class="line-chart-grid">
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                    </div>
                    <div class="line-chart-points">
                        <div class="chart-point" style="bottom: 40px; left: 5%;"></div>
                        <div class="chart-point" style="bottom: 60px; left: 20%;"></div>
                        <div class="chart-point" style="bottom: 35px; left: 35%;"></div>
                        <div class="chart-point" style="bottom: 75px; left: 50%;"></div>
                        <div class="chart-point" style="bottom: 55px; left: 65%;"></div>
                        <div class="chart-point" style="bottom: 85px; left: 80%;"></div>
                        <div class="chart-point" style="bottom: 45px; left: 95%;"></div>
                    </div>
                    <div class="line-chart-labels">
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                        <span>Sun</span>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Top Performing Vehicles</h3>
                    <a href="#" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ranking-list">
                    <div class="ranking-item">
                        <div class="ranking-position">1</div>
                        <div class="ranking-info">
                            <span class="ranking-name">Toyota Hiace (Van)</span>
                            <span class="ranking-stats">45 bookings · LKR 2,15,000</span>
                        </div>
                        <span class="ranking-trend positive">
                            <i class="fas fa-arrow-up"></i> +12%
                        </span>
                    </div>
                    
                    <div class="ranking-item">
                        <div class="ranking-position">2</div>
                        <div class="ranking-info">
                            <span class="ranking-name">Nissan Caravan (Van)</span>
                            <span class="ranking-stats">38 bookings · LKR 1,82,000</span>
                        </div>
                        <span class="ranking-trend positive">
                            <i class="fas fa-arrow-up"></i> +8%
                        </span>
                    </div>
                    
                    <div class="ranking-item">
                        <div class="ranking-position">3</div>
                        <div class="ranking-info">
                            <span class="ranking-name">Toyota Prius (Car)</span>
                            <span class="ranking-stats">32 bookings · LKR 96,000</span>
                        </div>
                        <span class="ranking-trend negative">
                            <i class="fas fa-arrow-down"></i> -3%
                        </span>
                    </div>
                    
                    <div class="ranking-item">
                        <div class="ranking-position">4</div>
                        <div class="ranking-info">
                            <span class="ranking-name">Mitsubishi Rosa (Bus)</span>
                            <span class="ranking-stats">28 bookings · LKR 2,52,000</span>
                        </div>
                        <span class="ranking-trend positive">
                            <i class="fas fa-arrow-up"></i> +15%
                        </span>
                    </div>
                    
                    <div class="ranking-item">
                        <div class="ranking-position">5</div>
                        <div class="ranking-info">
                            <span class="ranking-name">Suzuki Every (Van)</span>
                            <span class="ranking-stats">24 bookings · LKR 86,000</span>
                        </div>
                        <span class="ranking-trend neutral">
                            <i class="fas fa-minus"></i> 0%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="export-section">
            <button class="export-btn">
                <i class="fas fa-file-pdf"></i> Export as PDF
            </button>
            <button class="export-btn">
                <i class="fas fa-file-excel"></i> Export as Excel
            </button>
            <button class="export-btn">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

<script>
// Period selector functionality
document.querySelectorAll('.period-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        if (this.dataset.period === 'custom') {
            // Show custom date range picker
            alert('Custom date range picker would appear here');
        }
    });
});
</script>

</body>
</html>