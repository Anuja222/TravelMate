// Transportation Detail Page JavaScript

// Sample data structure - replace with actual API calls
const transportData = {
    id: 1,
    name: "Premium SUV - Toyota Land Cruiser",
    type: "SUV",
    category: "luxury",
    location: "Colombo International Airport",
    description: "Experience comfortable and reliable transportation with our premium SUV service. Perfect for families or groups, this spacious vehicle offers luxury, safety, and convenience throughout your journey in Sri Lanka. Equipped with modern amenities and driven by professional, experienced chauffeurs.",
    basePrice: 12500,
    pricePerKm: 150,
    images: [
        "assets/images/car1.png",
        "assets/images/car2.png",
        "assets/images/luxurycar3.png",
        "assets/images/luxurycar4.png"
    ],
    features: [
        { icon: "❄️", text: "Air Conditioning" },
        { icon: "📡", text: "GPS Navigation" },
        { icon: "📱", text: "Phone Charging" },
        { icon: "🎵", text: "Bluetooth Audio" },
        { icon: "💺", text: "Leather Seats" },
        { icon: "🛡️", text: "Insurance Included" },
        { icon: "🧳", text: "Spacious Luggage" },
        { icon: "👨‍✈️", text: "Professional Driver" }
    ],
    specifications: [
        { label: "Capacity", value: "7 Passengers" },
        { label: "Luggage", value: "5 Large Bags" },
        { label: "Fuel Type", value: "Diesel" },
        { label: "Transmission", value: "Automatic" },
        { label: "Year", value: "2023" },
        { label: "Mileage", value: "12 km/L" }
    ],
    pricingOptions: [
        {
            name: "Airport Transfer",
            price: "Rs.8,500",
            description: "One-way transfer from airport to your destination",
            features: [
                "Meet & Greet service",
                "Flight tracking",
                "Free waiting time: 60 mins",
                "All tolls included"
            ],
            popular: false
        },
        {
            name: "Daily Rental",
            price: "Rs.12,500/day",
            description: "Full day rental with driver (8 hours, 80km)",
            features: [
                "Professional chauffeur",
                "Fuel included",
                "Extra hours: Rs.1,500/hr",
                "Extra km: Rs.150/km"
            ],
            popular: true
        },
        {
            name: "Tour Package",
            price: "Rs.45,000",
            description: "3-day tour package covering major attractions",
            features: [
                "Customizable itinerary",
                "Hotel pickup/drop-off",
                "All fuel & tolls",
                "Driver accommodation"
            ],
            popular: false
        }
    ],
    reviews: [
        {
            name: "John Smith",
            avatar: "JS",
            rating: 5,
            date: "2 weeks ago",
            text: "Excellent service! The driver was professional and punctual. The vehicle was clean and comfortable. Highly recommended for anyone traveling in Sri Lanka."
        },
        {
            name: "Sarah Johnson",
            avatar: "SJ",
            rating: 5,
            date: "1 month ago",
            text: "Perfect for our family trip. The SUV was spacious and well-maintained. Our driver was knowledgeable about the area and very helpful."
        },
        {
            name: "Michael Chen",
            avatar: "MC",
            rating: 4,
            date: "2 months ago",
            text: "Great experience overall. The booking process was smooth and the vehicle arrived on time. Would use this service again."
        }
    ],
    overallRating: 4.9,
    ratingBreakdown: [
        { category: "Cleanliness", value: 5.0 },
        { category: "Comfort", value: 4.9 },
        { category: "Driver", value: 5.0 },
        { category: "Value", value: 4.7 }
    ]
};

// Current image index
let currentImageIndex = 0;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadTransportDetails();
    initializeImageGallery();
    setupDateRestrictions();
    setupFormListeners();
});

// Load transport details
function loadTransportDetails() {
    // Update title and basic info
    document.getElementById('transportTitle').textContent = transportData.name;
    document.getElementById('pickupLocation').textContent = transportData.location;
    document.getElementById('transportDescription').textContent = transportData.description;
    document.getElementById('priceAmount').textContent = `Rs.${transportData.basePrice.toLocaleString()}`;

    // Add badge
    const badgesContainer = document.getElementById('transportBadges');
    badgesContainer.innerHTML = `<span class="badge ${transportData.category}">${transportData.category}</span>`;

    // Load features
    loadFeatures();

    // Load specifications
    loadSpecifications();

    // Load pricing options
    loadPricingOptions();

    // Load reviews
    loadReviews();

    // Load rating breakdown
    loadRatingBreakdown();
}

// Initialize image gallery
function initializeImageGallery() {
    const thumbnailGallery = document.getElementById('thumbnailGallery');
    thumbnailGallery.innerHTML = '';

    transportData.images.forEach((image, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbnail.innerHTML = `<img src="${image}" alt="Transport Image ${index + 1}">`;
        thumbnail.onclick = () => showImage(index);
        thumbnailGallery.appendChild(thumbnail);
    });

    updateImageCounter();
}

// Show specific image
function showImage(index) {
    currentImageIndex = index;
    const mainImage = document.getElementById('mainHotelImage');
    mainImage.src = transportData.images[index];

    // Update thumbnails
    document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });

    updateImageCounter();
}

// Previous image
function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + transportData.images.length) % transportData.images.length;
    showImage(currentImageIndex);
}

// Next image
function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % transportData.images.length;
    showImage(currentImageIndex);
}

// Update image counter
function updateImageCounter() {
    document.getElementById('imageCounter').textContent = 
        `${currentImageIndex + 1} / ${transportData.images.length}`;
}

// Load features
function loadFeatures() {
    const featuresGrid = document.getElementById('featuresGrid');
    featuresGrid.innerHTML = '';

    transportData.features.forEach(feature => {
        const featureItem = document.createElement('div');
        featureItem.className = 'feature-item';
        featureItem.innerHTML = `
            <span class="feature-icon">${feature.icon}</span>
            <span class="feature-text">${feature.text}</span>
        `;
        featuresGrid.appendChild(featureItem);
    });
}

// Load specifications
function loadSpecifications() {
    const specsGrid = document.getElementById('specsGrid');
    specsGrid.innerHTML = '';

    transportData.specifications.forEach(spec => {
        const specItem = document.createElement('div');
        specItem.className = 'spec-item';
        specItem.innerHTML = `
            <div class="spec-label">${spec.label}</div>
            <div class="spec-value">${spec.value}</div>
        `;
        specsGrid.appendChild(specItem);
    });
}

// Load pricing options
function loadPricingOptions() {
    const pricingGrid = document.getElementById('pricingGrid');
    pricingGrid.innerHTML = '';

    transportData.pricingOptions.forEach(option => {
        const pricingOption = document.createElement('div');
        pricingOption.className = `pricing-option ${option.popular ? 'popular' : ''}`;
        
        const featuresHTML = option.features.map(feature => 
            `<div class="pricing-feature">${feature}</div>`
        ).join('');

        pricingOption.innerHTML = `
            <div class="pricing-header">
                <div class="pricing-name">${option.name}</div>
                <div class="pricing-price">${option.price}</div>
            </div>
            <div class="pricing-description">${option.description}</div>
            <div class="pricing-features">
                ${featuresHTML}
            </div>
        `;
        pricingGrid.appendChild(pricingOption);
    });
}

// Load reviews
function loadReviews() {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = '';

    document.getElementById('overallRating').textContent = transportData.overallRating;

    transportData.reviews.forEach(review => {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        
        const reviewItem = document.createElement('div');
        reviewItem.className = 'review-item';
        reviewItem.innerHTML = `
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">${review.avatar}</div>
                    <div class="reviewer-details">
                        <h4>${review.name}</h4>
                        <p>${review.date}</p>
                    </div>
                </div>
                <div class="review-rating">
                    <span class="review-stars">${stars}</span>
                </div>
            </div>
            <div class="review-text">${review.text}</div>
        `;
        reviewsList.appendChild(reviewItem);
    });
}

// Load rating breakdown
function loadRatingBreakdown() {
    const ratingBreakdown = document.getElementById('ratingBreakdown');
    ratingBreakdown.innerHTML = '';

    transportData.ratingBreakdown.forEach(rating => {
        const ratingItem = document.createElement('div');
        ratingItem.className = 'rating-item';
        ratingItem.innerHTML = `
            <span class="rating-category">${rating.category}</span>
            <div class="rating-bar">
                <div class="rating-fill" style="width: ${(rating.value / 5) * 100}%"></div>
            </div>
            <span class="rating-value">${rating.value}</span>
        `;
        ratingBreakdown.appendChild(ratingItem);
    });
}

// Setup date restrictions
function setupDateRestrictions() {
    const today = new Date().toISOString().split('T')[0];
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');

    pickupDate.min = today;
    returnDate.min = today;

    pickupDate.addEventListener('change', function() {
        returnDate.min = this.value;
        if (returnDate.value && returnDate.value < this.value) {
            returnDate.value = this.value;
        }
    });
}

// Setup form listeners
function setupFormListeners() {
    const serviceType = document.getElementById('serviceType');
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');

    serviceType.addEventListener('change', updatePriceDisplay);
    pickupDate.addEventListener('change', validateDates);
    returnDate.addEventListener('change', validateDates);
}

// Update price display based on service type
function updatePriceDisplay() {
    const serviceType = document.getElementById('serviceType').value;
    const priceAmount = document.getElementById('priceAmount');
    const pricePeriod = document.querySelector('.price-period');

    switch(serviceType) {
        case 'airport':
            priceAmount.textContent = 'Rs.8,500';
            pricePeriod.textContent = '/ transfer';
            break;
        case 'daily':
            priceAmount.textContent = 'Rs.12,500';
            pricePeriod.textContent = '/ day';
            break;
        case 'tour':
            priceAmount.textContent = 'Rs.45,000';
            pricePeriod.textContent = '/ 3 days';
            break;
        case 'custom':
            priceAmount.textContent = 'Contact Us';
            pricePeriod.textContent = '';
            break;
        default:
            priceAmount.textContent = 'Rs.12,500';
            pricePeriod.textContent = '/ day';
    }
}

// Validate dates
function validateDates() {
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;

    if (pickupDate && returnDate) {
        const pickup = new Date(pickupDate);
        const returnD = new Date(returnDate);

        if (returnD < pickup) {
            alert('Return date must be after pickup date');
            document.getElementById('returnDate').value = '';
        }
    }
}

// Calculate price
function calculatePrice() {
    const serviceType = document.getElementById('serviceType').value;
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;
    const pickupTime = document.getElementById('pickupTime').value;
    const returnTime = document.getElementById('returnTime').value;
    const pickupLocation = document.getElementById('pickupLocationInput').value;
    const dropoffLocation = document.getElementById('dropoffLocationInput').value;

    // Validation
    if (!serviceType || serviceType === '0') {
        alert('Please select a service type');
        return;
    }

    if (!pickupDate || !returnDate) {
        alert('Please select pickup and return dates');
        return;
    }

    if (!pickupTime || !returnTime) {
        alert('Please select pickup and return times');
        return;
    }

    if (!pickupLocation || !dropoffLocation) {
        alert('Please enter pickup and drop-off locations');
        return;
    }

    // Calculate duration
    const pickup = new Date(pickupDate);
    const returnD = new Date(returnDate);
    const duration = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24)) + 1;

    // Calculate price based on service type
    let basePrice = 0;
    switch(serviceType) {
        case 'airport':
            basePrice = 8500;
            break;
        case 'daily':
            basePrice = 12500 * duration;
            break;
        case 'tour':
            basePrice = 45000;
            break;
        case 'custom':
            basePrice = 15000 * duration;
            break;
    }

    const serviceCharge = Math.round(basePrice * 0.1); // 10% service charge
    const totalPrice = basePrice + serviceCharge;

    // Update summary
    document.getElementById('durationCount').textContent = `${duration} day${duration > 1 ? 's' : ''}`;
    document.getElementById('basePrice').textContent = `Rs.${basePrice.toLocaleString()}`;
    document.getElementById('serviceCharge').textContent = `Rs.${serviceCharge.toLocaleString()}`;
    document.getElementById('totalPrice').textContent = `Rs.${totalPrice.toLocaleString()}`;

    // Show summary and confirm button
    document.getElementById('bookingSummary').style.display = 'block';
    document.querySelector('.book-now-btn').style.display = 'none';
    document.querySelector('.confirm-booking-btn').style.display = 'block';
}

// Confirm booking - Redirect to booking process
async function confirmBooking() {
    const serviceType = document.getElementById('serviceType').value;
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;
    const pickupTime = document.getElementById('pickupTime').value;
    const returnTime = document.getElementById('returnTime').value;
    const pickupLocation = document.getElementById('pickupLocationInput').value;
    const dropoffLocation = document.getElementById('dropoffLocationInput').value;
    const passengers = document.getElementById('passengers').value;
    const luggage = document.getElementById('luggage').value;
    const specialRequirements = document.getElementById('specialRequirements').value;
    
    // Get prices from UI
    const basePriceText = document.getElementById('basePrice').textContent.replace(/Rs\.| /g, '').replace(/,/g, '');
    const serviceChargeText = document.getElementById('serviceCharge').textContent.replace(/Rs\.| /g, '').replace(/,/g, '');
    const totalPriceText = document.getElementById('totalPrice').textContent.replace(/Rs\.| /g, '').replace(/,/g, '');

    // Get vehicle ID from URL or data
    const urlParams = new URLSearchParams(window.location.search);
    const vehicleId = urlParams.get('id') || transportData.id;

    // Create booking object
    const bookingData = {
        vehicle_id: vehicleId,
        service_type: serviceType,
        pickup_date: pickupDate,
        pickup_time: pickupTime,
        return_date: returnDate,
        return_time: returnTime,
        pickup_location: pickupLocation,
        dropoff_location: dropoffLocation,
        passengers: parseInt(passengers),
        luggage: parseInt(luggage),
        special_requirements: specialRequirements,
        base_price: parseFloat(basePriceText),
        service_charge: parseFloat(serviceChargeText),
        total_price: parseFloat(totalPriceText)
    };

    console.log('Booking data:', bookingData);

    try {
        // Disable button
        const confirmBtn = document.querySelector('.confirm-booking-btn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Save booking data to session and redirect to booking details page
        const response = await fetch('/TravelMate/public/api/transport-booking/init-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingData),
            credentials: 'same-origin'
        });

        const result = await response.json();
        console.log('Init booking result:', result);

        if (result.success) {
            // Redirect to booking details page
            window.location.href = '/TravelMate/public/transport-booking-details';
        } else {
            const errorMsg = result.errors?.general || 
                           result.errors?.auth || 
                           result.errors?.availability ||
                           result.errors?.date ||
                           'Failed to initialize booking';
            alert('Error: ' + errorMsg);
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = 'Confirm Booking';
        }
    } catch (error) {
        console.error('Booking error:', error);
        alert('An error occurred while processing your booking. Please try again.');
        const confirmBtn = document.querySelector('.confirm-booking-btn');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Confirm Booking';
    }
}

// Show map (placeholder function)
function showMap() {
    alert('Map functionality coming soon!\nLocation: ' + transportData.location);
    // Implement Google Maps or other map integration here
}

// Keyboard navigation for image gallery
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        previousImage();
    } else if (e.key === 'ArrowRight') {
        nextImage();
    }
});