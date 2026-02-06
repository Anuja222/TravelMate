<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['vehicle']->vehicle_model ?? 'Vehicle Details') ?> - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/transport.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<?php 
$vehicle = $data['vehicle'];
$images = $data['images'] ?? [];
$transporter = $data['transporter'];

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
$mainImageUrl = $mainImage ? ROOT . '/' . $mainImage : ROOT . '/assets/images/default-vehicle.jpg';

$vehicleName = $vehicle->vehicle_model ?? 'Vehicle';
$type = $vehicle->vehicle_type ?? 'car';
$status = $vehicle->status ?? 'active';
?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <?php include __DIR__ . '/../flash_messages.php'; ?>
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= ROOT ?>/admin/transport"><i class="fas fa-car-side"></i> Transport</a>
            <i class="fas fa-chevron-right"></i>
            <span><?= htmlspecialchars($vehicleName) ?></span>
        </div>
        
        <!-- Vehicle Header -->
        <div class="vehicle-header">
            <div class="vehicle-hero" style="background-image: url('<?= $mainImageUrl ?>')">
                <div class="hero-overlay">
                    <div class="hero-content">
                        <div class="hero-badges">
                            <span class="type-badge type-<?= $type ?>">
                                <?= strtoupper(str_replace('_', ' ', $type)) ?>
                            </span>
                            <span class="status-badge status-<?= $status ?>">
                                <?= strtoupper($status) ?>
                            </span>
                        </div>
                        <h1><?= htmlspecialchars($vehicleName) ?></h1>
                        <p class="hero-specs">
                            <?php if ($vehicle->vehicle_year): ?>
                                <?= $vehicle->vehicle_year ?> • 
                            <?php endif; ?>
                            <?= htmlspecialchars($vehicle->vehicle_color ?? 'N/A') ?> • 
                            <?= strtoupper($vehicle->ac_type ?? 'Non-AC') ?>
                        </p>
                        <p class="hero-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= ucfirst(htmlspecialchars($vehicle->working_district ?? 'Location not set')) ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="vehicle-quick-info">
                <div class="quick-stat">
                    <i class="fas fa-tag"></i>
                    <span>LKR <?= number_format($vehicle->price_per_day ?? 0) ?></span>
                    <small>per day</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-road"></i>
                    <span>LKR <?= number_format($vehicle->price_per_km ?? 0) ?></span>
                    <small>per km</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-users"></i>
                    <span><?= $vehicle->passenger_count ?? 2 ?></span>
                    <small>Passengers</small>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-eye"></i>
                    <span><?= number_format($vehicle->views_count ?? 0) ?></span>
                    <small>Views</small>
                </div>
            </div>
        </div>

        <div class="vehicle-content">
            <div class="content-main">
                <!-- Description Section -->
                <section class="info-section">
                    <h2><i class="fas fa-info-circle"></i> Description</h2>
                    <div class="section-content">
                        <?php if ($vehicle->description): ?>
                            <p><?= nl2br(htmlspecialchars($vehicle->description)) ?></p>
                        <?php else: ?>
                            <p class="no-data">No description provided</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Vehicle Details Section -->
                <section class="info-section">
                    <h2><i class="fas fa-car"></i> Vehicle Details</h2>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong><i class="fas fa-car-side"></i> Vehicle Type</strong>
                                <span><?= ucfirst(str_replace('_', ' ', $vehicle->vehicle_type ?? 'N/A')) ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-cogs"></i> Model</strong>
                                <span><?= htmlspecialchars($vehicle->vehicle_model ?? 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-calendar"></i> Year</strong>
                                <span><?= htmlspecialchars($vehicle->vehicle_year ?? 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-palette"></i> Color</strong>
                                <span><?= htmlspecialchars($vehicle->vehicle_color ?? 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-id-card"></i> Vehicle Number</strong>
                                <span><?= htmlspecialchars($vehicle->vehicle_number ?? 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-snowflake"></i> AC Type</strong>
                                <span><?= strtoupper($vehicle->ac_type ?? 'Non-AC') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-users"></i> Passenger Capacity</strong>
                                <span><?= $vehicle->passenger_count ?? 2 ?> passengers</span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-map-marked-alt"></i> Working District</strong>
                                <span><?= ucfirst(htmlspecialchars($vehicle->working_district ?? 'N/A')) ?></span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Pricing Section -->
                <section class="info-section">
                    <h2><i class="fas fa-money-bill-wave"></i> Pricing</h2>
                    <div class="section-content">
                        <div class="pricing-grid">
                            <div class="pricing-item">
                                <div class="pricing-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="pricing-details">
                                    <span class="pricing-label">Per Day</span>
                                    <span class="pricing-value">LKR <?= number_format($vehicle->price_per_day ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="pricing-item">
                                <div class="pricing-icon"><i class="fas fa-road"></i></div>
                                <div class="pricing-details">
                                    <span class="pricing-label">Per Kilometer</span>
                                    <span class="pricing-value">LKR <?= number_format($vehicle->price_per_km ?? 0) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Driver Information Section -->
                <?php if ($vehicle->driver_name || $vehicle->driver_phone): ?>
                <section class="info-section">
                    <h2><i class="fas fa-user-tie"></i> Driver Information</h2>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong><i class="fas fa-user"></i> Driver Name</strong>
                                <span><?= htmlspecialchars($vehicle->driver_name ?? 'Not provided') ?></span>
                            </div>
                            <div class="info-item">
                                <strong><i class="fas fa-phone"></i> Driver Phone</strong>
                                <span><?= htmlspecialchars($vehicle->driver_phone ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Photo Gallery Section -->
                <?php if (!empty($images)): ?>
                <section class="info-section">
                    <h2><i class="fas fa-images"></i> Photo Gallery</h2>
                    <div class="section-content">
                        <div class="gallery-grid">
                            <?php foreach ($images as $image): ?>
                                <div class="gallery-item <?= $image->is_main ? 'main-image' : '' ?>">
                                    <img src="<?= ROOT ?>/<?= $image->image_path ?>" 
                                         alt="Vehicle Image"
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
                <!-- Transporter Info Card -->
                <div class="sidebar-card transporter-card">
                    <h3><i class="fas fa-user-tie"></i> Transporter Information</h3>
                    <div class="transporter-info">
                        <div class="transporter-avatar">
                            <?= strtoupper(substr($transporter->name ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="transporter-details">
                            <strong><?= htmlspecialchars($transporter->name ?? 'Unknown') ?></strong>
                            <span><?= htmlspecialchars($transporter->email ?? 'N/A') ?></span>
                            <?php if ($transporter->phone): ?>
                                <span><i class="fas fa-phone"></i> <?= htmlspecialchars($transporter->phone) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="transporter-meta">
                        <div><strong>User ID:</strong> #<?= $vehicle->user_id ?></div>
                        <div><strong>Role:</strong> <?= ucfirst($transporter->role ?? 'Transporter') ?></div>
                        <div><strong>Total Vehicles:</strong> <?= $transporter->total_vehicles ?? 0 ?></div>
                        <?php if ($transporter->member_since): ?>
                        <div><strong>Member Since:</strong> <?= date('M Y', strtotime($transporter->member_since)) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Listing Stats Card -->
                <div class="sidebar-card stats-card">
                    <h3><i class="fas fa-chart-line"></i> Listing Statistics</h3>
                    <div class="stats-list">
                        <div class="stat-row">
                            <span><i class="fas fa-calendar-plus"></i> Created</span>
                            <strong><?= date('M d, Y', strtotime($vehicle->created_at)) ?></strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="fas fa-edit"></i> Last Updated</span>
                            <strong><?= date('M d, Y', strtotime($vehicle->updated_at ?? $vehicle->created_at)) ?></strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="fas fa-eye"></i> Total Views</span>
                            <strong><?= number_format($vehicle->views_count ?? 0) ?></strong>
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
                    <div class="status-badge-large status-<?= $status ?>">
                        <i class="fas fa-<?= $status === 'active' ? 'check-circle' : ($status === 'booked' ? 'calendar-check' : 'times-circle') ?>"></i>
                        <?= ucfirst($status) ?>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="sidebar-card actions-card">
                    <h3><i class="fas fa-cogs"></i> Actions</h3>
                    <button class="btn-danger btn-full" onclick="deleteVehicle(<?= $vehicle->id ?>, '<?= htmlspecialchars(addslashes($vehicleName)) ?>')">
                        <i class="fas fa-trash"></i> Delete Vehicle
                    </button>
                    <a href="<?= ROOT ?>/admin/transport" class="btn-secondary btn-full">
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

// Gallery functions
function openGallery(imageSrc) {
    document.getElementById('galleryModal').style.display = 'flex';
    document.getElementById('galleryImage').src = imageSrc;
}

function closeGallery() {
    document.getElementById('galleryModal').style.display = 'none';
}

// Delete functions
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
                setTimeout(() => {
                    window.location.href = ROOT + '/admin/transport';
                }, 1500);
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
