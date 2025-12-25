// Global Variables
let currentModal = null;
let isScrolling = false;

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Initialize Application
function initializeApp() {
    setupEventListeners();
    setupIntersectionObserver();
    setupParallaxEffect();
    setMinimumDates();
}

// Event Listeners
function setupEventListeners() {
    // Modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target);
        }
    });

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && currentModal) {
            closeModal(currentModal);
        }
    });

    // Navbar scroll effect
    window.addEventListener('scroll', handleNavbarScroll);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', handleSmoothScroll);
    });

    // Form submission
    const bookingForm = document.querySelector('.booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', handleBookingSubmit);
    }

    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form button');
    if (newsletterForm) {
        newsletterForm.addEventListener('click', handleNewsletterSubmit);
    }
}

// Navbar Scroll Effect
function handleNavbarScroll() {
    if (isScrolling) return;
    isScrolling = true;
    
    requestAnimationFrame(() => {
        const navbar = document.querySelector('header');
        const scrolled = window.scrollY > 50;
        
        if (scrolled) {
            navbar.style.background = 'rgba(17, 17, 17, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.background = '#111';
            navbar.style.backdropFilter = 'none';
        }
        
        isScrolling = false;
    });
}

// Smooth Scroll Handler
function handleSmoothScroll(e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    const targetSection = document.querySelector(targetId);
    
    if (targetSection) {
        const headerOffset = 80;
        const elementPosition = targetSection.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

// Intersection Observer for Animations
function setupIntersectionObserver() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                
                // Add stagger effect for destination cards
                if (entry.target.classList.contains('destination-card')) {
                    const delay = Array.from(entry.target.parentNode.children).indexOf(entry.target) * 100;
                    entry.target.style.animationDelay = `${delay}ms`;
                }
            }
        });
    }, observerOptions);

    // Observe destination cards and other elements
    document.querySelectorAll('.destination-card, .section-header').forEach(el => {
        observer.observe(el);
    });
}

// Parallax Effect
function setupParallaxEffect() {
    if (window.innerWidth < 1024) return; // Disable on mobile for performance
    
    window.addEventListener('scroll', () => {
        if (isScrolling) return;
        isScrolling = true;
        
        requestAnimationFrame(() => {
            const scrolled = window.pageYOffset;
            const heroBackground = document.querySelector('.hero-background');
            
            if (heroBackground) {
                const parallax = scrolled * 0.5;
                heroBackground.style.transform = `translateY(${parallax}px)`;
            }
            
            isScrolling = false;
        });
    });
}

// Hero Section Functions
function scrollToDestinations() {
    const destinationsSection = document.querySelector('.destinations-section');
    if (destinationsSection) {
        const headerOffset = 80;
        const elementPosition = destinationsSection.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

function openBookingModal() {
    const modal = document.getElementById('bookingModal');
    if (modal) {
        modal.classList.add('active');
        currentModal = modal;
        document.body.style.overflow = 'hidden';
        
        // Focus on first input
        setTimeout(() => {
            const firstInput = modal.querySelector('input, select');
            if (firstInput) firstInput.focus();
        }, 300);
    }
}

function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    closeModal(modal);
}

// Modal Functions
function closeModal(modal) {
    if (modal) {
        modal.classList.remove('active');
        currentModal = null;
        document.body.style.overflow = '';
    }
}

// Destination Functions
function exploreDestination(category) {
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Loading...';
    button.disabled = true;

    // Simulate API call
    setTimeout(() => {
        // Reset button
        button.textContent = originalText;
        button.disabled = false;
        
        // Here you would typically navigate to a destination page or open a detailed modal
        showDestinationDetails(category);
    }, 1000);
}

function showDestinationDetails(category) {
    const categoryInfo = {
        beach: {
            title: 'Beach Side Adventures',
            description: 'Explore pristine beaches with crystal clear waters and golden sand.',
            highlights: ['Unawatuna Beach', 'Mirissa Beach', 'Bentota Beach', 'Arugam Bay']
        },
        country: {
            title: 'Country Side Escapes',
            description: 'Experience rural Sri Lanka with lush green landscapes and traditional villages.',
            highlights: ['Tea Plantations', 'Rice Paddies', 'Village Tours', 'Organic Farms']
        },
        hill: {
            title: 'Hill Country Retreats',
            description: 'Discover misty mountains and cool climate in Sri Lanka\'s hill country.',
            highlights: ['Nuwara Eliya', 'Ella', 'Kandy', 'Haputale']
        },
        mountain: {
            title: 'Mountain Adventures',
            description: 'Conquer majestic peaks and enjoy breathtaking panoramic views.',
            highlights: ['Adams Peak', 'Pidurutalagala', 'Knuckles Range', 'World\'s End']
        },
        cultural: {
            title: 'Cultural Heritage',
            description: 'Immerse yourself in Sri Lanka\'s rich cultural heritage and ancient traditions.',
            highlights: ['Sigiriya', 'Polonnaruwa', 'Anuradhapura', 'Dambulla']
        },
        historical: {
            title: 'Historical Wonders',
            description: 'Journey through time exploring ancient ruins and archaeological marvels.',
            highlights: ['Galle Fort', 'Yapahuwa', 'Medirigiriya', 'Ritigala']
        },
        city: {
            title: 'City Tours',
            description: 'Experience urban life with modern attractions and bustling markets.',
            highlights: ['Colombo', 'Galle', 'Kandy', 'Negombo']
        },
        forest: {
            title: 'Forest Expeditions',
            description: 'Venture into dense jungles for wildlife encounters and nature walks.',
            highlights: ['Sinharaja', 'Udawalawe', 'Yala', 'Wilpattu']
        },
        waterfall: {
            title: 'Waterfall Adventures',
            description: 'Discover cascading waters and natural pools in pristine settings.',
            highlights: ['Sekumpura Falls', 'Bambarakanda Falls', 'Diyaluma Falls', 'Baker\'s Falls']
        },
        rural: {
            title: 'Rural Experiences',
            description: 'Connect with authentic village life and local traditions.',
            highlights: ['Village Walks', 'Home Stays', 'Traditional Crafts', 'Local Cuisine']
        },
        island: {
            title: 'Island Getaways',
            description: 'Escape to secluded islands surrounded by pristine nature.',
            highlights: ['Pigeon Island', 'Delft Island', 'Barberyn Island', 'Crow Island']
        },
        dryland: {
            title: 'Dry Zone Exploration',
            description: 'Explore unique landscapes and desert-like beauty of dry zones.',
            highlights: ['Mannar', 'Puttalam', 'Hambantota', 'Monaragala']
        }
    };

    const info = categoryInfo[category] || categoryInfo.beach;
    
    alert(`${info.title}\n\n${info.description}\n\nTop Destinations:\n• ${info.highlights.join('\n• ')}\n\nContact us to plan your perfect trip!`);
}

// Form Handlers
function handleBookingSubmit(e) {
    e.preventDefault();
    
    const formData = {
        destinationType: document.getElementById('destinationType').value,
        checkinDate: document.getElementById('checkinDate').value,
        checkoutDate: document.getElementById('checkoutDate').value,
        adults: document.getElementById('adults').value,
        children: document.getElementById('children').value
    };

    // Validate form
    if (!validateBookingForm(formData)) {
        return;
    }

    // Show loading state
    const submitBtn = e.target.querySelector('.btn-primary');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Searching...';
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Show success message
        alert('Great! We found several amazing tours that match your preferences. Our team will contact you shortly with personalized recommendations.');
        
        // Close modal
        closeBookingModal();
        
        // Reset form
        e.target.reset();
    }, 2000);
}

function validateBookingForm(data) {
    if (!data.destinationType) {
        alert('Please select a destination type');
        return false;
    }
    
    if (!data.checkinDate || !data.checkoutDate) {
        alert('Please select both check-in and check-out dates');
        return false;
    }
    
    const checkin = new Date(data.checkinDate);
    const checkout = new Date(data.checkoutDate);
    const today = new Date();
    
    if (checkin < today) {
        alert('Check-in date cannot be in the past');
        return false;
    }
    
    if (checkout <= checkin) {
        alert('Check-out date must be after check-in date');
        return false;
    }
    
    return true;
}

function handleNewsletterSubmit() {
    const emailInput = document.querySelector('.newsletter-form input');
    const button = event.target;
    
    if (!emailInput.value) {
        alert('Please enter your email address');
        emailInput.focus();
        return;
    }
    
    if (!isValidEmail(emailInput.value)) {
        alert('Please enter a valid email address');
        emailInput.focus();
        return;
    }
    
    // Show loading state
    const originalText = button.textContent;
    button.textContent = 'Subscribing...';
    button.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Reset button
        button.textContent = originalText;
        button.disabled = false;
        
        // Show success message
        alert('Thank you for subscribing! You\'ll receive our latest travel tips and exclusive offers.');
        
        // Clear input
        emailInput.value = '';
    }, 1500);
}

// Utility Functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function setMinimumDates() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const checkinInput = document.getElementById('checkinDate');
    const checkoutInput = document.getElementById('checkoutDate');
    
    if (checkinInput) {
        checkinInput.min = today.toISOString().split('T')[0];
        checkinInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const nextDay = new Date(selectedDate);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutInput.min = nextDay.toISOString().split('T')[0];
        });
    }
    
    if (checkoutInput) {
        checkoutInput.min = tomorrow.toISOString().split('T')[0];
    }
}

// Submit Booking Function (called from modal)
function submitBooking() {
    const form = document.querySelector('.booking-form');
    if (form) {
        const event = new Event('submit');
        form.dispatchEvent(event);
    }
}

// Performance Optimization
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimized scroll handler
const optimizedScrollHandler = debounce(handleNavbarScroll, 10);
window.addEventListener('scroll', optimizedScrollHandler);

// Error Handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    // You could send this to a logging service
});


// Analytics (placeholder for Google Analytics or similar)
function trackEvent(action, category, label) {
    // Google Analytics 4 event tracking
    if (typeof gtag !== 'undefined') {
        gtag('event', action, {
            event_category: category,
            event_label: label
        });
    }
}

// Track destination card clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('explore-btn')) {
        const card = e.target.closest('.destination-card');
        const category = card ? card.dataset.category : 'unknown';
        trackEvent('explore_destination', 'destinations', category);
    }
});

// Lazy loading images (if needed)
function setupLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading if images have data-src attributes
if (document.querySelectorAll('img[data-src]').length > 0) {
    setupLazyLoading();
}

// Dark mode toggle (optional feature)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Load dark mode preference
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}

// Animation on scroll for mobile performance
function handleMobileAnimations() {
    if (window.innerWidth < 768) {
        // Disable complex animations on mobile
        document.body.classList.add('mobile-optimized');
    }
}

// Check mobile on load and resize
window.addEventListener('load', handleMobileAnimations);
window.addEventListener('resize', debounce(handleMobileAnimations, 250));