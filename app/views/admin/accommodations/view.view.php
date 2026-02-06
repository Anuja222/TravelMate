<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['accommodation']->title ?? 'Accommodation Details') ?> - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/accommodations.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<?php 
$acc = $data['accommodation'];
$images = $data['images'] ?? [];
$provider = $data['provider'];

// Get main image
$mainImage = null;
foreach ($images as $img) {
    if ($img->is_main == 1) {
        $mainImage = $img->image_path;
        break;
    }
}
if (!$mainImage && !empty($images)) {
    $mainImage = $images[0]->image_path;
}
$mainImageUrl = $mainImage ? ROOT . '/' . $mainImage : ROOT . '/assets/images/default-accommodation.jpg';
?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <?php include __DIR__ . '/../flash_messages.php'; ?>
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= ROOT ?>/admin/accommodations"><i class="fas fa-hotel"></i> Accommodations</a>
            <i class="fas fa-chevron-right"></i>
            <span><?= htmlspecialchars($acc->title) ?></span>
        </div>
        
        <!-- Property Header -->
        <div class="property-header">
            <div class="property-hero" style="background-image: url('<?= $mainImageUrl ?>')">
                <div class="hero-overlay">
                    <div class="hero-content">
                        <span class="type-badge type-<?= $acc->property_type ?>">
                            <?= strtoupper($acc->property_type ?? 'HOTEL') ?>
                        </span>
                        <h1><?= htmlspecialchars($acc->title) ?></h1>
                        <p class="hero-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($acc->city ?? 'Location not set') ?>
                            <?php if ($acc->district): ?>, <?= htmlspecialchars($acc->district) ?><?php endif; ?>
                            <?php if ($acc->province): ?>, <?= htmlspecialchars($acc->province) ?><?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="property-quick-info">
                <div class="quick-stat">
                    <i class="fas fa-tag"></i>
                    <span>LKR <?= number_format($acc->price_per_night ?? 0) ?></span>
                    <small>per night</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-door-open"></i>
                    <span><?= $acc->rooms ?? 0 ?></span>
                    <small>Rooms</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-bath"></i>
                    <span><?= $acc->bathrooms ?? 0 ?></span>
                    <small>Bathrooms</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-users"></i>
                    <span><?= $acc->max_guests ?? 0 ?></span>
                    <small>Max Guests</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-eye"></i>
                    <span><?= number_format($acc->views_count ?? 0) ?></span>
                    <small>Views</small>
                </div>
            </div>
        </div>

        <div class="property-content">
            <div class="content-main">
                <!-- Description Section -->
                <section class="info-section">
                    <h2><i class="fas fa-info-circle"></i> Description</h2>
                    <div class="section-content">
                        <?php if ($acc->description): ?>
                            <p><?= nl2br(htmlspecialchars($acc->description)) ?></p>
                        <?php else: ?>
                            <p class="no-data">No description provided</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Location & Contact Section -->
                <section class="info-section">
                    <h2><i class="fas fa-map-marker-alt"></i> Location & Contact</h2>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong><i class="fas fa-home"></i> Address</strong>
                                <span><?= htmlspecialchars($acc->address ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-city"></i> City</strong>
                                <span><?= htmlspecialchars($acc->city ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-map"></i> District</strong>
                                <span><?= htmlspecialchars($acc->district ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-globe-asia"></i> Province</strong>
                                <span><?= htmlspecialchars($acc->province ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-phone"></i> Phone</strong>
                                <span><?= htmlspecialchars($acc->phone ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-envelope"></i> Email</strong>
                                <span><?= htmlspecialchars($acc->email ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Property Details Section -->
                <section class="info-section">
                    <h2><i class="fas fa-hotel"></i> Property Details</h2>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong><i class="fas fa-building"></i> Property Type</strong>
                                <span><?= ucfirst($acc->property_type ?? 'Not specified') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-clock"></i> Check-in Time</strong>
                                <span>
                                    <?php if ($acc->check_in_start): ?>
                                        <?= date('g:i A', strtotime($acc->check_in_start)) ?> 
                                        - <?= date('g:i A', strtotime($acc->check_in_end)) ?>
                                    <?php else: ?>
                                        Not specified
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-sign-out-alt"></i> Check-out Time</strong>
                                <span>
                                    <?= $acc->check_out_time ? date('g:i A', strtotime($acc->check_out_time)) : 'Not specified' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- House Rules Section -->
                <section class="info-section">
                    <h2><i class="fas fa-clipboard-list"></i> House Rules</h2>
                    <div class="section-content">
                        <div class="rules-grid">
                            <div class="rule-item <?= $acc->smoking ? 'allowed' : 'not-allowed' ?>">
                                <i class="fas fa-smoking<?= $acc->smoking ? '' : '-ban' ?>"></i>
                                <span>Smoking <?= $acc->smoking ? 'Allowed' : 'Not Allowed' ?></span>
                            </div>
                            <div class="rule-item <?= $acc->pets ? 'allowed' : 'not-allowed' ?>">
                                <i class="fas fa-paw"></i>
                                <span>Pets <?= $acc->pets ? 'Allowed' : 'Not Allowed' ?></span>
                            </div>
                            <div class="rule-item <?= $acc->parties ? 'allowed' : 'not-allowed' ?>">
                                <i class="fas fa-champagne-glasses"></i>
                                <span>Parties <?= $acc->parties ? 'Allowed' : 'Not Allowed' ?></span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Photo Gallery Section -->
                <?php if (!empty($images)): ?>
                <section class="info-section">
                    <h2><i class="fas fa-images"></i> Photo Gallery</h2>
                    <div class="section-content">
                        <div class="gallery-grid">
                            <?php foreach ($images as $image): ?>
                                <div class="gallery-item <?= $image->is_main ? 'main-image' : '' ?>">
                                    <img src="<?= ROOT ?>/<?= $image->image_path ?>" 
                                         alt="Property Image"
                                         onclick="openGallery('<?= ROOT ?>/<?= $image->image_path ?>')">
                                    <?php if ($image->is_main): ?>
                                        <span class="main-badge">Main Photo</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            </div>

            <div class="content-sidebar">
                <!-- Provider Info Card -->
                <div class="sidebar-card provider-card">
                    <h3><i class="fas fa-user-tie"></i> Provider Information</h3>
                    <div class="provider-info">
                        <div class="provider-avatar">
                            <?= strtoupper(substr($provider->name ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="provider-details">
                            <strong><?= htmlspecialchars($provider->name ?? 'Unknown') ?></strong>
                            <span><?= htmlspecialchars($provider->email ?? 'N/A') ?></span>
                            <?php if ($provider->phone): ?>
                                <span><i class="fas fa-phone"></i> <?= htmlspecialchars($provider->phone) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="provider-meta">
                        <div><strong>User ID:</strong> #<?= $acc->user_id ?></div>
                        <div><strong>Role:</strong> <?= ucfirst($provider->role ?? 'Provider') ?></div>
                    </div>
                </div>

                <!-- Listing Stats Card -->
                <div class="sidebar-card stats-card">
                    <h3><i class="fas fa-chart-line"></i> Listing Statistics</h3>
                    <div class="stats-list">
                        <div class="stat-row">
                            <span><i class="fas fa-calendar-plus"></i> Created</span>
                            <strong><?= date('M d, Y', strtotime($acc->created_at)) ?></strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="fas fa-edit"></i> Last Updated</span>
                            <strong><?= date('M d, Y', strtotime($acc->updated_at ?? $acc->created_at)) ?></strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="fas fa-eye"></i> Total Views</span>
                            <strong><?= number_format($acc->views_count ?? 0) ?></strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="fas fa-images"></i> Photos</span>
                            <strong><?= count($images) ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="sidebar-card status-card">
                    <h3><i class="fas fa-info-circle"></i> Status</h3>
                    <div class="status-badge status-<?= $acc->status ?? 'active' ?>">
                        <i class="fas fa-<?= ($acc->status ?? 'active') === 'active' ? 'check-circle' : 'times-circle' ?>"></i>
                        <?= ucfirst($acc->status ?? 'Active') ?>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="sidebar-card actions-card">
                    <h3><i class="fas fa-cogs"></i> Actions</h3>
                    <button class="btn-danger btn-full" onclick="deleteAccommodation(<?= $acc->id ?>, '<?= htmlspecialchars(addslashes($acc->title)) ?>')">
                        <i class="fas fa-trash"></i> Delete Listing
                    </button>
                    <a href="<?= ROOT ?>/admin/accommodations" class="btn-secondary btn-full">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery Modal -->
<div id="galleryModal" class="modal gallery-modal">
    <span class="close gallery-close" onclick="closeGallery()">&times;</span>
    <img id="galleryImage" class="gallery-modal-content">
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

// Gallery functions
function openGallery(imageSrc) {
    document.getElementById('galleryModal').style.display = 'flex';
    document.getElementById('galleryImage').src = imageSrc;
}

function closeGallery() {
    document.getElementById('galleryModal').style.display = 'none';
}

// Delete functions
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
                setTimeout(() => {
                    window.location.href = ROOT + '/admin/accommodations';
                }, 1500);
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

// Show notification
function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = 'notification ' + type + ' show';
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Close modals on outside click
window.onclick = function(event) {
    const galleryModal = document.getElementById('galleryModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === galleryModal) {
        closeGallery();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGallery();
        closeDeleteModal();
    }
});
</script>

</body>
</html>
