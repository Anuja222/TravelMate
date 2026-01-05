// Hotel Data
const hotelData = {
    id: 'hotel_001',
    name: 'Luxury Beach Resort',
    location: 'Bentota Beach, Sri Lanka',
    basePrice: 45000,
    description: 'Experience the ultimate in luxury at our beachfront resort in Bentota. With pristine beaches, world-class amenities, and exceptional service, this resort offers an unforgettable stay in paradise.',
    images: [
        'assets/images/luxuryhotel.png',
        'assets/images/luxuryhotel1.png',
        'assets/images/luxuryhotel2.png',
        'assets/images/luxuryhotel3.png'
    ],
    amenities: [
        { icon: '🏊', text: 'Swimming Pool' },
        { icon: '🍽️', text: 'Restaurant' },
        { icon: '🏋️', text: 'Fitness Center' },
        { icon: '🚗', text: 'Free Parking' },
        { icon: '📶', text: 'Free WiFi' },
        { icon: '🏖️', text: 'Beach Access' },
        { icon: '💆', text: 'Spa & Wellness' },
        { icon: '🍹', text: 'Bar/Lounge' }
    ],
    rooms: [
        {
            id: 'room_001',
            name: 'Deluxe Room',
            price: 45000,
            description: 'Spacious room with ocean view, king-size bed, and modern amenities',
            features: ['Ocean View', 'King Bed', '45 sqm', 'Max 2 Adults']
        },
        {
            id: 'room_002',
            name: 'Family Suite',
            price: 65000,
            description: 'Large suite perfect for families with separate living area',
            features: ['Ocean View', '2 Bedrooms', '75 sqm', 'Max 4 Adults + 2 Children']
        },
        {
            id: 'room_003',
            name: 'Premium Villa',
            price: 95000,
            description: 'Luxury villa with private pool and butler service',
            features: ['Private Pool', 'Butler Service', '120 sqm', 'Max 4 Adults']
        }
    ],
    reviews: [
        {
            name: 'John Doe',
            rating: 5,
            date: '2024-01-15',
            text: 'Amazing experience! The staff was incredibly helpful and the location is perfect.'
        },
        {
            name: 'Sarah Smith',
            rating: 4,
            date: '2024-01-10',
            text: 'Beautiful resort with excellent facilities. The beach is stunning.'
        }
    ]
};

// Current image index for gallery
let currentImageIndex = 0;

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    loadHotelData();
    initializeDatePickers();
    populateThumbnails();
    updateMainImage();
});

// Load hotel data into page
function loadHotelData() {
    // Set hotel title and location
    document.getElementById('hotelTitle').textContent = hotelData.name;
    document.getElementById('hotelLocation').textContent = hotelData.location;
    document.getElementById('hotelDescription').textContent = hotelData.description;
    document.getElementById('priceAmount').textContent = `Rs.${hotelData.basePrice.toLocaleString()}`;
    
    // Load amenities
    const amenitiesGrid = document.getElementById('amenitiesGrid');
    amenitiesGrid.innerHTML = '';
    hotelData.amenities.forEach(amenity => {
        const amenityItem = document.createElement('div');
        amenityItem.className = 'amenity-item';
        amenityItem.innerHTML = `
            <span class="amenity-icon">${amenity.icon}</span>
            <span class="amenity-text">${amenity.text}</span>
        `;
        amenitiesGrid.appendChild(amenityItem);
    });
    
    // Load room types
    const roomsGrid = document.getElementById('roomsGrid');
    const roomSelect = document.getElementById('roomType');
    roomsGrid.innerHTML = '';
    roomSelect.innerHTML = '<option value="">Select Room Type</option>';
    
    hotelData.rooms.forEach(room => {
        // Add to rooms grid
        const roomCard = document.createElement('div');
        roomCard.className = 'room-type';
        roomCard.innerHTML = `
            <div class="room-header">
                <h4 class="room-name">${room.name}</h4>
                <span class="room-price">Rs.${room.price.toLocaleString()}/night</span>
            </div>
            <p class="room-description">${room.description}</p>
            <div class="room-features">
                ${room.features.map(feature => `<span class="room-feature">${feature}</span>`).join('')}
            </div>
        `;
        roomsGrid.appendChild(roomCard);
        
        // Add to select dropdown
        const option = document.createElement('option');
        option.value = room.id;
        option.textContent = `${room.name} - Rs.${room.price.toLocaleString()}/night`;
        option.dataset.price = room.price;
        option.dataset.name = room.name;
        roomSelect.appendChild(option);
    });
    
    // Load reviews
    loadReviews();
}

// Load reviews
function loadReviews() {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = '';
    
    hotelData.reviews.forEach(review => {
        const reviewItem = document.createElement('div');
        reviewItem.className = 'review-item';
        reviewItem.innerHTML = `
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">${review.name.charAt(0)}</div>
                    <div class="reviewer-details">
                        <h4>${review.name}</h4>
                        <p>${new Date(review.date).toLocaleDateString()}</p>
                    </div>
                </div>
                <div class="review-rating">
                    <span class="review-stars">${'★'.repeat(review.rating)}</span>
                </div>
            </div>
            <p class="review-text">${review.text}</p>
        `;
        reviewsList.appendChild(reviewItem);
    });
}

// Initialize date pickers with minimum date as today
function initializeDatePickers() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const checkinDate = document.getElementById('checkinDate');
    const checkoutDate = document.getElementById('checkoutDate');
    
    // Set minimum dates
    checkinDate.min = today.toISOString().split('T')[0];
    checkinDate.value = today.toISOString().split('T')[0];
    
    checkoutDate.min = tomorrow.toISOString().split('T')[0];
    checkoutDate.value = tomorrow.toISOString().split('T')[0];
    
    // Update checkout min when checkin changes
    checkinDate.addEventListener('change', function() {
        const checkinValue = new Date(this.value);
        const minCheckout = new Date(checkinValue);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutDate.min = minCheckout.toISOString().split('T')[0];
        
        if (new Date(checkoutDate.value) <= checkinValue) {
            checkoutDate.value = minCheckout.toISOString().split('T')[0];
        }
    });
}

// Gallery functions
function populateThumbnails() {
    const thumbnailGallery = document.getElementById('thumbnailGallery');
    thumbnailGallery.innerHTML = '';
    
    hotelData.images.forEach((image, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbnail.onclick = () => {
            currentImageIndex = index;
            updateMainImage();
        };
        thumbnail.innerHTML = `<img src="${image}" alt="Hotel Image ${index + 1}">`;
        thumbnailGallery.appendChild(thumbnail);
    });
}

function updateMainImage() {
    const mainImage = document.getElementById('mainHotelImage');
    mainImage.src = hotelData.images[currentImageIndex];
    
    // Update counter
    document.getElementById('imageCounter').textContent = 
        `${currentImageIndex + 1} / ${hotelData.images.length}`;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.classList.toggle('active', index === currentImageIndex);
    });
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + hotelData.images.length) % hotelData.images.length;
    updateMainImage();
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % hotelData.images.length;
    updateMainImage();
}

// Calculate price and show summary
function calculatePrice() {
    const checkinDate = document.getElementById('checkinDate').value;
    const checkoutDate = document.getElementById('checkoutDate').value;
    const adults = document.getElementById('adults').value;
    const children = document.getElementById('children').value;
    const roomType = document.getElementById('roomType');
    
    // Validation
    if (!checkinDate || !checkoutDate || !roomType.value) {
        showValidationModal('Please fill in all required fields');
        return;
    }
    
    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
    
    if (nights <= 0) {
        showValidationModal('Please select valid check-in and check-out dates');
        return;
    }
    
    const selectedRoom = roomType.options[roomType.selectedIndex];
    const roomPrice = parseFloat(selectedRoom.dataset.price) || hotelData.basePrice;
    const roomName = selectedRoom.dataset.name || 'Standard Room';
    
    const basePrice = roomPrice * nights;
    const taxRate = 0.15; // 15% tax
    const taxes = basePrice * taxRate;
    const totalPrice = basePrice + taxes;
    
    // Update summary
    document.getElementById('nightsCount').textContent = nights;
    document.getElementById('basePrice').textContent = `Rs.${basePrice.toLocaleString()}`;
    document.getElementById('taxesFees').textContent = `Rs.${taxes.toLocaleString()}`;
    document.getElementById('totalPrice').textContent = `Rs.${totalPrice.toLocaleString()}`;
    
    // Show summary and confirm button
    document.getElementById('bookingSummary').style.display = 'block';
    document.querySelector('.book-now-btn').style.display = 'none';
    document.querySelector('.confirm-booking-btn').style.display = 'block';
    
    // Store preliminary booking data
    const bookingData = {
        hotelId: hotelData.id,
        hotelName: hotelData.name,
        hotelLocation: hotelData.location,
        roomId: roomType.value,
        roomName: roomName,
        roomPrice: roomPrice,
        checkinDate: checkinDate,
        checkoutDate: checkoutDate,
        nights: nights,
        adults: adults,
        children: children,
        basePrice: basePrice,
        taxes: taxes,
        totalPrice: totalPrice,
        timestamp: new Date().toISOString()
    };
    
    // Store in session storage temporarily
    sessionStorage.setItem('tempBooking', JSON.stringify(bookingData));
}

// Show validation modal
function showValidationModal(message) {
    const modal = document.getElementById('validationModal');
    const messageElement = document.getElementById('validationMessage');
    messageElement.textContent = message;
    modal.classList.add('show');
}

// Confirm booking and redirect
function confirmBooking() {
    const tempBooking = JSON.parse(sessionStorage.getItem('tempBooking'));
    
    if (!tempBooking) {
        showValidationModal('Please calculate price first');
        return;
    }
    
    // Store in localStorage for next page
    localStorage.setItem('currentBooking', JSON.stringify(tempBooking));
    
    // Redirect to availability page
    window.location.href = 'booking_availability';
}

// Show map (placeholder function)
function showMap() {
    // You can integrate with Google Maps or other map service
    alert('Map feature coming soon!');
}