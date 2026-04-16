console.log('Profile page loaded');

document.addEventListener('DOMContentLoaded', function() {
    // tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // remove active class from all tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // add active class to clicked tab
            this.classList.add('active');
            document.getElementById(`${targetTab}-tab`).classList.add('active');
        });
    });
    
    // follow button functionality
    const followBtn = document.getElementById('followBtn');
    if (followBtn) {
        followBtn.addEventListener('click', function() {
            if (this.classList.contains('following')) {
                this.classList.remove('following');
                this.innerHTML = `
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Follow
                `;
                // tODO: Make API call to unfollow
            } else {
                this.classList.add('following');
                this.innerHTML = `
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    Following
                `;
                // tODO: Make API call to follow
            }
        });
    }
    
    // like button functionality for posts
    const likeButtons = document.querySelectorAll('.post-action-btn');
    likeButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const svg = this.querySelector('svg');
            if (svg) {
                const path = svg.querySelector('path');
                if (path) {
                    if (path.getAttribute('fill') === 'none') {
                        path.setAttribute('fill', 'red');
                        path.setAttribute('stroke', 'red');
                        // tODO: Make API call to like post
                    } else {
                        path.setAttribute('fill', 'none');
                        path.setAttribute('stroke', 'currentColor');
                        // tODO: Make API call to unlike post
                    }
                }
            }
        });
    });
    
    // photo gallery modal (optional enhancement)
    const photoItems = document.querySelectorAll('.photo-item');
    photoItems.forEach(item => {
        item.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            // tODO: Open image in modal/lightbox
            console.log('Photo clicked:', imgSrc);
        });
    });
    
    // more button dropdown (placeholder)
    const moreBtn = document.querySelector('.btn-more');
    if (moreBtn) {
        moreBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            // tODO: Show dropdown menu with options like Share, Report, etc.
            console.log('More options clicked');
        });
    }
});
