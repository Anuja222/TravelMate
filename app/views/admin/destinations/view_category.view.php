<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['category']->name ?? $data['category']->title) ?> - Places</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/destinations.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<?php 
$category = $data['category'];
$places = $data['places'];
$categoryName = $category->name ?? $category->title;
$categoryDesc = $category->description ?? '';
$categoryImage = $category->image ?? '/assets/images/default-destination.jpg';
?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <?php include __DIR__ . '/../flash_messages.php'; ?>
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= ROOT ?>/admin/destinations">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>

        <!-- Category Header -->
        <div class="category-header-banner" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('<?= ROOT . $categoryImage ?>');">
            <div class="category-header-content">
                <h1><?= htmlspecialchars($categoryName) ?></h1>
                <p><?= htmlspecialchars($categoryDesc) ?></p>
                <div class="category-meta">
                    <span><i class="fas fa-map-marker-alt"></i> <?= $data['pagination']['total'] ?? count($places) ?> Places</span>
                    <span><i class="fas fa-calendar"></i> Created: <?= date('M d, Y', strtotime($category->created_at)) ?></span>
                </div>
            </div>
            <button class="btn-primary btn-add-place" onclick="showAddPlaceModal()">
                <i class="fas fa-plus"></i> Add New Place
            </button>
        </div>
        
        <!-- Search Bar for Places -->
        <div class="search-filter-bar">
            <form method="GET" action="<?= ROOT ?>/admin/destinations/category" class="search-form">
                <input type="hidden" name="id" value="<?= $category->id ?>">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" id="searchInput" 
                           placeholder="Search places by name or description..." 
                           value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                    <?php if (!empty($data['search'])): ?>
                        <a href="<?= ROOT ?>/admin/destinations/category?id=<?= $category->id ?>" class="clear-search" title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
            <?php if (!empty($data['search'])): ?>
                <div class="search-results-info">
                    Found <strong><?= $data['pagination']['total'] ?? 0 ?></strong> result(s) for "<strong><?= htmlspecialchars($data['search']) ?></strong>"
                </div>
            <?php endif; ?>
        </div>

        <!-- Places Grid -->
        <div class="places-grid">
            <?php if (!empty($places)): ?>
                <?php foreach ($places as $place): ?>
                    <?php 
                    $placeName = $place->name ?? $place->title;
                    $placeDesc = $place->description ?? '';
                    $placeImage = $place->image ?? '/assets/images/default-place.jpg';
                    $placeViews = $place->views ?? 0;
                    $placeStatus = $place->status ?? 'active';
                    ?>
                    <div class="place-card <?= $placeStatus === 'inactive' ? 'inactive' : '' ?>">
                        <div class="place-image">
                            <img src="<?= ROOT . $placeImage ?>" 
                                 alt="<?= htmlspecialchars($placeName) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-place.jpg'">
                            <?php if ($placeStatus === 'inactive'): ?>
                                <span class="status-badge inactive">Inactive</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="place-content">
                            <h3><?= htmlspecialchars($placeName) ?></h3>
                            <p><?= htmlspecialchars(substr($placeDesc, 0, 120)) ?><?= strlen($placeDesc) > 120 ? '...' : '' ?></p>
                            
                            <div class="place-meta">
                                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($place->created_at)) ?></span>
                                <span><i class="fas fa-eye"></i> <?= number_format($placeViews) ?> views</span>
                            </div>
                            
                            <div class="place-actions">
                                <button class="btn-icon btn-edit" 
                                        onclick="editPlace(<?= $place->id ?>)" 
                                        title="Edit Place">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-delete" 
                                        onclick="deletePlace(<?= $place->id ?>, '<?= htmlspecialchars(addslashes($placeName)) ?>')" 
                                        title="Delete Place">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state full-width">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php if (!empty($data['search'])): ?>
                        <h3>No Results Found</h3>
                        <p>No places match your search for "<?= htmlspecialchars($data['search']) ?>"</p>
                        <a href="<?= ROOT ?>/admin/destinations/category?id=<?= $category->id ?>" class="btn-primary">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    <?php else: ?>
                        <h3>No Places Yet</h3>
                        <p>Start adding places to "<?= htmlspecialchars($categoryName) ?>"</p>
                        <button class="btn-primary" onclick="showAddPlaceModal()">
                            <i class="fas fa-plus"></i> Add First Place
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination for Places -->
        <?php if (isset($data['pagination']) && $data['pagination']['pages'] > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination">
                    <?php 
                    $pagination = $data['pagination'];
                    $currentPage = $pagination['current_page'];
                    $totalPages = $pagination['pages'];
                    $baseUrl = ROOT . '/admin/destinations/category?id=' . $category->id;
                    $searchParam = !empty($data['search']) ? '&search=' . urlencode($data['search']) : '';
                    ?>
                    
                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?><?= $searchParam ?>" class="page-link">
                            <i class="fas fa-chevron-left"></i> Prev
                        </a>
                    <?php else: ?>
                        <span class="page-link disabled"><i class="fas fa-chevron-left"></i> Prev</span>
                    <?php endif; ?>
                    
                    <!-- Page Numbers -->
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    
                    if ($startPage > 1): ?>
                        <a href="<?= $baseUrl ?>&page=1<?= $searchParam ?>" class="page-link">1</a>
                        <?php if ($startPage > 2): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="page-link active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>&page=<?= $i ?><?= $searchParam ?>" class="page-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                        <a href="<?= $baseUrl ?>&page=<?= $totalPages ?><?= $searchParam ?>" class="page-link"><?= $totalPages ?></a>
                    <?php endif; ?>
                    
                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?><?= $searchParam ?>" class="page-link">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="page-link disabled">Next <i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
                <div class="pagination-info">
                    Showing page <?= $currentPage ?> of <?= $totalPages ?> (<?= $pagination['total'] ?> total places)
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Place Modal -->
<div id="placeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="placeModalTitle"><i class="fas fa-plus-circle"></i> Add New Place</h3>
            <span class="close" onclick="closePlaceModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="placeForm" enctype="multipart/form-data">
                <input type="hidden" id="placeId" name="id">
                <input type="hidden" id="destinationId" name="destination_id" value="<?= $category->id ?>">
                
                <div class="form-group">
                    <label for="placeName"><i class="fas fa-heading"></i> Place Name *</label>
                    <input type="text" id="placeName" name="name" 
                           placeholder="e.g., Unawatuna Beach, Sigiriya Rock" required>
                </div>
                
                <div class="form-group">
                    <label for="placeDescription"><i class="fas fa-align-left"></i> Description</label>
                    <textarea id="placeDescription" name="description" rows="4" 
                              placeholder="Describe this place - what makes it special, what visitors can see and do here..."></textarea>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Place Image</label>
                    <div class="image-upload-area" id="placeImageUploadArea">
                        <div class="upload-placeholder" id="placeUploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click or drag image here</p>
                            <span>JPG, PNG (max 5MB)</span>
                        </div>
                        <div class="image-preview" id="placeImagePreview" style="display: none;">
                            <img id="placePreviewImg" src="" alt="Preview">
                            <button type="button" class="btn-remove-image" onclick="removePlaceImage()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="file" id="placeImage" name="image" accept="image/*" style="display: none;">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closePlaceModal()">Cancel</button>
            <button class="btn-primary" onclick="savePlace()">
                <i class="fas fa-save"></i> Save Place
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header modal-header-danger">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete "<strong id="deletePlaceName"></strong>"?</p>
            <p class="warning-text"><i class="fas fa-warning"></i> This action cannot be undone!</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" onclick="confirmDeletePlace()">
                <i class="fas fa-trash"></i> Delete Place
            </button>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="notification"></div>

<script>
const ROOT = '<?= ROOT ?>';
const categoryId = <?= $category->id ?>;
let placeToDelete = null;
let isEditMode = false;

// Show add place modal
function showAddPlaceModal() {
    isEditMode = false;
    document.getElementById('placeModalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Place to <?= htmlspecialchars($categoryName) ?>';
    document.getElementById('placeForm').reset();
    document.getElementById('placeId').value = '';
    document.getElementById('destinationId').value = categoryId;
    document.getElementById('placeImagePreview').style.display = 'none';
    document.getElementById('placeUploadPlaceholder').style.display = 'flex';
    document.getElementById('placeModal').style.display = 'flex';
}

// Edit place
function editPlace(placeId) {
    isEditMode = true;
    document.getElementById('placeModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Place';
    
    fetch(ROOT + '/api/admin/destination/place/get?id=' + placeId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const place = data.data;
                document.getElementById('placeId').value = place.id;
                document.getElementById('placeName').value = place.name || place.title;
                document.getElementById('placeDescription').value = place.description || '';
                
                if (place.image) {
                    document.getElementById('placePreviewImg').src = ROOT + place.image;
                    document.getElementById('placeImagePreview').style.display = 'block';
                    document.getElementById('placeUploadPlaceholder').style.display = 'none';
                } else {
                    document.getElementById('placeImagePreview').style.display = 'none';
                    document.getElementById('placeUploadPlaceholder').style.display = 'flex';
                }
                
                document.getElementById('placeModal').style.display = 'flex';
            } else {
                showNotification('Failed to load place details', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load place details', 'error');
        });
}

// Save place
function savePlace() {
    const form = document.getElementById('placeForm');
    const formData = new FormData(form);
    
    const name = document.getElementById('placeName').value.trim();
    
    if (!name) {
        showNotification('Place name is required', 'error');
        return;
    }
    
    const url = isEditMode ? 
        ROOT + '/api/admin/destination/place/update' : 
        ROOT + '/api/admin/destination/place/add';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(isEditMode ? 'Place updated successfully!' : 'Place added successfully!', 'success');
            closePlaceModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to save place', 'error');
    });
}

// Delete place
function deletePlace(placeId, placeName) {
    placeToDelete = placeId;
    document.getElementById('deletePlaceName').textContent = placeName;
    document.getElementById('deleteModal').style.display = 'flex';
}

function confirmDeletePlace() {
    if (placeToDelete) {
        fetch(ROOT + '/api/admin/destination/place/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: placeToDelete})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Place deleted successfully!', 'success');
                closeDeleteModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete place', 'error');
        });
    }
}

// Close modals
function closePlaceModal() {
    document.getElementById('placeModal').style.display = 'none';
    isEditMode = false;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    placeToDelete = null;
}

// Image upload handling
document.getElementById('placeUploadPlaceholder').addEventListener('click', function() {
    document.getElementById('placeImage').click();
});

document.getElementById('placeImageUploadArea').addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

document.getElementById('placeImageUploadArea').addEventListener('dragleave', function() {
    this.classList.remove('dragover');
});

document.getElementById('placeImageUploadArea').addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        handlePlaceImageFile(file);
    }
});

document.getElementById('placeImage').addEventListener('change', function() {
    if (this.files[0]) {
        handlePlaceImageFile(this.files[0]);
    }
});

function handlePlaceImageFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('placePreviewImg').src = e.target.result;
        document.getElementById('placeImagePreview').style.display = 'block';
        document.getElementById('placeUploadPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    
    // Update the file input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    document.getElementById('placeImage').files = dataTransfer.files;
}

function removePlaceImage() {
    document.getElementById('placeImage').value = '';
    document.getElementById('placePreviewImg').src = '';
    document.getElementById('placeImagePreview').style.display = 'none';
    document.getElementById('placeUploadPlaceholder').style.display = 'flex';
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
    const placeModal = document.getElementById('placeModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === placeModal) {
        closePlaceModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>

</body>
</html>
