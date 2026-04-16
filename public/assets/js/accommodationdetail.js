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

<<<<<<< HEAD
// Load accommodation data from API
async function loadAccommodationData(accommodationId) {
    try {
        console.log('Fetching accommodation data...');
        const baseUrl = getBaseUrl();
        const apiUrl = `${baseUrl}/api/accommodation/listAll`;
        console.log('API URL:', apiUrl);
        
        const response = await fetch(apiUrl);
        const result = await response.json();
        
        console.log('API Response:', result);
        
        if (!result.success || !result.data) {
            console.error('API returned error or no data');
            showValidationModal('Failed to load accommodation details');
            return;
        }
        
        // Find the specific accommodation or use first one if no ID
        let accommodation;
        if (accommodationId) {
            accommodation = result.data.find(acc => acc.id == accommodationId);
            console.log('Found accommodation by ID:', accommodation);
        } else {
            accommodation = result.data[0];
            console.log('Using first accommodation:', accommodation);
        }
        
        if (!accommodation) {
            console.error('Accommodation not found');
            showValidationModal('Accommodation not found. Redirecting...');
            setTimeout(() => {
                window.location.href = 'accommodation';
            }, 2000);
            return;
        }
        
        console.log('Processing accommodation:', accommodation);
        
        // Store accommodation data globally
        accommodationData = {
            id: accommodation.id,
            name: accommodation.title,
            location: accommodation.location || 'Sri Lanka',
            basePrice: parseFloat(accommodation.price_per_night) || 0,
            avgRating: parseFloat(accommodation.avg_rating || 0),
            ratingCount: parseInt(accommodation.rating_count || 0, 10) || 0,
            description: accommodation.description || 'No description available',
            images: (() => {
                // Use images array from API if available
                if (accommodation.images && Array.isArray(accommodation.images) && accommodation.images.length > 0) {
                    return accommodation.images.map(img => getBaseUrl() + '/' + img.image_path);
                }
                // Fallback to main_image if no gallery images
                else if (accommodation.main_image) {
                    return [getBaseUrl() + '/' + accommodation.main_image];
                }
                // Default fallback
                else {
                    return ['assets/images/default-accommodation.png'];
                }
            })(),
            rooms: parseInt(accommodation.rooms) || 1,
            bathrooms: parseInt(accommodation.bathrooms) || 1,
            maxGuests: parseInt(accommodation.max_guests) || 2,
            propertyType: accommodation.property_type || 'property'
        };
        
        console.log('Accommodation data prepared:', accommodationData);
        console.log('Images loaded:', accommodationData.images.length, 'images');
        
        // Load the data into the page
        populateAccommodationDetails();
        populateThumbnails();
        updateMainImage();
        populateReviewSummary();
        loadRoomAvailability();
        
        console.log('Page populated successfully');
        
    } catch (error) {
        console.error('Error loading accommodation:', error);
        showValidationModal('Error loading accommodation details');
    }
}

// Populate accommodation details into the page
function populateAccommodationDetails() {
    if (!accommodationData) {
        console.error('No accommodation data available');
        return;
    }
=======
// Load hotel data into page
function loadHotelData() {
    // Set hotel title and location
    document.getElementById('hotelTitle').textContent = hotelData.name;
    document.getElementById('hotelLocation').textContent = hotelData.location;
    document.getElementById('hotelDescription').textContent = hotelData.description;
    document.getElementById('priceAmount').textContent = `Rs.${hotelData.basePrice.toLocaleString()}`;
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
    
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
    
<<<<<<< HEAD
    // Create a standard room option based on the accommodation
    const roomCard = document.createElement('div');
    roomCard.className = 'room-type';
    roomCard.innerHTML = `
        <div class="room-header">
            <h4 class="room-name">Standard Room</h4>
            <span class="room-price">Rs.${accommodationData.basePrice.toLocaleString()}/night</span>
        </div>
        <p class="room-description">Comfortable accommodation with ${accommodationData.rooms} bedroom(s) and ${accommodationData.bathrooms} bathroom(s)</p>
        <div class="room-features">
            <span class="room-feature">${accommodationData.rooms} Bedroom(s)</span>
            <span class="room-feature">${accommodationData.bathrooms} Bathroom(s)</span>
            <span class="room-feature">Max ${accommodationData.maxGuests} Guests</span>
        </div>
    `;
    roomsGrid.appendChild(roomCard);
    
    // Add option to select dropdown
    const option = document.createElement('option');
    option.value = 'standard';
    option.textContent = `Standard Room - Rs.${accommodationData.basePrice.toLocaleString()}/night`;
    option.dataset.price = accommodationData.basePrice;
    option.dataset.name = 'Standard Room';
    roomSelect.appendChild(option);
    
    console.log('Room types updated');
    
    // Update adults dropdown based on max guests
    updateGuestsDropdown();
    
    console.log('Guests dropdown updated');
    console.log('Population complete!');
}

// Format property type
function formatPropertyType(type) {
    if (!type) return 'Property';
    return type.charAt(0).toUpperCase() + type.slice(1).replace(/_/g, ' ');
}

// Load room availability data
async function loadRoomAvailability() {
    if (!accommodationData || !accommodationData.id) {
        console.error('No accommodation data available for room availability');
        return;
    }

    try {
        const response = await fetch(`${getBaseUrl()}/api/accommodation/roomAvailability?id=${accommodationData.id}`);
        const result = await response.json();

        if (result.success && result.data) {
            // Update room counts
            document.getElementById('totalRooms').textContent = result.data.total_rooms;
            document.getElementById('availableRooms').textContent = result.data.available_rooms;
            document.getElementById('unavailableRooms').textContent = result.data.unavailable_rooms;

            // Update availability message
            const messageDiv = document.getElementById('availabilityMessage');
            const availableRooms = result.data.available_rooms;
            
            if (availableRooms === 0) {
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> No rooms available';
                messageDiv.className = 'availability-message error';
                messageDiv.style.display = 'flex';
            } else if (availableRooms <= 2) {
                messageDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Only ${availableRooms} room${availableRooms > 1 ? 's' : ''} left!`;
                messageDiv.className = 'availability-message warning';
                messageDiv.style.display = 'flex';
            } else {
                messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> Rooms available';
                messageDiv.className = 'availability-message success';
                messageDiv.style.display = 'flex';
            }

            // Populate number of rooms dropdown based on available rooms
            updateNumberOfRoomsDropdown(availableRooms);

            console.log('Room availability loaded:', result.data);
        } else {
            console.error('Failed to load room availability:', result.message);
        }
    } catch (error) {
        console.error('Error loading room availability:', error);
    }
}

// Update number of rooms dropdown based on available rooms
function updateNumberOfRoomsDropdown(availableRooms) {
    const selectElement = document.getElementById('numberOfRooms');
    if (!selectElement) return;

    // Clear existing options
    selectElement.innerHTML = '';

    // Add options based on available rooms
    if (availableRooms === 0) {
        const option = document.createElement('option');
        option.value = '0';
        option.textContent = 'No rooms available';
        option.disabled = true;
        selectElement.appendChild(option);
    } else {
        for (let i = 1; i <= availableRooms; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${i} Room${i > 1 ? 's' : ''}`;
            selectElement.appendChild(option);
        }
    }

    console.log(`Updated room dropdown with ${availableRooms} available rooms`);
}

// Update guests dropdown based on max capacity
function updateGuestsDropdown() {
    if (!accommodationData) return;
    
    const adultsSelect = document.getElementById('adults');
    adultsSelect.innerHTML = '';
    
    for (let i = 1; i <= accommodationData.maxGuests; i++) {
=======
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
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
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

function getRatingLabel(avg) {
    if (avg >= 4.5) return 'Excellent';
    if (avg >= 4.0) return 'Very Good';
    if (avg >= 3.0) return 'Good';
    if (avg > 0) return 'Fair';
    return 'Not yet rated';
}

function getRatingStarsHtml(avg) {
    let stars = '';
    for (let index = 1; index <= 5; index++) {
        if (avg >= index) {
            stars += '<i class="fas fa-star"></i>';
        } else if (avg >= index - 0.5) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

function populateReviewSummary() {
    if (!accommodationData) return;

    const overallRatingEl = document.getElementById('overallRating');
    const ratingLabelEl = document.querySelector('.overall-rating .rating-label');
    const ratingBreakdownEl = document.getElementById('ratingBreakdown');
    const reviewsListEl = document.getElementById('reviewsList');

    if (!overallRatingEl || !ratingLabelEl || !ratingBreakdownEl || !reviewsListEl) {
        return;
    }

    const ratingCount = accommodationData.ratingCount || 0;
    const avgRating = accommodationData.avgRating || 0;

    if (ratingCount > 0) {
        overallRatingEl.textContent = avgRating.toFixed(1);
        ratingLabelEl.textContent = getRatingLabel(avgRating);

        ratingBreakdownEl.innerHTML = `
            <div class="rating-item">
                <span class="rating-category">Overall</span>
                <span class="review-stars">${getRatingStarsHtml(avgRating)}</span>
                <span class="rating-value">${ratingCount}</span>
            </div>
        `;

        reviewsListEl.innerHTML = `
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar"><i class="fas fa-users"></i></div>
                        <div class="reviewer-details">
                            <h4>Traveler Ratings</h4>
                            <p>${ratingCount} review${ratingCount > 1 ? 's' : ''}</p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="review-stars">${getRatingStarsHtml(avgRating)}</span>
                    </div>
                </div>
                <p class="review-text">Average guest rating is ${avgRating.toFixed(1)} out of 5 based on ${ratingCount} submitted review${ratingCount > 1 ? 's' : ''}.</p>
            </div>
        `;
    } else {
        overallRatingEl.textContent = '-';
        ratingLabelEl.textContent = 'Not yet rated';
        ratingBreakdownEl.innerHTML = `
            <div class="rating-item">
                <span class="rating-category">Rating</span>
                <span class="rating-value">Not yet rated</span>
            </div>
        `;
        reviewsListEl.innerHTML = '<p style="text-align: center; color: #666;">No ratings yet for this accommodation.</p>';
    }
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
    const locationText = (accommodationData && accommodationData.location)
        ? String(accommodationData.location).trim()
        : '';

    if (!locationText) {
        showValidationModal('Location is not available for this accommodation.');
        return;
    }

    const mapModal = document.getElementById('mapModal');
    const mapFrame = document.getElementById('mapFrame');
    const mapTitle = document.getElementById('mapModalTitle');
    const mapExternalLink = document.getElementById('mapExternalLink');

    if (!mapModal || !mapFrame || !mapTitle) {
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(locationText)}`;
        window.open(mapsUrl, '_blank', 'noopener,noreferrer');
        return;
    }

    mapTitle.textContent = `Location: ${locationText}`;
    const externalUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(locationText)}`;
    mapFrame.src = `https://maps.google.com/maps?q=${encodeURIComponent(locationText)}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
    if (mapExternalLink) {
        mapExternalLink.href = externalUrl;
    }
    mapModal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMapModal() {
    const mapModal = document.getElementById('mapModal');
    const mapFrame = document.getElementById('mapFrame');

    if (mapModal) {
        mapModal.classList.remove('show');
    }
    if (mapFrame) {
        mapFrame.src = '';
    }

    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const mapModal = document.getElementById('mapModal');
    if (!mapModal) return;

    mapModal.addEventListener('click', function(event) {
        if (event.target === mapModal) {
            closeMapModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && mapModal.classList.contains('show')) {
            closeMapModal();
        }
    });
});