<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodation Listings - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/accommodations.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<?php 
$accommodations = $data['accommodations'];
$stats = $data['stats'];
$cities = $data['cities'];
$propertyTypes = $data['propertyTypes'];
$filters = $data['filters'];
$pagination = $data['pagination'];
?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1><i class="fas fa-hotel"></i> Accommodation Listings</h1>
                <p class="subtitle">Manage all accommodation listings from providers</p>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by name or city..." 
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-row">
            <div class="stat-card blue">
                <div class="icon"><i class="fas fa-building"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['total']) ?></div>
                    <div class="label">Total Listings</div>
                </div>
            </div>
            <div class="stat-card indigo">
                <div class="icon"><i class="fas fa-hotel"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['hotels']) ?></div>
                    <div class="label">Hotels</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['resorts']) ?></div>
                    <div class="label">Resorts</div>
                </div>
            </div>
            <div class="stat-card cyan">
                <div class="icon"><i class="fas fa-eye"></i></div>
                <div class="details">
                    <div class="value"><?= number_format($stats['total_views']) ?></div>
                    <div class="label">Total Views</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-group">
                <select id="typeFilter" class="filter-select">
                    <option value="">All Types</option>
                    <?php foreach ($propertyTypes as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $filters['type'] === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select id="cityFilter" class="filter-select">
                    <option value="">All Cities</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city->city) ?>" 
                                <?= $filters['city'] === $city->city ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city->city) ?>
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

        <!-- Accommodations Grid -->
        <div class="accommodations-grid">
            <?php if (!empty($accommodations)): ?>
                <?php foreach ($accommodations as $acc): ?>
                    <?php 
                    $mainImage = $acc->main_image ? ROOT . '/' . $acc->main_image : ROOT . '/assets/images/default-accommodation.jpg';
                    $price = number_format($acc->price_per_night ?? 0);
                    $providerName = $acc->provider_name ?? 'Unknown Provider';
                    $city = $acc->city ?? 'Location not set';
                    $type = $acc->property_type ?? 'hotel';
                    ?>
                    <div class="accommodation-card" data-id="<?= $acc->id ?>">
                        <div class="card-image">
                            <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($acc->title) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-accommodation.jpg'">
                            <span class="type-badge type-<?= $type ?>"><?= strtoupper($type) ?></span>
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($acc->title) ?></h3>
                            
                            <div class="card-meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?></span>
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($providerName) ?></span>
                            </div>
                            
                            <div class="card-price">
                                <i class="fas fa-tag"></i> LKR <?= $price ?> / night
                            </div>
                            
                            <div class="card-details">
                                <span><i class="fas fa-door-open"></i> <?= $acc->rooms ?? 0 ?> Rooms</span>
                                <span><i class="fas fa-users"></i> <?= $acc->max_guests ?? 0 ?> Guests</span>
                            </div>
                            
                            <div class="card-stats">
                                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($acc->created_at)) ?></span>
                                <span><i class="fas fa-eye"></i> <?= number_format($acc->views_count ?? 0) ?> views</span>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <button class="btn-view" onclick="viewAccommodation(<?= $acc->id ?>)">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-delete" onclick="deleteAccommodation(<?= $acc->id ?>, '<?= htmlspecialchars(addslashes($acc->title)) ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-hotel"></i>
                    <h3>No Accommodations Found</h3>
                    <p>
                        <?php if ($filters['search'] || $filters['type'] || $filters['city']): ?>
                            No accommodations match your current filters. Try adjusting your search criteria.
                        <?php else: ?>
                            There are no accommodation listings yet. Providers can add listings from their dashboard.
                        <?php endif; ?>
                    </p>
                    <?php if ($filters['search'] || $filters['type'] || $filters['city']): ?>
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
                    <a href="?page=<?= $pagination['currentPage'] - 1 ?>&type=<?= urlencode($filters['type']) ?>&city=<?= urlencode($filters['city']) ?>&search=<?= urlencode($filters['search']) ?>&sort=<?= $filters['sort'] ?>" class="page-link">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>
                
                <span class="page-info">
                    Page <?= $pagination['currentPage'] ?> of <?= $pagination['totalPages'] ?>
                    (<?= $pagination['totalItems'] ?> total)
                </span>
                
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="?page=<?= $pagination['currentPage'] + 1 ?>&type=<?= urlencode($filters['type']) ?>&city=<?= urlencode($filters['city']) ?>&search=<?= urlencode($filters['search']) ?>&sort=<?= $filters['sort'] ?>" class="page-link">
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
            <h3><i class="fas fa-exclamation-triangle"></i> Delete Accommodation</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this accommodation?</p>
            <div class="delete-item-info">
                <i class="fas fa-hotel"></i>
                <strong id="deleteAccommodationName"></strong>
            </div>
            <p class="warning-text">
                <i class="fas fa-warning"></i> 
                This action will remove the listing from public view. The provider will lose this listing.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" onclick="confirmDelete()">
                <i class="fas fa-trash"></i> Delete Listing
            </button>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="notification"></div>

<script>
const ROOT = '<?= ROOT ?>';
let accommodationToDelete = null;

// View accommodation details
function viewAccommodation(id) {
    window.location.href = ROOT + '/admin/accommodations/view?id=' + id;
}

// Delete accommodation
function deleteAccommodation(id, name) {
    accommodationToDelete = id;
    document.getElementById('deleteAccommodationName').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
}

function confirmDelete() {
    if (accommodationToDelete) {
        fetch(ROOT + '/api/admin/accommodation/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: accommodationToDelete})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Accommodation deleted successfully!', 'success');
                closeDeleteModal();
                // Remove card from grid
                const card = document.querySelector(`[data-id="${accommodationToDelete}"]`);
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
            showNotification('Failed to delete accommodation', 'error');
        });
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    accommodationToDelete = null;
}

// Filters
document.getElementById('typeFilter').addEventListener('change', applyFilters);
document.getElementById('cityFilter').addEventListener('change', applyFilters);
document.getElementById('sortFilter').addEventListener('change', applyFilters);

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

function applyFilters() {
    const type = document.getElementById('typeFilter').value;
    const city = document.getElementById('cityFilter').value;
    const sort = document.getElementById('sortFilter').value;
    const search = document.getElementById('searchInput').value;
    
    let url = ROOT + '/admin/accommodations?';
    if (type) url += 'type=' + encodeURIComponent(type) + '&';
    if (city) url += 'city=' + encodeURIComponent(city) + '&';
    if (search) url += 'search=' + encodeURIComponent(search) + '&';
    url += 'sort=' + sort;
    
    window.location.href = url;
}

function clearFilters() {
    window.location.href = ROOT + '/admin/accommodations';
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
