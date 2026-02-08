// Feed Page Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Filter Tabs Functionality
    const filterTabs = document.querySelectorAll('.tab-btn');
    const posts = document.querySelectorAll('.post-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const filter = this.dataset.filter;

            // Filter posts
            posts.forEach(post => {
                if (filter === 'all') {
                    post.style.display = 'block';
                } else {
                    if (post.dataset.category === filter) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                }
            });
        });
    });

    // Like Button Functionality
    const likeBtns = document.querySelectorAll('.like-btn');
    likeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('liked');
            const svg = this.querySelector('.action-icon svg');
            if (this.classList.contains('liked')) {
                this.style.color = '#e74c3c';
                svg.setAttribute('fill', '#e74c3c');
                svg.setAttribute('stroke', '#e74c3c');
            } else {
                this.style.color = '#65676b';
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
            }
        });
    });

    // Save Button Functionality
    const saveBtns = document.querySelectorAll('.save-btn');
    saveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('saved');
            if (this.classList.contains('saved')) {
                this.style.color = '#f39c12';
                alert('Post saved to your collection!');
            } else {
                this.style.color = '#65676b';
            }
        });
    });

    // Comment Input Focus
    const commentInputs = document.querySelectorAll('.comment-input');
    commentInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.background = '#e4e6eb';
        });
        input.addEventListener('blur', function() {
            this.style.background = '#f0f2f5';
        });
    });

    // Send Comment
    const sendBtns = document.querySelectorAll('.send-comment-btn');
    sendBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.value.trim()) {
                alert('Comment posted: ' + input.value);
                input.value = '';
            }
        });
    });

    // Image Click to Enlarge (Simple version)
    const postImages = document.querySelectorAll('.post-image');
    postImages.forEach(img => {
        img.addEventListener('click', function() {
            // Could implement lightbox here
            console.log('Image clicked:', this.src);
        });
    });

    // Post Menu Button
    const menuBtns = document.querySelectorAll('.post-menu-btn');
    menuBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            alert('Post options menu (Save, Report, Hide, etc.)');
        });
    });

    // View More Comments
    const viewMoreBtns = document.querySelectorAll('.view-more-comments');
    viewMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            alert('Loading more comments...');
        });
    });

    // Follow Button
    const followBtns = document.querySelectorAll('.follow-btn');
    followBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.textContent === 'Follow') {
                this.textContent = 'Following';
                this.style.background = '#e4e6eb';
                this.style.color = '#65676b';
            } else {
                this.textContent = 'Follow';
                this.style.background = '#1abc5b';
                this.style.color = 'white';
            }
        });
    });
});

// Toggle post menu
function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    // Close all other menus
    document.querySelectorAll('.post-menu-dropdown').forEach(m => {
        if (m.id !== 'menu-' + postId) {
            m.style.display = 'none';
        }
    });
    // Toggle current menu
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.post-menu')) {
        document.querySelectorAll('.post-menu-dropdown').forEach(m => {
            m.style.display = 'none';
        });
    }
});
