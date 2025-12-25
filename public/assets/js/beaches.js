// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Newsletter subscription
    const subscribeBtn = document.querySelector('.subscribe-btn');
    const emailInput = document.querySelector('.email-input');
    
    if (subscribeBtn && emailInput) {
        subscribeBtn.addEventListener('click', function() {
            const email = emailInput.value.trim();
            if (validateEmail(email)) {
                showNotification('Thank you for subscribing!', 'success');
                emailInput.value = '';
            } else {
                showNotification('Please enter a valid email address', 'error');
            }
        });

        // Allow Enter key to submit
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                subscribeBtn.click();
            }
        });
    }

    // Read more button functionality
    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const article = this.closest('.article-card');
            const paragraph = article.querySelector('p');
            
            if (paragraph.classList.contains('expanded')) {
                paragraph.classList.remove('expanded');
                this.textContent = 'Read more';
            } else {
                paragraph.classList.add('expanded');
                this.textContent = 'Read less';
                // Add expanded content
                if (!paragraph.querySelector('.expanded-content')) {
                    const expandedContent = document.createElement('span');
                    expandedContent.className = 'expanded-content';
                    expandedContent.textContent = ' The crystal-clear waters, pristine sandy beaches, and vibrant coral reefs make this destination truly spectacular. Whether you\'re looking for adventure or relaxation, Hikkaduwa offers something for everyone.';
                    paragraph.appendChild(expandedContent);
                }
            }
        });
    });

    // See accommodations button
    const accommodationsBtn = document.querySelector('.see-accommodations-btn');
    if (accommodationsBtn) {
        accommodationsBtn.addEventListener('click', function() {
            showNotification('Redirecting to accommodations...', 'info');
            // Simulate navigation delay
            setTimeout(() => {
                // In a real application, you would navigate to accommodations page
                console.log('Navigate to accommodations page');
            }, 1000);
        });
    }

    // See all reviews button
    const reviewsBtn = document.querySelector('.see-all-reviews');
    if (reviewsBtn) {
        reviewsBtn.addEventListener('click', function() {
            showNotification('Loading all reviews...', 'info');
            // Simulate loading more reviews
            setTimeout(() => {
                this.textContent = 'Showing all reviews';
                this.disabled = true;
            }, 1000);
        });
    }

    // Contact Us button
    const contactBtn = document.querySelector('.user-btn');
    if (contactBtn) {
        contactBtn.addEventListener('click', function() {
            showContactModal();
        });
    }

    // Add scroll effect to header
    let lastScrollTop = 0;
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });

    // Add animation on scroll for articles
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all article cards
    const articleCards = document.querySelectorAll('.article-card');
    articleCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// Utility functions
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Style the notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '1em 1.5em',
        borderRadius: '8px',
        color: '#fff',
        fontWeight: '500',
        zIndex: '1000',
        transform: 'translateX(400px)',
        transition: 'transform 0.3s ease',
        maxWidth: '300px'
    });

    // Set background color based on type
    switch(type) {
        case 'success':
            notification.style.background = '#1abc5b';
            break;
        case 'error':
            notification.style.background = '#e74c3c';
            break;
        case 'info':
        default:
            notification.style.background = '#3498db';
    }

    // Add to DOM
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);

    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function showContactModal() {
    // Create modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';
    Object.assign(modalOverlay.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        background: 'rgba(0, 0, 0, 0.5)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: '1001',
        opacity: '0',
        transition: 'opacity 0.3s ease'
    });

    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    Object.assign(modalContent.style, {
        background: '#fff',
        borderRadius: '12px',
        padding: '2em',
        maxWidth: '400px',
        width: '90%',
        transform: 'scale(0.9)',
        transition: 'transform 0.3s ease'
    });

    modalContent.innerHTML = `
        <h3 style="color: #333; margin-bottom: 1em; font-size: 1.8em;">Contact Us</h3>
        <p style="color: #666; margin-bottom: 1.5em;">Get in touch with us for any inquiries or booking assistance.</p>
        <div style="margin-bottom: 1.5em;">
            <p style="color: #333; margin-bottom: 0.5em;"><strong>Phone:</strong></p>
            <p style="color: #666;">+94 11 123 4567</p>
        </div>
        <div style="margin-bottom: 1.5em;">
            <p style="color: #333; margin-bottom: 0.5em;"><strong>Email:</strong></p>
            <p style="color: #666;">info@travelmate.lk</p>
        </div>
        <div style="margin-bottom: 1.5em;">
            <p style="color: #333; margin-bottom: 0.5em;"><strong>Address:</strong></p>
            <p style="color: #666;">Colombo, Sri Lanka</p>
        </div>
        <button class="close-modal-btn" style="
            background: #1abc5b;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.8em 2em;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            font-size: 1em;
            transition: background 0.3s ease;
        ">Close</button>
    `;

    modalOverlay.appendChild(modalContent);
    document.body.appendChild(modalOverlay);

    // Animate in
    setTimeout(() => {
        modalOverlay.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);

    // Close modal function
    function closeModal() {
        modalOverlay.style.opacity = '0';
        modalContent.style.transform = 'scale(0.9)';
        setTimeout(() => {
            modalOverlay.remove();
        }, 300);
    }

    // Close button event
    const closeBtn = modalContent.querySelector('.close-modal-btn');
    closeBtn.addEventListener('click', closeModal);
    closeBtn.addEventListener('mouseenter', function() {
        this.style.background = '#16a252';
    });
    closeBtn.addEventListener('mouseleave', function() {
        this.style.background = '#1abc5b';
    });

    // Close on overlay click
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function escapeClose(e) {
        if (e.key === 'Escape') {
            closeModal();
            document.removeEventListener('keydown', escapeClose);
        }
    });
}

// Mobile menu toggle (for future implementation)
function toggleMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('mobile-active');
}

// Add mobile menu styles dynamically
const style = document.createElement('style');
style.textContent = `
    @media (max-width: 768px) {
        .nav-links {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #111;
            flex-direction: column;
            padding: 1em;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        
        .nav-links.mobile-active {
            display: flex;
        }
        
        .mobile-menu-btn {
            display: block;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5em;
            cursor: pointer;
        }
    }
    
    @media (min-width: 769px) {
        .mobile-menu-btn {
            display: none;
        }
    }
`;
document.head.appendChild(style);