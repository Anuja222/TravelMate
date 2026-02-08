<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport / Vehicle Listings - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/transport.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../admin_header.view.php'; ?>

<?php 
$vehicles = $data['vehicles'];
$stats = $data['stats'];
$districts = $data['districts'];
$vehicleTypes = $data['vehicleTypes'];
$statusOptions = $data['statusOptions'];
$filters = $data['filters'];
$pagination = $data['pagination'];
?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <?php include __DIR__ . '/../flash_messages.php'; ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1><i class="fas fa-car-side"></i> Transport / Vehicle Listings</h1>
                <p class="subtitle">Manage all vehicle listings from transporters</p>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by model, number, district..." 
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-row">
            <div class="stat-card blue">
                <div class="icon"><i class="fas fa-car"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['total']) ?></div>
                    <div class="label">Total Vehicles</div>
                </div>
            </div>
            <div class="stat-card indigo">
                <div class="icon"><i class="fas fa-car-side"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['cars_suvs']) ?></div>
                    <div class="label">Cars & SUVs</div>
                </div>
            </div>
            <div class="stat-card orange">
                <div class="icon"><i class="fas fa-bus"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['vans_buses']) ?></div>
                    <div class="label">Vans & Buses</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['available']) ?></div>
                    <div class="label">Available</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-group">
                <select id="typeFilter" class="filter-select">
                    <option value="">All Types</option>
                    <?php foreach ($vehicleTypes as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $filters['type'] === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select id="districtFilter" class="filter-select">
                    <option value="">All Districts</option>
                    <?php foreach ($districts as $dist): ?>
                        <option value="<?= htmlspecialchars($dist->working_district) ?>" 
                                <?= $filters['district'] === $dist->working_district ? 'selected' : '' ?>>
                            <?= ucfirst(htmlspecialchars($dist->working_district)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <?php foreach ($statusOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $filters['status'] === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select id="sortFilter" class="filter-select">
                    <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest First</option>
                    <option value="oldest" <?= $filters['sort'] === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                    <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="views" <?= $filters['sort'] === 'views' ? 'selected' : '' ?>>Most Viewed</option>
                </select>
            </div>
            <button class="btn-clear-filters" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>

        <!-- Vehicles Grid -->
        <div class="vehicles-grid">
            <?php if (!empty($vehicles)): ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <?php 
                    $mainImage = $vehicle->main_image ? ROOT . '/' . $vehicle->main_image : ROOT . '/assets/images/default-vehicle.jpg';
                    $price = number_format($vehicle->price_per_day ?? 0);
                    $transporterName = $vehicle->transporter_name ?? 'Unknown Transporter';
                    $district = ucfirst($vehicle->working_district ?? 'Location not set');
                    $type = $vehicle->vehicle_type ?? 'car';
                    $status = $vehicle->status ?? 'active';
                    $vehicleName = $vehicle->vehicle_model ?? 'Vehicle';
                    ?>
                    <div class="vehicle-card" data-id="<?= $vehicle->id ?>">
                        <div class="card-image">
                            <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($vehicleName) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-vehicle.jpg'">
                            <span class="type-badge type-<?= $type ?>"><?= strtoupper(str_replace('_', ' ', $type)) ?></span>
                            <span class="status-badge status-<?= $status ?>"><?= strtoupper($status) ?></span>
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($vehicleName) ?></h3>
                            <div class="card-specs">
                                <?php if ($vehicle->vehicle_year): ?>
                                    <?= $vehicle->vehicle_year ?> • 
                                <?php endif; ?>
                                <?= htmlspecialchars($vehicle->vehicle_color ?? 'N/A') ?> • 
                                <?= strtoupper($vehicle->ac_type ?? 'non-ac') ?>
                            </div>
                            
                            <div class="card-meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($district) ?></span>
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($transporterName) ?></span>
                            </div>
                            
                            <?php if ($vehicle->price_per_day > 0): ?>
                            <div class="card-price">
                                <span class="price-day"><i class="fas fa-tag"></i> LKR <?= $price ?> / day</span>
                                <?php if ($vehicle->price_per_km > 0): ?>
                                    <span class="price-km">LKR <?= number_format($vehicle->price_per_km) ?> / km</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="card-details">
                                <span><i class="fas fa-users"></i> <?= $vehicle->passenger_count ?? 2 ?> Passengers</span>
                                <span><i class="fas fa-id-card"></i> <?= htmlspecialchars($vehicle->vehicle_number ?? 'N/A') ?></span>
                            </div>
                            
                            <div class="card-stats">
                                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($vehicle->created_at)) ?></span>
                                <span><i class="fas fa-eye"></i> <?= number_format($vehicle->views_count ?? 0) ?> views</span>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <button class="btn-view" onclick="viewVehicle(<?= $vehicle->id ?>)">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-delete" onclick="deleteVehicle(<?= $vehicle->id ?>, '<?= htmlspecialchars(addslashes($vehicleName)) ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-car"></i>
                    <h3>No Vehicles Found</h3>
                    <p>
                        <?php if ($filters['search'] || $filters['type'] || $filters['district'] || $filters['status']): ?>
                            No vehicles match your current filters. Try adjusting your search criteria.
                        <?php else: ?>
                            There are no vehicle listings yet. Transporters can add vehicles from their dashboard.
                        <?php endif; ?>
                    </p>
                    <?php if ($filters['search'] || $filters['type'] || $filters['district'] || $filters['status']): ?>
                        <button class="btn-primary" onclick="clearFilters()">
                            <i class="fas fa-times"></i> Clear Filters
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['totalPages'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['currentPage'] > 1): ?>
                    <a href="?page=<?= $pagination['currentPage'] - 1 ?>&type=<?= urlencode($filters['type']) ?>&district=<?= urlencode($filters['district']) ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search']) ?>&sort=<?= $filters['sort'] ?>" class="page-link">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>
                
                <span class="page-info">
                    Page <?= $pagination['currentPage'] ?> of <?= $pagination['totalPages'] ?>
                    (<?= $pagination['totalItems'] ?> total)
                </span>
                
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="?page=<?= $pagination['currentPage'] + 1 ?>&type=<?= urlencode($filters['type']) ?>&district=<?= urlencode($filters['district']) ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search']) ?>&sort=<?= $filters['sort'] ?>" class="page-link">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header modal-header-danger">
            <h3><i class="fas fa-exclamation-triangle"></i> Delete Vehicle</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this vehicle?</p>
            <div class="delete-item-info">
                <i class="fas fa-car"></i>
                <strong id="deleteVehicleName"></strong>
            </div>
            <p class="warning-text">
                <i class="fas fa-warning"></i> 
                This action will remove the vehicle from public view. The transporter will lose this listing.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" onclick="confirmDelete()">
                <i class="fas fa-trash"></i> Delete Vehicle
            </button>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="notification"></div>

<script>
const ROOT = '<?= ROOT ?>';
let vehicleToDelete = null;

// View vehicle details
function viewVehicle(id) {
    window.location.href = ROOT + '/admin/transport/view?id=' + id;
}

// Delete vehicle
function deleteVehicle(id, name) {
    vehicleToDelete = id;
    document.getElementById('deleteVehicleName').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
}

function confirmDelete() {
    if (vehicleToDelete) {
        fetch(ROOT + '/api/admin/transport/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: vehicleToDelete})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Vehicle deleted successfully!', 'success');
                closeDeleteModal();
                // Remove card from grid
                const card = document.querySelector(`[data-id="${vehicleToDelete}"]`);
                if (card) {
                    card.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => card.remove(), 300);
                }
                // Reload after short delay
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete vehicle', 'error');
        });
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    vehicleToDelete = null;
}

// Filters
document.getElementById('typeFilter').addEventListener('change', applyFilters);
document.getElementById('districtFilter').addEventListener('change', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('sortFilter').addEventListener('change', applyFilters);

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

function applyFilters() {
    const type = document.getElementById('typeFilter').value;
    const district = document.getElementById('districtFilter').value;
    const status = document.getElementById('statusFilter').value;
    const sort = document.getElementById('sortFilter').value;
    const search = document.getElementById('searchInput').value;
    
    let url = ROOT + '/admin/transport?';
    if (type) url += 'type=' + encodeURIComponent(type) + '&';
    if (district) url += 'district=' + encodeURIComponent(district) + '&';
    if (status) url += 'status=' + encodeURIComponent(status) + '&';
    if (search) url += 'search=' + encodeURIComponent(search) + '&';
    url += 'sort=' + sort;
    
    window.location.href = url;
}

function clearFilters() {
    window.location.href = ROOT + '/admin/transport';
}

// Show notification
function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = 'notification ' + type + ' show';
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Close modal on outside click
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>

</body>
</html>
