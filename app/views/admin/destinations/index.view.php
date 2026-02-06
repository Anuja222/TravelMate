<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination Categories - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/destinations.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../../traveller/header.view.php'; ?>

<div class="page-container">
    <?php include __DIR__ . '/../sidebar.view.php'; ?>
    
    <div class="content">
        <?php include __DIR__ . '/../flash_messages.php'; ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1><i class="fas fa-map-marked-alt"></i> Destination Categories</h1>
                <p class="subtitle">Manage destination categories and their places</p>
            </div>
            <button class="btn-primary" onclick="showAddCategoryModal()">
                <i class="fas fa-plus"></i> Add New Category
            </button>
        </div>
        
        <!-- Search Bar -->
        <div class="search-filter-bar">
            <form method="GET" action="<?= ROOT ?>/admin/destinations" class="search-form">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" id="searchInput" 
                           placeholder="Search categories by name or description..." 
                           value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                    <?php if (!empty($data['search'])): ?>
                        <a href="<?= ROOT ?>/admin/destinations" class="clear-search" title="Clear search">
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

        <!-- Category Cards Grid (2 rows x 4 columns) -->
        <div class="category-cards-grid">
            <?php if (!empty($data['categories'])): ?>
                <?php foreach ($data['categories'] as $category): ?>
                    <?php 
                    $placeCount = $category->place_count ?? 0;
                    $activeCount = $category->active_count ?? 0;
                    $totalViews = $category->total_views ?? 0;
                    $categoryName = $category->name ?? $category->title;
                    $categoryDesc = $category->description ?? 'No description available';
                    $categoryImage = $category->image ?? '/assets/images/default-destination.jpg';
                    ?>
                    <div class="category-card">
                        <div class="category-card-image" onclick="viewCategory(<?= $category->id ?>)">
                            <img src="<?= ROOT . $categoryImage ?>" 
                                 alt="<?= htmlspecialchars($categoryName) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-destination.jpg'">
                            <div class="category-card-overlay">
                                <button class="overlay-view-btn" onclick="viewCategory(<?= $category->id ?>)">
                                    <i class="fas fa-eye"></i> View Places
                                </button>
                            </div>
                        </div>
                        
                        <div class="category-card-content">
                            <h3><?= htmlspecialchars($categoryName) ?></h3>
                            <p class="category-description">
                                <?= htmlspecialchars(substr($categoryDesc, 0, 100)) ?><?= strlen($categoryDesc) > 100 ? '...' : '' ?>
                            </p>
                            
                            <div class="category-stats-row">
                                <div class="stat-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= $placeCount ?> Places</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span><?= $activeCount ?> Active</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-eye"></i>
                                    <span><?= number_format($totalViews) ?> Views</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-card-actions" onclick="event.stopPropagation()">
                            <button class="btn-icon btn-view" 
                                    onclick="viewCategory(<?= $category->id ?>)" 
                                    title="View Places">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon btn-edit" 
                                    onclick="editCategory(<?= $category->id ?>)" 
                                    title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" 
                                    onclick="deleteCategory(<?= $category->id ?>, '<?= htmlspecialchars($categoryName) ?>')" 
                                    title="Delete Category">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state full-width">
                    <i class="fas fa-map-marked-alt"></i>
                    <?php if (!empty($data['search'])): ?>
                        <h3>No Results Found</h3>
                        <p>No categories match your search for "<?= htmlspecialchars($data['search']) ?>"</p>
                        <a href="<?= ROOT ?>/admin/destinations" class="btn-primary">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    <?php else: ?>
                        <h3>No Categories Found</h3>
                        <p>Start by adding your first destination category</p>
                        <button class="btn-primary" onclick="showAddCategoryModal()">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($data['pagination']) && $data['pagination']['pages'] > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination">
                    <?php 
                    $pagination = $data['pagination'];
                    $currentPage = $pagination['current_page'];
                    $totalPages = $pagination['pages'];
                    $searchParam = !empty($data['search']) ? '&search=' . urlencode($data['search']) : '';
                    ?>
                    
                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= ROOT ?>/admin/destinations?page=<?= $currentPage - 1 ?><?= $searchParam ?>" class="page-link">
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
                        <a href="<?= ROOT ?>/admin/destinations?page=1<?= $searchParam ?>" class="page-link">1</a>
                        <?php if ($startPage > 2): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="page-link active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= ROOT ?>/admin/destinations?page=<?= $i ?><?= $searchParam ?>" class="page-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                        <a href="<?= ROOT ?>/admin/destinations?page=<?= $totalPages ?><?= $searchParam ?>" class="page-link"><?= $totalPages ?></a>
                    <?php endif; ?>
                    
                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= ROOT ?>/admin/destinations?page=<?= $currentPage + 1 ?><?= $searchParam ?>" class="page-link">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="page-link disabled">Next <i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
                <div class="pagination-info">
                    Showing page <?= $currentPage ?> of <?= $totalPages ?> (<?= $pagination['total'] ?> total categories)
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="categoryModalTitle"><i class="fas fa-plus-circle"></i> Add New Category</h3>
            <span class="close" onclick="closeCategoryModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="categoryForm" enctype="multipart/form-data">
                <input type="hidden" id="categoryId" name="id">
                
                <div class="form-group">
                    <label for="categoryName"><i class="fas fa-heading"></i> Category Name *</label>
                    <input type="text" id="categoryName" name="name" 
                           placeholder="e.g., Beach Side, Cultural, Mountains" required>
                </div>
                
                <div class="form-group">
                    <label for="categoryDescription"><i class="fas fa-align-left"></i> Description *</label>
                    <textarea id="categoryDescription" name="description" rows="4" 
                              placeholder="Brief description of this destination category..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Category Image</label>
                    <div class="image-upload-area" id="categoryImageUploadArea">
                        <div class="upload-placeholder" id="categoryUploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click or drag image here</p>
                            <span>JPG, PNG (max 5MB)</span>
                        </div>
                        <div class="image-preview" id="categoryImagePreview" style="display: none;">
                            <img id="categoryPreviewImg" src="" alt="Preview">
                            <button type="button" class="btn-remove-image" onclick="removeCategoryImage()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="file" id="categoryImage" name="image" accept="image/*" style="display: none;">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeCategoryModal()">Cancel</button>
            <button class="btn-primary" onclick="saveCategory()">
                <i class="fas fa-save"></i> Save Category
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
            <p>Are you sure you want to delete "<strong id="deleteCategoryName"></strong>"?</p>
            <p class="warning-text"><i class="fas fa-warning"></i> Categories with places cannot be deleted. You must delete all places first.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" onclick="confirmDeleteCategory()">
                <i class="fas fa-trash"></i> Delete Category
            </button>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="notification"></div>

<script>
const ROOT = '<?= ROOT ?>';
let categoryToDelete = null;
let isEditMode = false;

// View category places
function viewCategory(categoryId) {
    window.location.href = ROOT + '/admin/destinations/category?id=' + categoryId;
}

// Show add category modal
function showAddCategoryModal() {
    isEditMode = false;
    document.getElementById('categoryModalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryImagePreview').style.display = 'none';
    document.getElementById('categoryUploadPlaceholder').style.display = 'flex';
    document.getElementById('categoryModal').style.display = 'flex';
}

// Edit category
function editCategory(categoryId) {
    isEditMode = true;
    document.getElementById('categoryModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Category';
    
    fetch(ROOT + '/api/admin/destination/category/get?id=' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                document.getElementById('categoryId').value = category.id;
                document.getElementById('categoryName').value = category.name || category.title;
                document.getElementById('categoryDescription').value = category.description || '';
                
                if (category.image) {
                    document.getElementById('categoryPreviewImg').src = ROOT + category.image;
                    document.getElementById('categoryImagePreview').style.display = 'block';
                    document.getElementById('categoryUploadPlaceholder').style.display = 'none';
                } else {
                    document.getElementById('categoryImagePreview').style.display = 'none';
                    document.getElementById('categoryUploadPlaceholder').style.display = 'flex';
                }
                
                document.getElementById('categoryModal').style.display = 'flex';
            } else {
                showNotification('Failed to load category details', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load category details', 'error');
        });
}

// Close category modal
function closeCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
    isEditMode = false;
}

// Save category
function saveCategory() {
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);
    
    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();
    
    if (!name) {
        showNotification('Category name is required', 'error');
        return;
    }
    
    if (!description) {
        showNotification('Description is required', 'error');
        return;
    }
    
    const url = isEditMode ? 
        ROOT + '/api/admin/destination/category/update' : 
        ROOT + '/api/admin/destination/category/add';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(isEditMode ? 'Category updated successfully!' : 'Category created successfully!', 'success');
            closeCategoryModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to save category', 'error');
    });
}

// Delete category
function deleteCategory(categoryId, categoryName) {
    categoryToDelete = categoryId;
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteModal').style.display = 'flex';
}

function confirmDeleteCategory() {
    if (categoryToDelete) {
        fetch(ROOT + '/api/admin/destination/category/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: categoryToDelete})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Category deleted successfully!', 'success');
                closeDeleteModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete category', 'error');
        });
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    categoryToDelete = null;
}

// Image upload handling
document.getElementById('categoryUploadPlaceholder').addEventListener('click', function() {
    document.getElementById('categoryImage').click();
});

document.getElementById('categoryImageUploadArea').addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

document.getElementById('categoryImageUploadArea').addEventListener('dragleave', function() {
    this.classList.remove('dragover');
});

document.getElementById('categoryImageUploadArea').addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        handleCategoryImageFile(file);
    }
});

document.getElementById('categoryImage').addEventListener('change', function() {
    if (this.files[0]) {
        handleCategoryImageFile(this.files[0]);
    }
});

function handleCategoryImageFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('categoryPreviewImg').src = e.target.result;
        document.getElementById('categoryImagePreview').style.display = 'block';
        document.getElementById('categoryUploadPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    
    // Update the file input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    document.getElementById('categoryImage').files = dataTransfer.files;
}

function removeCategoryImage() {
    document.getElementById('categoryImage').value = '';
    document.getElementById('categoryPreviewImg').src = '';
    document.getElementById('categoryImagePreview').style.display = 'none';
    document.getElementById('categoryUploadPlaceholder').style.display = 'flex';
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
    const categoryModal = document.getElementById('categoryModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === categoryModal) {
        closeCategoryModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>

</body>
</html>
