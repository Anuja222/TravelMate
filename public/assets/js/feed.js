// feed Page Interactions
document.addEventListener('DOMContentLoaded', function() {
    // filter Tabs Functionality
    const filterTabs = document.querySelectorAll('.tab-btn');
    const posts = document.querySelectorAll('.post-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // add active class to clicked tab
            this.classList.add('active');

            const filter = this.dataset.filter;

            // filter posts
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

    // vote Button Functionality
    const voteBtns = document.querySelectorAll('.vote-btn');
    voteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.id;
            const type = this.dataset.type; // 'upvote' or 'downvote'
            const postActions = this.closest('.post-actions');
            
            // send AJAX request to update database
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('type', type);
            
            // get base URL to ensure proper endpoint mapping safely provided by PHP
            const baseUrl = window.AppConfig && window.AppConfig.baseUrl ? window.AppConfig.baseUrl : '';
            
            fetch(baseUrl + '/blog/vote', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // update UI counters
                    postActions.querySelector('.upvote .count').textContent = data.upvotes;
                    postActions.querySelector('.downvote .count').textContent = data.downvotes;
                    
                    // toggle active state classes
                    const isCurrentlyActive = this.classList.contains('active');
                    
                    // reset both buttons
                    postActions.querySelectorAll('.vote-btn').forEach(b => {
                        b.classList.remove('active');
                        b.style.removeProperty('color');
                    });
                    
                    // re-apply to clicked if it wasn't just toggled off
                    if (!isCurrentlyActive) {
                        this.classList.add('active');
                    }
                } else {
                    console.error('Vote failed:', data.message);
                    alert("Failed to record vote: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Network error. Please try again.");
            });
        });
    });

    // save Button Functionality
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

    // comment Input Focus
    const commentInputs = document.querySelectorAll('.comment-input');
    commentInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.background = '#e4e6eb';
        });
        input.addEventListener('blur', function() {
            this.style.background = '#f0f2f5';
        });
    });

    // send Comment
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

    // image Click to Enlarge (Simple version)
    const postImages = document.querySelectorAll('.post-image');
    postImages.forEach(img => {
        img.addEventListener('click', function() {
            // could implement lightbox here
            console.log('Image clicked:', this.src);
        });
    });

    // post Menu Button
    const menuBtns = document.querySelectorAll('.post-menu-btn');
    menuBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            alert('Post options menu (Save, Report, Hide, etc.)');
        });
    });

    // view More Comments
    const viewMoreBtns = document.querySelectorAll('.view-more-comments');
    viewMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            alert('Loading more comments...');
        });
    });

    // follow Button
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

// toggle post menu
function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    // close all other menus
    document.querySelectorAll('.post-menu-dropdown').forEach(m => {
        if (m.id !== 'menu-' + postId) {
            m.style.display = 'none';
        }
    });
    // toggle current menu
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

// close menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.post-menu')) {
        document.querySelectorAll('.post-menu-dropdown').forEach(m => {
            m.style.display = 'none';
        });
    }
});
