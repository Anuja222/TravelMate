// Accommodation Detail Page JavaScript
console.log('=== Accommodation Detail JS Loaded ===');

// Get base URL
function getBaseUrl() {
    const path = window.location.pathname;
    if (path.includes('/TravelMate')) {
        return '/TravelMate/public';
    }
    return '';
}

// Get accommodation ID from URL
function getAccommodationId() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

// Global accommodation data
let accommodationData = null;
let currentImageIndex = 0;

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    const accommodationId = getAccommodationId();
    console.log('Accommodation ID from URL:', accommodationId);
    
    if (!accommodationId) {
        console.warn('No accommodation ID found in URL');
        // Load first available accommodation as fallback for testing
        loadAccommodationData(null);
        initializeDatePickers();
        return;
    }
    
    loadAccommodationData(accommodationId);
    initializeDatePickers();
});

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
    
    console.log('Populating page with accommodation details...');
    
    // Set basic details
    document.getElementById('hotelTitle').textContent = accommodationData.name;
    document.getElementById('hotelLocation').textContent = accommodationData.location;
    document.getElementById('hotelDescription').textContent = accommodationData.description;
    document.getElementById('priceAmount').textContent = `Rs.${accommodationData.basePrice.toLocaleString()}`;
    
    console.log('Basic details updated');
    
    // Set property type badge
    const badgesContainer = document.getElementById('hotelBadges');
    badgesContainer.innerHTML = `<span class="badge">${formatPropertyType(accommodationData.propertyType)}</span>`;
    
    console.log('Badge updated');
    
    // Load amenities based on accommodation data
    const amenitiesGrid = document.getElementById('amenitiesGrid');
    amenitiesGrid.innerHTML = `
        <div class="amenity-item">
            <span class="amenity-icon">🛏️</span>
            <span class="amenity-text">${accommodationData.rooms} Bedroom${accommodationData.rooms > 1 ? 's' : ''}</span>
        </div>
        <div class="amenity-item">
            <span class="amenity-icon">🚿</span>
            <span class="amenity-text">${accommodationData.bathrooms} Bathroom${accommodationData.bathrooms > 1 ? 's' : ''}</span>
        </div>
        <div class="amenity-item">
            <span class="amenity-icon">👥</span>
            <span class="amenity-text">Up to ${accommodationData.maxGuests} Guests</span>
        </div>
        <div class="amenity-item">
            <span class="amenity-icon">📶</span>
            <span class="amenity-text">Free WiFi</span>
        </div>
        <div class="amenity-item">
            <span class="amenity-icon">🚗</span>
            <span class="amenity-text">Free Parking</span>
        </div>
        <div class="amenity-item">
            <span class="amenity-icon">🍽️</span>
            <span class="amenity-text">Kitchen</span>
        </div>
    `;
    
    console.log('Amenities updated');
    
    // Load room types (simplified - using property rooms as room types)
    const roomsGrid = document.getElementById('roomsGrid');
    const roomSelect = document.getElementById('roomType');
    roomsGrid.innerHTML = '';
    roomSelect.innerHTML = '<option value="">Select Room Type</option>';
    
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

// Update guests dropdown based on max capacity
function updateGuestsDropdown() {
    if (!accommodationData) return;
    
    const adultsSelect = document.getElementById('adults');
    adultsSelect.innerHTML = '';
    
    for (let i = 1; i <= accommodationData.maxGuests; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${i} Adult${i > 1 ? 's' : ''}`;
        adultsSelect.appendChild(option);
    }
}

// Load hotel data into page (deprecated - now using populateAccommodationDetails)
function loadHotelData() {
    // This function is no longer used - data is loaded via API
    console.log('Using dynamic data loading');
}

// Load reviews (placeholder - can be extended later)
function loadReviews() {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = '<p style="text-align: center; color: #666;">No reviews yet. Be the first to review!</p>';
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
    if (!accommodationData) return;
    
    const thumbnailGallery = document.getElementById('thumbnailGallery');
    thumbnailGallery.innerHTML = '';
    
    accommodationData.images.forEach((image, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbnail.onclick = () => {
            currentImageIndex = index;
            updateMainImage();
        };
        thumbnail.innerHTML = `<img src="${image}" alt="Property Image ${index + 1}" onerror="this.src='assets/images/default-accommodation.png'">`;
        thumbnailGallery.appendChild(thumbnail);
    });
}

function updateMainImage() {
    if (!accommodationData) return;
    
    const mainImage = document.getElementById('mainHotelImage');
    mainImage.src = accommodationData.images[currentImageIndex];
    mainImage.onerror = function() {
        this.src = 'assets/images/default-accommodation.png';
    };
    
    // Update counter
    document.getElementById('imageCounter').textContent = 
        `${currentImageIndex + 1} / ${accommodationData.images.length}`;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.classList.toggle('active', index === currentImageIndex);
    });
}

function previousImage() {
    if (!accommodationData) return;
    currentImageIndex = (currentImageIndex - 1 + accommodationData.images.length) % accommodationData.images.length;
    updateMainImage();
}

function nextImage() {
    if (!accommodationData) return;
    currentImageIndex = (currentImageIndex + 1) % accommodationData.images.length;
    updateMainImage();
}

// Calculate price and show summary
function calculatePrice() {
    if (!accommodationData) {
        showValidationModal('Accommodation data not loaded');
        return;
    }
    
    const checkinDate = document.getElementById('checkinDate').value;
    const checkoutDate = document.getElementById('checkoutDate').value;
    const adults = document.getElementById('adults').value;
    const children = document.getElementById('children').value;
    const roomType = document.getElementById('roomType');
    const numberOfRooms = document.getElementById('numberOfRooms').value;
    
    // Validation
    if (!checkinDate || !checkoutDate || !roomType.value || !numberOfRooms) {
        showValidationModal('Please fill in all required fields: Check-in date, Check-out date, Room type, and Number of rooms');
        return;
    }
    
    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
    
    if (nights <= 0) {
        showValidationModal('Check-out date must be after check-in date');
        return;
    }
    
    const selectedRoom = roomType.options[roomType.selectedIndex];
    const roomPrice = parseFloat(selectedRoom.dataset.price) || accommodationData.basePrice;
    const roomName = selectedRoom.dataset.name || 'Standard Room';
    const rooms = parseInt(numberOfRooms);
    
    // Calculate pricing
    const basePrice = roomPrice * nights * rooms;
    const taxRate = 0.15; // 15% tax and service charge
    const taxes = Math.round(basePrice * taxRate);
    const totalPrice = basePrice + taxes;
    
    // Validate guest count
    const totalGuests = parseInt(adults) + parseInt(children || 0);
    if (totalGuests > accommodationData.maxGuests) {
        showValidationModal(`This property can accommodate maximum ${accommodationData.maxGuests} guests`);
        return;
    }
    
    // Update summary display
    document.getElementById('nightsCount').textContent = `${nights} night${nights > 1 ? 's' : ''}`;
    document.getElementById('roomsCount').textContent = `${rooms} room${rooms > 1 ? 's' : ''}`;
    document.getElementById('basePrice').textContent = `Rs.${basePrice.toLocaleString()}`;
    document.getElementById('taxesFees').textContent = `Rs.${taxes.toLocaleString()}`;
    document.getElementById('totalPrice').textContent = `Rs.${totalPrice.toLocaleString()}`;
    
    // Show summary and confirm button
    document.getElementById('bookingSummary').style.display = 'block';
    document.querySelector('.book-now-btn').style.display = 'none';
    document.querySelector('.confirm-booking-btn').style.display = 'block';
    
    // Store booking data for submission
    const bookingData = {
        accommodationId: accommodationData.id,
        accommodationName: accommodationData.name,
        location: accommodationData.location,
        roomId: roomType.value,
        roomName: roomName,
        roomPrice: roomPrice,
        numberOfRooms: rooms,
        checkinDate: checkinDate,
        checkoutDate: checkoutDate,
        nights: nights,
        adults: parseInt(adults),
        children: parseInt(children || 0),
        basePrice: basePrice,
        taxes: taxes,
        totalPrice: totalPrice,
        propertyType: accommodationData.propertyType,
        timestamp: new Date().toISOString()
    };
    
    // Store in session storage
    sessionStorage.setItem('pendingBooking', JSON.stringify(bookingData));
    
    console.log('Booking calculation complete:', bookingData);
}

// Show validation modal
function showValidationModal(message) {
    const modal = document.getElementById('validationModal');
    const messageElement = document.getElementById('validationMessage');
    
    if (!modal || !messageElement) {
        console.error('Validation modal elements not found, using alert fallback');
        alert(message);
        return;
    }
    
    messageElement.textContent = message;
    modal.classList.add('show');
}

// Confirm booking and proceed to booking flow
function confirmBooking() {
    const bookingData = JSON.parse(sessionStorage.getItem('pendingBooking'));
    
    if (!bookingData) {
        showValidationModal('Please calculate the price first');
        return;
    }
    
    console.log('=== Confirm Booking Called ===');
    console.log('Pending booking data from sessionStorage:', bookingData);
    console.log('AccommodationId:', bookingData.accommodationId);
    
    // Prepare booking data for multi-step booking process
    const currentBooking = {
        // Booking step tracker (2 = details, 3 = payment, 4 = final)
        bookingStep: 2,
        
        // Accommodation details
        accommodationId: bookingData.accommodationId,
        accommodationName: bookingData.accommodationName || 'Accommodation',
        
        // Room details
        roomId: bookingData.roomId,
        roomName: bookingData.roomName,
        roomPrice: bookingData.roomPrice,
        numberOfRooms: bookingData.numberOfRooms,
        
        // Stay details
        checkinDate: bookingData.checkinDate,
        checkoutDate: bookingData.checkoutDate,
        nights: bookingData.nights,
        adults: bookingData.adults,
        children: bookingData.children || 0,
        
        // Pricing details
        basePrice: bookingData.basePrice,
        taxes: bookingData.taxes,
        totalPrice: bookingData.totalPrice,
        discount: 0,
        
        // Status
        bookingStatus: 'pending',
        paymentStatus: 'pending',
        
        // Timestamps
        reservedAt: new Date().toISOString()
    };
    
    console.log('CurrentBooking object created:', currentBooking);
    console.log('AccommodationId in currentBooking:', currentBooking.accommodationId);
    
    // Save to localStorage for the booking flow
    localStorage.setItem('currentBooking', JSON.stringify(currentBooking));
    console.log('Saved to localStorage');
    
    // Clear session storage
    sessionStorage.removeItem('pendingBooking');
    
    // Redirect to booking details page
    console.log('Redirecting to booking_details...');
    window.location.href = 'booking_details';
}

// Show success modal
function showSuccessModal(message) {
    const modal = document.getElementById('validationModal');
    const messageElement = document.getElementById('validationMessage');
    const iconSvg = modal.querySelector('.validation-icon svg circle');
    const pathElement = modal.querySelector('.validation-icon svg path');
    
    // Change colors to green for success
    if (iconSvg) iconSvg.setAttribute('stroke', '#1abc5b');
    if (pathElement) pathElement.setAttribute('stroke', '#1abc5b');
    
    messageElement.textContent = message;
    modal.querySelector('h2').textContent = 'Success!';
    modal.classList.add('show');
    
    // Reset colors after modal closes
    setTimeout(() => {
        if (iconSvg) iconSvg.setAttribute('stroke', '#f59e0b');
        if (pathElement) pathElement.setAttribute('stroke', '#f59e0b');
        modal.querySelector('h2').textContent = 'Incomplete Information';
    }, 3000);
}

// Show map (placeholder function)
function showMap() {
    // You can integrate with Google Maps or other map service
    alert('Map feature coming soon!');
}