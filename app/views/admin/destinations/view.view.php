<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($destination->title) ?> - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/destinations.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <!-- Back Button -->
        <a href="<?= ROOT ?>/admin/destinations?category=<?= $destination->category ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to <?= ucfirst($destination->category) ?> Side
        </a>

        <!-- Destination Header -->
        <div class="destination-detail-header">
            <div class="detail-image">
                <img src="<?= ROOT . ($destination->image ?? '/assets/images/default-destination.jpg') ?>" 
                     alt="<?= htmlspecialchars($destination->title) ?>"
                     onerror="this.src='<?= ROOT ?>/assets/images/default-destination.jpg'">
                
                <div class="detail-badges">
                    <?php if ($destination->featured): ?>
                        <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                    <?php endif; ?>
                    <span class="badge badge-category">
                        <?php if ($destination->category == 'beach'): ?>
                            <i class="fas fa-umbrella-beach"></i> Beach Side
                        <?php elseif ($destination->category == 'country'): ?>
                            <i class="fas fa-tree"></i> Country Side
                        <?php else: ?>
                            <i class="fas fa-mountain"></i> Hill Side
                        <?php endif; ?>
                    </span>
                    <span class="badge badge-<?= $destination->status ?>">
                        <?= ucfirst($destination->status) ?>
                    </span>
                </div>
            </div>

            <div class="detail-info">
                <h1><?= htmlspecialchars($destination->title) ?></h1>
                
                <?php if ($destination->location): ?>
                    <p class="location">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($destination->location) ?>
                    </p>
                <?php endif; ?>

                <p class="description"><?= nl2br(htmlspecialchars($destination->description)) ?></p>

                <div class="detail-stats">
                    <div class="stat">
                        <i class="fas fa-eye"></i>
                        <span><?= number_format($destination->views_count ?? 0) ?></span>
                        <label>Views</label>
                    </div>
                    <div class="stat">
                        <i class="fas fa-layer-group"></i>
                        <span><?= count($places) ?></span>
                        <label>Places</label>
                    </div>
                    <div class="stat">
                        <i class="fas fa-calendar"></i>
                        <span><?= date('M d, Y', strtotime($destination->created_at)) ?></span>
                        <label>Created</label>
                    </div>
                </div>

                <div class="detail-actions">
                    <button class="btn btn-primary" onclick="editDestination(<?= $destination->id ?>)">
                        <i class="fas fa-edit"></i> Edit Destination
                    </button>
                    <button class="btn btn-secondary" onclick="toggleFeatured(<?= $destination->id ?>)">
                        <i class="fas fa-star"></i> <?= $destination->featured ? 'Remove Featured' : 'Make Featured' ?>
                    </button>
                    <button class="btn btn-danger" onclick="deleteDestination(<?= $destination->id ?>)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Places Section -->
        <div class="places-section">
            <div class="section-header">
                <h2><i class="fas fa-layer-group"></i> Places in <?= htmlspecialchars($destination->title) ?></h2>
                <button class="btn btn-primary" onclick="showAddPlaceModal()">
                    <i class="fas fa-plus"></i> Add Place
                </button>
            </div>

            <div class="places-grid">
                <?php if ($places && count($places) > 0): ?>
                    <?php foreach ($places as $place): ?>
                        <div class="place-card" data-id="<?= $place->id ?>">
                            <div class="place-image">
                                <img src="<?= ROOT . ($place->image ?? '/assets/images/default-place.jpg') ?>" 
                                     alt="<?= htmlspecialchars($place->title) ?>"
                                     onerror="this.src='<?= ROOT ?>/assets/images/default-destination.jpg'">
                            </div>
                            <div class="place-content">
                                <h4><?= htmlspecialchars($place->title) ?></h4>
                                <p><?= htmlspecialchars(substr($place->description ?? '', 0, 80)) ?>...</p>
                                <div class="place-actions">
                                    <button class="btn-icon" onclick="editPlace(<?= $place->id ?>)" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" onclick="deletePlace(<?= $place->id ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-places">
                        <i class="fas fa-map-pin"></i>
                        <h4>No Places Added Yet</h4>
                        <p>Add specific locations within this destination</p>
                        <button class="btn btn-primary" onclick="showAddPlaceModal()">
                            <i class="fas fa-plus"></i> Add First Place
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Destination Modal -->
<div id="destinationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"><i class="fas fa-edit"></i> Edit Destination</h2>
            <span class="close" onclick="closeModal('destinationModal')">&times;</span>
        </div>
        
        <form id="destinationForm">
            <input type="hidden" id="destinationId" name="id" value="<?= $destination->id ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="title"><i class="fas fa-heading"></i> Destination Name *</label>
                    <input type="text" id="title" name="title" required value="<?= htmlspecialchars($destination->title) ?>">
                </div>

                <div class="form-group">
                    <label for="category"><i class="fas fa-folder"></i> Category *</label>
                    <select id="category" name="category" required>
                        <option value="beach" <?= $destination->category == 'beach' ? 'selected' : '' ?>>🏖️ Beach Side</option>
                        <option value="country" <?= $destination->category == 'country' ? 'selected' : '' ?>>🌳 Country Side</option>
                        <option value="hill" <?= $destination->category == 'hill' ? 'selected' : '' ?>>⛰️ Hill Side</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Description *</label>
                <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($destination->description) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                    <input type="text" id="location" name="location" value="<?= htmlspecialchars($destination->location ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="status"><i class="fas fa-toggle-on"></i> Status</label>
                    <select id="status" name="status">
                        <option value="active" <?= $destination->status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $destination->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-star"></i> Featured</label>
                <label class="switch">
                    <input type="checkbox" id="featured" name="featured" value="1" <?= $destination->featured ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </div>

            <input type="hidden" id="imageUrl" name="image" value="<?= $destination->image ?>">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('destinationModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Add/Edit Place Modal -->
<div id="placeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="placeModalTitle"><i class="fas fa-plus-circle"></i> Add New Place</h2>
            <span class="close" onclick="closeModal('placeModal')">&times;</span>
        </div>
        
        <form id="placeForm">
            <input type="hidden" id="placeId" name="id">
            <input type="hidden" name="destination_id" value="<?= $destination->id ?>">
            
            <div class="form-group">
                <label for="placeTitle"><i class="fas fa-heading"></i> Place Name *</label>
                <input type="text" id="placeTitle" name="title" required placeholder="e.g., Secret Beach">
            </div>

            <div class="form-group">
                <label for="placeDescription"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="placeDescription" name="description" rows="4" placeholder="Describe this place..."></textarea>
            </div>

            <div class="form-group">
                <label for="placeImageUpload"><i class="fas fa-image"></i> Image</label>
                <div class="image-upload-area" id="placeImageUploadArea">
                    <input type="file" id="placeImageUpload" name="image" accept="image/*" hidden>
                    <div class="upload-placeholder" onclick="document.getElementById('placeImageUpload').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload image</p>
                    </div>
                    <div class="image-preview" id="placeImagePreview" style="display: none;">
                        <img id="placePreviewImg" src="" alt="Preview">
                        <button type="button" class="btn-remove-image" onclick="removePlaceImage()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="placeImageUrl" name="image">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('placeModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Place</button>
            </div>
        </form>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="notification"></div>

<script>
const ROOT = '<?= ROOT ?>';
const destinationId = <?= $destination->id ?>;
const destinationCategory = '<?= $destination->category ?>';

// Edit destination
function editDestination() {
    document.getElementById('destinationModal').style.display = 'flex';
}

// Delete destination
function deleteDestination(id) {
    if (!confirm('Are you sure you want to delete this destination and all its places?')) {
        return;
    }

    fetch(ROOT + '/api/admin/destination/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Destination deleted successfully', 'success');
            setTimeout(() => {
                window.location.href = ROOT + '/admin/destinations?category=' + destinationCategory;
            }, 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred', 'error'));
}

// Toggle featured
function toggleFeatured(id) {
    fetch(ROOT + '/api/admin/destination/toggle-featured', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Featured status updated', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    });
}

// Submit destination form
document.getElementById('destinationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const jsonData = {
        id: document.getElementById('destinationId').value,
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        category: document.getElementById('category').value,
        location: document.getElementById('location').value,
        status: document.getElementById('status').value,
        featured: document.getElementById('featured').checked ? 1 : 0,
        image: document.getElementById('imageUrl').value
    };

    fetch(ROOT + '/api/admin/destination/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('destinationModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred', 'error'));
});

// ==================== PLACE MANAGEMENT ====================

// Show add place modal
function showAddPlaceModal() {
    document.getElementById('placeModalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Place';
    document.getElementById('placeForm').reset();
    document.getElementById('placeId').value = '';
    document.getElementById('placeImagePreview').style.display = 'none';
    document.querySelector('#placeImageUploadArea .upload-placeholder').style.display = 'flex';
    document.getElementById('placeImageUrl').value = '';
    document.getElementById('placeModal').style.display = 'flex';
}

// Edit place
function editPlace(id) {
    fetch(ROOT + '/api/admin/destination/place/get?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const place = data.place;
                document.getElementById('placeModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Place';
                document.getElementById('placeId').value = place.id;
                document.getElementById('placeTitle').value = place.title;
                document.getElementById('placeDescription').value = place.description || '';
                
                if (place.image) {
                    document.getElementById('placePreviewImg').src = ROOT + place.image;
                    document.getElementById('placeImagePreview').style.display = 'block';
                    document.querySelector('#placeImageUploadArea .upload-placeholder').style.display = 'none';
                    document.getElementById('placeImageUrl').value = place.image;
                }
                
                document.getElementById('placeModal').style.display = 'flex';
            } else {
                showNotification('Failed to load place', 'error');
            }
        });
}

// Delete place
function deletePlace(id) {
    if (!confirm('Are you sure you want to delete this place?')) {
        return;
    }

    fetch(ROOT + '/api/admin/destination/place/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Place deleted successfully', 'success');
            document.querySelector(`.place-card[data-id="${id}"]`).remove();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    });
}

// Submit place form
document.getElementById('placeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const placeId = document.getElementById('placeId').value;
    const url = placeId ? 
        ROOT + '/api/admin/destination/place/update' : 
        ROOT + '/api/admin/destination/place/add';
    
    const jsonData = {
        id: placeId || undefined,
        destination_id: destinationId,
        title: document.getElementById('placeTitle').value,
        description: document.getElementById('placeDescription').value,
        image: document.getElementById('placeImageUrl').value
    };

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('placeModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    });
});

// Place image upload
document.getElementById('placeImageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('placePreviewImg').src = e.target.result;
        document.getElementById('placeImagePreview').style.display = 'block';
        document.querySelector('#placeImageUploadArea .upload-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);

    const formData = new FormData();
    formData.append('image', file);

    fetch(ROOT + '/api/admin/destination/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('placeImageUrl').value = data.image_url;
        }
    });
});

function removePlaceImage() {
    document.getElementById('placePreviewImg').src = '';
    document.getElementById('placeImagePreview').style.display = 'none';
    document.querySelector('#placeImageUploadArea .upload-placeholder').style.display = 'flex';
    document.getElementById('placeImageUrl').value = '';
}

// Utility functions
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = 'notification ' + type + ' show';
    setTimeout(() => notification.classList.remove('show'), 3000);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

</body>
</html>
