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

<!-- Payment Details Modal->
<
            
        </div>
        <div class="modal-footer">
            <button class="modal-btn download-btn" onclick="downloadInvoice()">
                <i class="fas fa-download"></i> Download Invoice
            </button>
            <button class="modal-btn print-btn" onclick="printInvoice()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="modal-btn close-btn" onclick="closePaymentModal()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

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
            <p>View all your transactions and payment details from here</p>
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
                        <th></th>
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
                            <button class="view-payment-btn" onclick="showPaymentDetails('TB-2024-001')">
                                <i class="fas fa-eye"></i> View
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
                        <td><span class="amount">LKR 33 000</span></td>
                        <td><span class="payment-method"><i class="fas fa-mobile-alt"></i> Digital Wallet</span></td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="view-payment-btn" onclick="showPaymentDetails('TB-2024-002')">
                                <i class="fas fa-eye"></i> View
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
                            <button class="view-payment-btn" onclick="showPaymentDetails('TB-2024-003')">
                                <i class="fas fa-eye"></i> View
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
                            <button class="view-payment-btn" onclick="showPaymentDetails('TB-2024-004')">
                                <i class="fas fa-eye"></i> View
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
                            <button class="view-payment-btn" onclick="showPaymentDetails('TB-2024-005')">
                                <i class="fas fa-eye"></i> View
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
// Payment data (in a real app, this would come from the server)
const paymentDetails = {
    'TB-2024-001': {
        bookingId: '#TB-2024-001',
        date: 'Mar 15, 2024',
        time: '10:30 AM',
        customer: {
            name: 'John Smith',
            email: 'john@email.com',
            phone: '+94 77 123 4567'
        },
        amount: 'LKR 45,000',
        paymentMethod: 'Credit Card',
        cardDetails: '**** **** **** 4242',
        status: 'completed',
        transactionId: 'TXN-123456789',
        tripDetails: {
            from: 'Colombo',
            to: 'Kandy',
            date: 'Mar 15, 2024',
            time: '08:00 AM',
            vehicle: 'Toyota Prius (PB-1234)'
        }
    },
    'TB-2024-002': {
        bookingId: '#TB-2024-002',
        date: 'Mar 14, 2024',
        time: '02:15 PM',
        customer: {
            name: 'Sarah Johnson',
            email: 'sarah@email.com',
            phone: '+94 77 234 5678'
        },
        amount: 'LKR 32,500',
        paymentMethod: 'Digital Wallet',
        walletDetails: 'PickMe Pay',
        status: 'completed',
        transactionId: 'TXN-234567890',
        tripDetails: {
            from: 'Galle',
            to: 'Matara',
            date: 'Mar 14, 2024',
            time: '01:00 PM',
            vehicle: 'Honda Vezel (PB-5678)'
        }
    },
    'TB-2024-003': {
        bookingId: '#TB-2024-003',
        date: 'Mar 12, 2024',
        time: '09:45 AM',
        customer: {
            name: 'Michael Chen',
            email: 'michael@email.com',
            phone: '+94 77 345 6789'
        },
        amount: 'LKR 28,000',
        paymentMethod: 'Bank Transfer',
        bankDetails: 'Commercial Bank - ****1234',
        status: 'pending',
        transactionId: 'TXN-345678901',
        tripDetails: {
            from: 'Negombo',
            to: 'Colombo',
            date: 'Mar 12, 2024',
            time: '08:30 AM',
            vehicle: 'Suzuki Swift (PB-9012)'
        }
    },
    'TB-2024-004': {
        bookingId: '#TB-2024-004',
        date: 'Mar 10, 2024',
        time: '04:20 PM',
        customer: {
            name: 'Emily Davis',
            email: 'emily@email.com',
            phone: '+94 77 456 7890'
        },
        amount: 'LKR 52,000',
        paymentMethod: 'Credit Card',
        cardDetails: '**** **** **** 5678',
        status: 'failed',
        failureReason: 'Insufficient funds',
        transactionId: 'TXN-456789012',
        tripDetails: {
            from: 'Colombo',
            to: 'Galle',
            date: 'Mar 10, 2024',
            time: '03:00 PM',
            vehicle: 'Toyota KDH (PB-3456)'
        }
    },
    'TB-2024-005': {
        bookingId: '#TB-2024-005',
        date: 'Mar 8, 2024',
        time: '11:00 AM',
        customer: {
            name: 'Robert Wilson',
            email: 'robert@email.com',
            phone: '+94 77 567 8901'
        },
        amount: 'LKR 18,500',
        paymentMethod: 'Credit Card',
        cardDetails: '**** **** **** 9012',
        status: 'refunded',
        refundReason: 'Trip cancelled by customer',
        refundDate: 'Mar 9, 2024',
        transactionId: 'TXN-567890123',
        tripDetails: {
            from: 'Kandy',
            to: 'Nuwara Eliya',
            date: 'Mar 8, 2024',
            time: '09:00 AM',
            vehicle: 'Micro Panda (PB-7890)'
        }
    }
};

let currentBookingId = '';

function showPaymentDetails(bookingId) {
    currentBookingId = bookingId;
    const payment = paymentDetails[bookingId];
    if (!payment) return;
    
    const modalBody = document.getElementById('paymentModalBody');
    const statusClass = payment.status.toLowerCase();
    
    modalBody.innerHTML = `
        <div class="payment-detail-header">
            <div class="payment-status-badge ${statusClass}">
                ${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}
            </div>
            <div class="payment-transaction-id">
                <i class="fas fa-hashtag"></i> TXN: ${payment.transactionId}
            </div>
        </div>
        
        <div class="payment-detail-grid">
            <div class="payment-detail-section">
                <h4><i class="fas fa-info-circle"></i> Booking Information</h4>
                <div class="detail-item">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">${payment.bookingId}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">${payment.date} at ${payment.time}</span>
                </div>
            </div>
            
            <div class="payment-detail-section">
                <h4><i class="fas fa-user"></i> Customer Information</h4>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">${payment.customer.name}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">${payment.customer.email}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">${payment.customer.phone}</span>
                </div>
            </div>
            
            <div class="payment-detail-section">
                <h4><i class="fas fa-car"></i> Trip Details</h4>
                <div class="detail-item">
                    <span class="detail-label">Route:</span>
                    <span class="detail-value">${payment.tripDetails.from} → ${payment.tripDetails.to}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Departure:</span>
                    <span class="detail-value">${payment.tripDetails.date} at ${payment.tripDetails.time}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Vehicle:</span>
                    <span class="detail-value">${payment.tripDetails.vehicle}</span>
                </div>
            </div>
            
            <div class="payment-detail-section">
                <h4><i class="fas fa-credit-card"></i> Payment Information</h4>
                <div class="detail-item highlight">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount-value">${payment.amount}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Method:</span>
                    <span class="detail-value">${payment.paymentMethod}</span>
                </div>
                ${payment.cardDetails ? `
                <div class="detail-item">
                    <span class="detail-label">Card:</span>
                    <span class="detail-value">${payment.cardDetails}</span>
                </div>
                ` : ''}
                ${payment.walletDetails ? `
                <div class="detail-item">
                    <span class="detail-label">Wallet:</span>
                    <span class="detail-value">${payment.walletDetails}</span>
                </div>
                ` : ''}
                ${payment.bankDetails ? `
                <div class="detail-item">
                    <span class="detail-label">Bank:</span>
                    <span class="detail-value">${payment.bankDetails}</span>
                </div>
                ` : ''}
                ${payment.failureReason ? `
                <div class="detail-item error">
                    <span class="detail-label">Reason:</span>
                    <span class="detail-value">${payment.failureReason}</span>
                </div>
                ` : ''}
                ${payment.refundReason ? `
                <div class="detail-item">
                    <span class="detail-label">Refund Reason:</span>
                    <span class="detail-value">${payment.refundReason}</span>
                </div>
                ` : ''}
                ${payment.refundDate ? `
                <div class="detail-item">
                    <span class="detail-label">Refund Date:</span>
                    <span class="detail-value">${payment.refundDate}</span>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    
    // Show the modal
    document.getElementById('paymentModal').style.display = 'flex';
    // Prevent body from scrolling when modal is open
    document.body.style.overflow = 'hidden';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    // Re-enable body scrolling
    document.body.style.overflow = 'auto';
}

function downloadInvoice() {
    if (currentBookingId) {
        alert(`Downloading invoice for booking ${currentBookingId}`);
        // In a real app, this would trigger a PDF download
    }
}

function printInvoice() {
    if (currentBookingId) {
        alert(`Printing invoice for booking ${currentBookingId}`);
        // In a real app, this would open a print-friendly version
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        closePaymentModal();
    }
}

// Filter functionality
document.querySelector('.filter-btn')?.addEventListener('click', function() {
    alert('Filters applied! In a real application, this would filter the payment history.');
});
</script>

</body>
</html>