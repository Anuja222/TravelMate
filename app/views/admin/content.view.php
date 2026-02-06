<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/content.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include __DIR__ . '/../traveller/header.view.php'; ?>

<div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
        <?php include __DIR__ . '/flash_messages.php'; ?>
        <div class="page-title">
            <h1>Blog Management</h1>
            <div class="header-actions">
                <a href="blogs-all" class="btn-all-blogs">All Blogs</a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span>📄</span>
                </div>
                <div class="stat-details">
                    <h3><?= isset($stats) ? $stats->total : 0 ?></h3>
                    <p>Total Blogs</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pending">
                    <span>⏳</span>
                </div>
                <div class="stat-details">
                    <h3><?= isset($stats) ? $stats->pending : 0 ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon approved">
                    <span>✓</span>
                </div>
                <div class="stat-details">
                    <h3><?= isset($stats) ? $stats->approved : 0 ?></h3>
                    <p>Approved</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <span>✗</span>
                </div>
                <div class="stat-details">
                    <h3><?= isset($stats) ? $stats->rejected : 0 ?></h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions" id="bulkActions">
            <span><span id="selectedCount">0</span> blogs selected</span>
            <div class="bulk-btns">
                <button class="btn-bulk-approve" onclick="bulkApprove()">Approve All</button>
                <button class="btn-bulk-reject" onclick="showBulkRejectModal()">Reject All</button>
                <button class="btn-cancel" onclick="cancelSelection()">Cancel</button>
            </div>
        </div>

        <!-- Blog Cards -->
        <div class="blogs-list">
            <?php if (isset($blogs) && is_array($blogs) && count($blogs) > 0): ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card" data-blog-id="<?= $blog->id ?>">
                        <div class="blog-checkbox">
                            <input type="checkbox" value="<?= $blog->id ?>" onchange="updateBulkSelection()">
                        </div>
                        
                        <div class="blog-image">
                            <img src="<?= !empty($blog->featured_image) ? htmlspecialchars($blog->featured_image) : ROOT . '/assets/images/default-blog.jpg' ?>" 
                                 alt="<?= htmlspecialchars($blog->title) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-blog.jpg'">
                        </div>
                        
                        <div class="blog-content">
                            <div class="blog-header">
                                <h3 class="blog-title"><?= htmlspecialchars($blog->title) ?></h3>
                                <span class="blog-status status-<?= $blog->status ?>"><?= ucfirst($blog->status) ?></span>
                            </div>
                            
                            <div class="blog-meta">
                                <span class="meta-item"><span class="meta-icon">👤</span> <?= htmlspecialchars($blog->author_name ?? 'Unknown') ?></span>
                                <span class="meta-item"><span class="meta-icon">📅</span> <?= date('M d, Y', strtotime($blog->created_at)) ?></span>
                                <span class="meta-item"><span class="meta-icon">👁</span> <?= $blog->views_count ?? 0 ?> views</span>
                                <?php if (!empty($blog->location)): ?>
                                    <span class="meta-item"><span class="meta-icon">📍</span> <?= htmlspecialchars($blog->location) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="blog-excerpt">
                                <?= htmlspecialchars(substr($blog->excerpt ?? strip_tags($blog->content), 0, 150)) ?>...
                            </p>
                            
                            <div class="blog-actions">
                                <a href="<?= ROOT ?>/admin/blog/view?id=<?= $blog->id ?>" class="btn-action btn-view">View</a>
                                
                                <?php if ($blog->status == 'pending'): ?>
                                    <button class="btn-action btn-approve" onclick="approveBlog(<?= $blog->id ?>)">Approve</button>
                                    <button class="btn-action btn-reject" onclick="showRejectModal(<?= $blog->id ?>)">Reject</button>
                                <?php endif; ?>
                                
                                <button class="btn-action btn-feature <?= ($blog->is_featured ?? 0) ? 'featured' : '' ?>" 
                                        onclick="toggleFeatured(<?= $blog->id ?>)">
                                    <?= ($blog->is_featured ?? 0) ? '★ Featured' : '☆ Feature' ?>
                                </button>
                                
                                <button class="btn-action btn-delete" onclick="deleteBlog(<?= $blog->id ?>)">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <h3>No Pending Blogs</h3>
                    <p>All blogs have been reviewed. Check back later for new submissions.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Blog</h3>
            <span class="close" onclick="closeModal('rejectModal')">&times;</span>
        </div>
        <form id="rejectForm">
            <input type="hidden" id="rejectBlogId">
            <div class="form-group">
                <label for="rejectionFeedback">Feedback for the author:</label>
                <textarea id="rejectionFeedback" rows="5" placeholder="Provide constructive feedback to help the author improve their content..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn-danger">Reject Blog</button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div id="bulkRejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Selected Blogs</h3>
            <span class="close" onclick="closeModal('bulkRejectModal')">&times;</span>
        </div>
        <form id="bulkRejectForm">
            <div class="form-group">
                <label for="bulkRejectionFeedback">Feedback for all selected blogs:</label>
                <textarea id="bulkRejectionFeedback" rows="5" placeholder="This feedback will be sent to all selected blog authors..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('bulkRejectModal')">Cancel</button>
                <button type="submit" class="btn-danger">Reject All Selected</button>
            </div>
        </form>
    </div>
</div>

<script>
const ROOT = '<?= ROOT ?>';
let selectedBlogs = [];

function updateBulkSelection() {
    const checkboxes = document.querySelectorAll('.blog-checkbox input:checked');
    selectedBlogs = Array.from(checkboxes).map(cb => parseInt(cb.value));
    
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedBlogs.length > 0) {
        bulkActions.classList.add('active');
        selectedCount.textContent = selectedBlogs.length;
    } else {
        bulkActions.classList.remove('active');
    }
}

function cancelSelection() {
    document.querySelectorAll('.blog-checkbox input').forEach(cb => cb.checked = false);
    updateBulkSelection();
}

function approveBlog(blogId) {
    if (!confirm('Are you sure you want to approve this blog?')) return;

    fetch(ROOT + '/api/admin/blog/approve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog approved successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
}

function showRejectModal(blogId) {
    document.getElementById('rejectBlogId').value = blogId;
    document.getElementById('rejectModal').style.display = 'flex';
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const blogId = document.getElementById('rejectBlogId').value;
    const feedback = document.getElementById('rejectionFeedback').value;
    
    fetch(ROOT + '/api/admin/blog/reject', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_id: blogId, feedback: feedback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog rejected successfully!', 'success');
            closeModal('rejectModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
});

function deleteBlog(blogId) {
    if (!confirm('Are you sure you want to delete this blog? This action cannot be undone.')) return;

    fetch(ROOT + '/api/admin/blog/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
}

function toggleFeatured(blogId) {
    fetch(ROOT + '/api/admin/blog/toggle-featured', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Featured status updated!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
}

function bulkApprove() {
    if (!confirm('Are you sure you want to approve ' + selectedBlogs.length + ' blogs?')) return;

    fetch(ROOT + '/api/admin/blog/bulk-approve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_ids: selectedBlogs })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
}

function showBulkRejectModal() {
    document.getElementById('bulkRejectModal').style.display = 'flex';
}

document.getElementById('bulkRejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const feedback = document.getElementById('bulkRejectionFeedback').value;
    
    fetch(ROOT + '/api/admin/blog/bulk-reject', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ blog_ids: selectedBlogs, feedback: feedback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('bulkRejectModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error, 'error'));
});

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}
</script>

</body>
</html>
