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
    <title>TravelMate - Payment History</title>
    <link rel="stylesheet" href="assets/css/Transpoter/payment_history.css">
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
            <h1><i class="fas fa-credit-card"></i> Payment History</h1>
            <p>View all your transactions and payment details</p>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Total Earnings</span>
                    <span class="summary-value">LKR 2,50,000</span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Successful Payments</span>
                    <span class="summary-value">LKR 2,35,000</span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Pending Payments</span>
                    <span class="summary-value">LKR 15,000</span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Total Transactions</span>
                    <span class="summary-value">24</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <div class="filter-group">
                <label for="dateRange">Date Range</label>
                <select id="dateRange" class="filter-select">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="paymentStatus">Payment Status</label>
                <select id="paymentStatus" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" id="search" class="filter-input" placeholder="Search by booking ID or customer">
            </div>
            
            <button class="filter-btn">
                <i class="fas fa-search"></i> Apply Filters
            </button>
        </div>

        <!-- Payment Table -->
        <div class="table-container">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="booking-id">#TB-2024-001</span></td>
                        <td>Mar 15, 2024<br><small>10:30 AM</small></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">John Smith</span>
                                <span class="customer-email">john@email.com</span>
                            </div>
                        </td>
                        <td><span class="amount">LKR 45,000</span></td>
                        <td><span class="payment-method"><i class="fas fa-credit-card"></i> Card</span></td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="action-btn view-btn" onclick="viewInvoice('TB-2024-001')">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><span class="booking-id">#TB-2024-002</span></td>
                        <td>Mar 14, 2024<br><small>02:15 PM</small></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Sarah Johnson</span>
                                <span class="customer-email">sarah@email.com</span>
                            </div>
                        </td>
                        <td><span class="amount">LKR 32,500</span></td>
                        <td><span class="payment-method"><i class="fas fa-mobile-alt"></i> Digital Wallet</span></td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="action-btn view-btn" onclick="viewInvoice('TB-2024-002')">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><span class="booking-id">#TB-2024-003</span></td>
                        <td>Mar 12, 2024<br><small>09:45 AM</small></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Michael Chen</span>
                                <span class="customer-email">michael@email.com</span>
                            </div>
                        </td>
                        <td><span class="amount">LKR 28,000</span></td>
                        <td><span class="payment-method"><i class="fas fa-university"></i> Bank Transfer</span></td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>
                            <button class="action-btn view-btn" onclick="viewInvoice('TB-2024-003')">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><span class="booking-id">#TB-2024-004</span></td>
                        <td>Mar 10, 2024<br><small>04:20 PM</small></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Emily Davis</span>
                                <span class="customer-email">emily@email.com</span>
                            </div>
                        </td>
                        <td><span class="amount">LKR 52,000</span></td>
                        <td><span class="payment-method"><i class="fas fa-credit-card"></i> Card</span></td>
                        <td><span class="status-badge failed">Failed</span></td>
                        <td>
                            <button class="action-btn view-btn" onclick="viewInvoice('TB-2024-004')">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><span class="booking-id">#TB-2024-005</span></td>
                        <td>Mar 8, 2024<br><small>11:00 AM</small></td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">Robert Wilson</span>
                                <span class="customer-email">robert@email.com</span>
                            </div>
                        </td>
                        <td><span class="amount">LKR 18,500</span></td>
                        <td><span class="payment-method"><i class="fas fa-credit-card"></i> Card</span></td>
                        <td><span class="status-badge refunded">Refunded</span></td>
                        <td>
                            <button class="action-btn view-btn" onclick="viewInvoice('TB-2024-005')">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn" disabled><i class="fas fa-chevron-left"></i></button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

<script>
function viewInvoice(bookingId) {
    // Redirect to invoice page
    window.location.href = `/TravelMate/public/invoice/${bookingId}`;
}

// Filter functionality
document.querySelector('.filter-btn').addEventListener('click', function() {
    // In a real app, this would apply filters and reload data
    alert('Filters applied! In a real application, this would filter the payment history.');
});
</script>

</body>
</html>