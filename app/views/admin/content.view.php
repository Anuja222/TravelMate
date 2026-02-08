<!DOCTYPE html>
<html>
<head>
  <title>Blog Management</title>
  <link rel="stylesheet" href="assets/css/Admin/content.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">

      <?php include 'sidebar.view.php'; ?>

<div class="content">
  <div class="page-title">  
    <h1>Blog Management</h1>
    <p style="color: #666; margin-top: 10px;">Review and approve traveler blog posts</p>
  </div>

  <?php if (isset($pendingPosts) && count($pendingPosts) > 0): ?>
    <div class="posts-grid">
      <?php foreach ($pendingPosts as $post): ?>
        <div class="post-card" data-post-id="<?= $post->id ?>">
          <div class="post-image">
            <?php if (!empty($post->image)): ?>
              <img src="<?= htmlspecialchars($post->image) ?>" alt="<?= htmlspecialchars($post->title) ?>" onerror="this.src='assets/images/default-post.png'">
            <?php else: ?>
              <img src="assets/images/default-post.png" alt="No image">
            <?php endif; ?>
            <div class="post-category-badge">
              <?= htmlspecialchars(ucfirst($post->category ?? 'General')) ?>
            </div>
          </div>
          
          <div class="post-content">
            <h3 class="post-title"><?= htmlspecialchars($post->title) ?></h3>
            
            <div class="post-meta">
              <div class="meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?= htmlspecialchars($post->first_name . ' ' . $post->last_name) ?>
              </div>
              <div class="meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <?= htmlspecialchars($post->location ?? 'Not specified') ?>
              </div>
              <div class="meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <?= date('M d, Y', strtotime($post->created_at)) ?>
              </div>
            </div>
            
            <p class="post-description">
              <?= htmlspecialchars(substr($post->description ?? '', 0, 120)) ?><?= strlen($post->description ?? '') > 120 ? '...' : '' ?>
            </p>
            
            <div class="post-actions">
              <button class="btn-view" onclick="viewFullPost(<?= $post->id ?>, <?= htmlspecialchars(json_encode($post), ENT_QUOTES) ?>)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                View Full
              </button>
              <button class="btn-approve" onclick="approvePost(<?= $post->id ?>)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Approve
              </button>
              <button class="btn-reject" onclick="rejectPost(<?= $post->id ?>)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Reject
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
      </svg>
      <h3>No Pending Posts</h3>
      <p>All blog posts have been reviewed.</p>
    </div>
  <?php endif; ?>
</div>

</div>

<!-- View Post Modal -->
<div id="viewPostModal" class="modal" style="display: none;">
  <div class="modal-content" style="max-width: 900px;">
    <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
      <h2 id="modalPostTitle" style="margin: 0; color: #1f2937;"></h2>
      <button onclick="closeModal()" style="background: none; border: none; font-size: 32px; cursor: pointer; color: #9ca3af; line-height: 1; padding: 0; width: 32px; height: 32px;">&times;</button>
    </div>
    <div class="modal-body">
      <div id="modalPostImage" style="margin-bottom: 20px;"></div>
      <div id="modalPostMeta" style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; font-size: 14px; color: #6b7280;"></div>
      <div id="modalPostContent" style="line-height: 1.7; color: #374151; font-size: 16px;"></div>
      <div id="modalPostActions" style="margin-top: 30px; display: flex; gap: 12px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e7eb;"></div>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal" style="display: none;">
  <div class="modal-content" style="max-width: 450px;">
    <div class="confirm-modal-icon" id="confirmIcon"></div>
    <h2 id="confirmTitle" style="margin: 20px 0 10px 0; color: #1f2937; text-align: center; font-size: 22px;"></h2>
    <p id="confirmMessage" style="margin: 0 0 30px 0; color: #6b7280; text-align: center; font-size: 15px; line-height: 1.6;"></p>
    <div style="display: flex; gap: 12px; justify-content: center;">
      <button id="confirmCancel" style="padding: 12px 32px; background: #f3f4f6; border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: #374151; font-weight: 600;">Cancel</button>
      <button id="confirmAction" style="padding: 12px 32px; border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: white; font-weight: 600;"></button>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none;">
  <div class="modal-content" style="max-width: 400px; text-align: center;">
    <div class="success-icon">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="9 12 11 14 15 10"></polyline>
      </svg>
    </div>
    <h2 id="successTitle" style="margin: 20px 0 10px 0; color: #1f2937; font-size: 22px;">Success!</h2>
    <p id="successMessage" style="margin: 0 0 30px 0; color: #6b7280; font-size: 15px;"></p>
    <button onclick="closeSuccessModal()" style="padding: 12px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: white; font-weight: 600;">Continue</button>
  </div>
</div>

<style>
  /* Grid Layout */
  .posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    margin-top: 30px;
  }
  
  /* Post Card */
  .post-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
  }
  
  .post-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
  }
  
  .post-image {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #f3f4f6;
  }
  
  .post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .post-card:hover .post-image img {
    transform: scale(1.05);
  }
  
  .post-category-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(26, 188, 91, 0.95);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .post-content {
    padding: 20px;
  }
  
  .post-title {
    margin: 0 0 12px 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .post-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #6b7280;
  }
  
  .meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  .meta-item svg {
    flex-shrink: 0;
  }
  
  .post-description {
    margin: 0 0 16px 0;
    color: #4b5563;
    font-size: 14px;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .post-actions {
    display: flex;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
  }
  
  .post-actions button {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .btn-view {
    background: #f3f4f6;
    color: #374151;
  }
  
  .btn-view:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
  }
  
  .btn-approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
  }
  
  .btn-approve:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }
  
  .btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
  }
  
  .btn-reject:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
  }
  
  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
    margin-top: 30px;
  }
  
  .empty-state svg {
    opacity: 0.3;
    margin-bottom: 20px;
  }
  
  .empty-state h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 20px;
  }
  
  .empty-state p {
    margin: 0;
    color: #6b7280;
    font-size: 15px;
  }
  
  /* Modal Styles */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
  }
  
  .modal-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  @keyframes slideUp {
    from {
      transform: translateY(30px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  
  .success-message {
    background: #d1fae5;
    color: #065f46;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    animation: slideDown 0.3s ease;
  }
  
  @keyframes slideDown {
    from {
      transform: translateY(-20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  
  /* Confirmation Modal Styles */
  .confirm-modal-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: #d1fae5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.4s ease;
  }
  
  @keyframes scaleIn {
    from {
      transform: scale(0);
      opacity: 0;
    }
    to {
      transform: scale(1);
      opacity: 1;
    }
  }
</style>

<script>
  function approvePost(postId) {
    showConfirmModal(
      'Approve Blog Post',
      'Are you sure you want to approve this blog post? It will be published to the feed immediately.',
      'Approve',
      '#10b981',
      '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline></svg>',
      function() {
        fetch('content/approve', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeConfirmModal();
            showSuccessModalWithMessage('Blog Approved!', 'The blog post has been successfully approved and is now visible in the feed.');
            removePostFromList(postId);
          } else {
            closeConfirmModal();
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          closeConfirmModal();
          alert('Failed to approve post');
        });
      }
    );
  }
  
  function rejectPost(postId) {
    showConfirmModal(
      'Reject Blog Post',
      'Are you sure you want to reject this blog post? The traveler will be notified.',
      'Reject',
      '#ef4444',
      '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
      function() {
        fetch('content/reject', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeConfirmModal();
            showSuccessModalWithMessage('Blog Rejected', 'The blog post has been rejected and removed from pending list.');
            removePostFromList(postId);
          } else {
            closeConfirmModal();
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          closeConfirmModal();
          alert('Failed to reject post');
        });
      }
    );
  }
  
  function removePostFromList(postId) {
    const postCard = document.querySelector(`.post-card[data-post-id="${postId}"]`);
    if (postCard) {
      postCard.style.transition = 'all 0.3s ease';
      postCard.style.opacity = '0';
      postCard.style.transform = 'scale(0.9)';
      setTimeout(() => {
        postCard.remove();
        
        // Check if there are no more posts
        const postsGrid = document.querySelector('.posts-grid');
        if (postsGrid && postsGrid.querySelectorAll('.post-card').length === 0) {
          // Replace only the grid, not the entire content
          postsGrid.outerHTML = `
            <div class="empty-state">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              <h3>No Pending Posts</h3>
              <p>All blog posts have been reviewed.</p>
            </div>
          `;
        }
      }, 300);
    }
  }
  
  function viewFullPost(postId, postData) {
    const modal = document.getElementById('viewPostModal');
    document.getElementById('modalPostTitle').textContent = postData.title;
    
    // Display image
    const imageDiv = document.getElementById('modalPostImage');
    if (postData.image) {
      imageDiv.innerHTML = `<img src="${postData.image}" alt="${postData.title}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px;" onerror="this.style.display='none'">`;
    } else {
      imageDiv.innerHTML = '';
    }
    
    // Display meta information
    const metaDiv = document.getElementById('modalPostMeta');
    metaDiv.innerHTML = `
      <div style="display: flex; align-items: center; gap: 6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <strong>Author:</strong> ${postData.first_name} ${postData.last_name}
      </div>
      <div style="display: flex; align-items: center; gap: 6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <strong>Location:</strong> ${postData.location || 'Not specified'}
      </div>
      <div style="display: flex; align-items: center; gap: 6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <strong>Category:</strong> ${postData.category || 'General'}
      </div>
    `;
    
    // Display content
    document.getElementById('modalPostContent').innerHTML = `<p style="white-space: pre-wrap;">${postData.description || ''}</p>`;
    
    // Display action buttons
    const actionsDiv = document.getElementById('modalPostActions');
    actionsDiv.innerHTML = `
      <button onclick="closeModal()" style="padding: 10px 24px; background: #f3f4f6; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; color: #374151; font-weight: 600;">Close</button>
      <button onclick="approvePost(${postId}); closeModal();" style="padding: 10px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 8px; font-size: 14px; cursor: pointer; color: white; font-weight: 600;">Approve Post</button>
      <button onclick="rejectPost(${postId}); closeModal();" style="padding: 10px 24px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 8px; font-size: 14px; cursor: pointer; color: white; font-weight: 600;">Reject Post</button>
    `;
    
    modal.style.display = 'flex';
  }
  
  function closeModal() {
    document.getElementById('viewPostModal').style.display = 'none';
  }
  
  function showConfirmModal(title, message, actionText, actionColor, icon, onConfirm) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmIcon').innerHTML = icon;
    
    const actionBtn = document.getElementById('confirmAction');
    actionBtn.textContent = actionText;
    actionBtn.style.background = `linear-gradient(135deg, ${actionColor} 0%, ${actionColor}dd 100%)`;
    
    actionBtn.onclick = onConfirm;
    document.getElementById('confirmCancel').onclick = closeConfirmModal;
    
    modal.style.display = 'flex';
  }
  
  function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
  }
  
  function showSuccessModalWithMessage(title, message) {
    const modal = document.getElementById('successModal');
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = message;
    modal.style.display = 'flex';
  }
  
  function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
  }
</script>
</body>
</html>
