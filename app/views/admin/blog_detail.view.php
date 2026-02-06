<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Details - Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/blog_detail.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../traveller/header.view.php'; ?>

<div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
        <?php include __DIR__ . '/flash_messages.php'; ?>
        <a href="<?= ROOT ?>/content" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Blog Management
        </a>

        <div class="blog-detail-container">
            <!-- Featured Image -->
            <?php 
            $imageUrl = !empty($blog->featured_image) ? htmlspecialchars($blog->featured_image) : ROOT . '/assets/images/default-blog.jpg';
            ?>
            <div class="blog-featured-image" style="background-image: url('<?= $imageUrl ?>')">
                <div class="overlay">
                    <h1 class="blog-title"><?= htmlspecialchars($blog->title ?? 'Untitled Blog') ?></h1>
                    <div class="blog-meta">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($blog->author_name ?? 'Unknown') ?></span>
                        <span><i class="fas fa-calendar"></i> <?= isset($blog->created_at) ? date('F d, Y', strtotime($blog->created_at)) : 'N/A' ?></span>
                        <span><i class="fas fa-eye"></i> <?= $blog->views_count ?? 0 ?> views</span>
                        <?php if (!empty($blog->location)): ?>
                            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($blog->location) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Blog Body -->
            <div class="blog-body">
                <!-- Status Banner -->
                <div class="status-banner <?= $blog->status ?? 'pending' ?>">
                    <div class="status-text">
                        <?php if (($blog->status ?? 'pending') == 'pending'): ?>
                            <i class="fas fa-clock"></i> This blog is pending review
                        <?php elseif ($blog->status == 'approved'): ?>
                            <i class="fas fa-check-circle"></i> This blog has been approved
                            <?php if (!empty($blog->approved_at)): ?>
                                on <?= date('F d, Y', strtotime($blog->approved_at)) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <i class="fas fa-times-circle"></i> This blog has been rejected
                            <?php if (!empty($blog->rejected_at)): ?>
                                on <?= date('F d, Y', strtotime($blog->rejected_at)) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($blog->is_featured ?? false): ?>
                        <span class="featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Author Info -->
                <div class="author-info">
                    <div class="author-avatar">
                        <?= strtoupper(substr($blog->author_name ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="author-details">
                        <h4><?= htmlspecialchars($blog->author_name ?? 'Unknown Author') ?></h4>
                        <p><?= htmlspecialchars($blog->email ?? 'No email available') ?></p>
                    </div>
                </div>

                <!-- Blog Info Grid -->
                <div class="blog-info-grid">
                    <div class="info-item">
                        <label>Created</label>
                        <span><?= isset($blog->created_at) ? date('M d, Y H:i', strtotime($blog->created_at)) : 'N/A' ?></span>
                    </div>
                    <div class="info-item">
                        <label>Views</label>
                        <span><?= number_format($blog->views_count ?? 0) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Likes</label>
                        <span><?= number_format($blog->likes_count ?? 0) ?></span>
                    </div>
                    <?php if (!empty($blog->location)): ?>
                    <div class="info-item">
                        <label>Location</label>
                        <span><?= htmlspecialchars($blog->location) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tags if available -->
                <?php if (!empty($blog->tags)): ?>
                <div class="blog-tags">
                    <?php 
                    $tags = is_array($blog->tags) ? $blog->tags : explode(',', $blog->tags);
                    foreach ($tags as $tag): 
                    ?>
                        <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Admin Feedback (if rejected) -->
                <?php if (($blog->status ?? '') == 'rejected' && !empty($blog->admin_feedback)): ?>
                <div class="feedback-section">
                    <h4><i class="fas fa-comment-alt"></i> Rejection Feedback</h4>
                    <p><?= htmlspecialchars($blog->admin_feedback) ?></p>
                </div>
                <?php endif; ?>

                <!-- Blog Content Section -->
                <div class="blog-content-section">
                    <h3>Blog Content</h3>
                    <div class="blog-text">
                        <?= nl2br(htmlspecialchars($blog->content ?? 'No content available')) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <?php if (($blog->status ?? 'pending') == 'pending'): ?>
                        <button class="btn btn-approve" onclick="approveBlog(<?= $blog->id ?>)">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button class="btn btn-reject" onclick="showRejectModal()">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-feature" onclick="toggleFeatured(<?= $blog->id ?>)">
                        <i class="fas fa-star"></i> <?= ($blog->is_featured ?? 0) ? 'Unfeature' : 'Feature' ?>
                    </button>
                    
                    <button class="btn btn-delete" onclick="deleteBlog(<?= $blog->id ?>)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Blog</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <label for="rejectionFeedback">Feedback for the author:</label>
            <textarea id="rejectionFeedback" rows="5" placeholder="Provide constructive feedback to help the author improve their content..."></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button class="btn btn-reject" onclick="rejectBlog(<?= $blog->id ?>)">Reject Blog</button>
        </div>
    </div>
</div>

<script>
const ROOT = '<?= ROOT ?>';

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

function approveBlog(blogId) {
    if (!confirm('Are you sure you want to approve this blog?')) return;
    
    fetch(ROOT + '/api/admin/blog/approve', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog approved successfully!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(data.message || 'Failed to approve blog', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

function rejectBlog(blogId) {
    const feedback = document.getElementById('rejectionFeedback').value;
    if (!feedback.trim()) {
        showNotification('Please provide feedback for the author', 'error');
        return;
    }
    
    fetch(ROOT + '/api/admin/blog/reject', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ blog_id: blogId, feedback: feedback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog rejected successfully!');
            closeModal();
            setTimeout(() => window.location.href = ROOT + '/content', 1500);
        } else {
            showNotification(data.message || 'Failed to reject blog', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

function toggleFeatured(blogId) {
    fetch(ROOT + '/api/admin/blog/toggle-featured', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(data.message || 'Failed to toggle featured status', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

function deleteBlog(blogId) {
    if (!confirm('Are you sure you want to delete this blog? This action cannot be undone.')) return;
    
    fetch(ROOT + '/api/admin/blog/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ blog_id: blogId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Blog deleted successfully!');
            setTimeout(() => window.location.href = ROOT + '/content', 1500);
        } else {
            showNotification(data.message || 'Failed to delete blog', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

</body>
</html>
