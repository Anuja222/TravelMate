// accommodation Detail Page JavaScript
console.log('=== Accommodation Detail JS Loaded ===');

// get base URL
function getBaseUrl() {
    const path = window.location.pathname;
    if (path.includes('/TravelMate')) {
        return '/TravelMate/public';
    }
    return '';
}

// get accommodation ID from URL
function getAccommodationId() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

// global accommodation data
let accommodationData = null;
let currentImageIndex = 0;

// initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    const accommodationId = getAccommodationId();
    console.log('Accommodation ID from URL:', accommodationId);
    
    if (!accommodationId) {
        console.warn('No accommodation ID found in URL');
        // load first available accommodation as fallback for testing
        loadAccommodationData(null);
        initializeDatePickers();
        return;
    }
    
    loadAccommodationData(accommodationId);
    initializeDatePickers();
});

// load accommodation data from API
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
        
        // find the specific accommodation or use first one if no ID
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
        
        // store accommodation data globally
        accommodationData = {
            id: accommodation.id,
            name: accommodation.title,
            location: accommodation.location || 'Sri Lanka',
            basePrice: parseFloat(accommodation.price_per_night) || 0,
            avgRating: parseFloat(accommodation.avg_rating || 0),
            ratingCount: parseInt(accommodation.rating_count || 0, 10) || 0,
            description: accommodation.description || 'No description available',
            images: (() => {
                // use images array from API if available
                if (accommodation.images && Array.isArray(accommodation.images) && accommodation.images.length > 0) {
                    return accommodation.images.map(img => getBaseUrl() + '/' + img.image_path);
                }
                // fallback to main_image if no gallery images
                else if (accommodation.main_image) {
                    return [getBaseUrl() + '/' + accommodation.main_image];
                }
                // default fallback
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
        
        // load the data into the page
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

// populate accommodation details into the page
function populateAccommodationDetails() {
    if (!accommodationData) {
        console.error('No accommodation data available');
        return;
    }
    
    console.log('Populating page with accommodation details...');
    
    // set basic details
    document.getElementById('hotelTitle').textContent = accommodationData.name;
    document.getElementById('hotelLocation').textContent = accommodationData.location;
    document.getElementById('hotelDescription').textContent = accommodationData.description;
    document.getElementById('priceAmount').textContent = `Rs.${accommodationData.basePrice.toLocaleString()}`;
    
    console.log('Basic details updated');
    
    // set property type badge
    const badgesContainer = document.getElementById('hotelBadges');
    badgesContainer.innerHTML = `<span class="badge">${formatPropertyType(accommodationData.propertyType)}</span>`;
    
    console.log('Badge updated');
    
    // load amenities based on accommodation data
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
    
    // load room types (simplified - using property rooms as room types)
    const roomsGrid = document.getElementById('roomsGrid');
    const roomSelect = document.getElementById('roomType');
    roomsGrid.innerHTML = '';
    roomSelect.innerHTML = '<option value="">Select Room Type</option>';
    
    // create a standard room option based on the accommodation
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
    
    // add option to select dropdown
    const option = document.createElement('option');
    option.value = 'standard';
    option.textContent = `Standard Room - Rs.${accommodationData.basePrice.toLocaleString()}/night`;
    option.dataset.price = accommodationData.basePrice;
    option.dataset.name = 'Standard Room';
    roomSelect.appendChild(option);
    
    console.log('Room types updated');
    
    // update adults dropdown based on max guests
    updateGuestsDropdown();
    
    console.log('Guests dropdown updated');
    console.log('Population complete!');
}

// format property type
function formatPropertyType(type) {
    if (!type) return 'Property';
    return type.charAt(0).toUpperCase() + type.slice(1).replace(/_/g, ' ');
}

// load room availability data
async function loadRoomAvailability() {
    if (!accommodationData || !accommodationData.id) {
        console.error('No accommodation data available for room availability');
        return;
    }

    try {
        const response = await fetch(`${getBaseUrl()}/api/accommodation/roomAvailability?id=${accommodationData.id}`);
        const result = await response.json();

        if (result.success && result.data) {
            // update room counts
            document.getElementById('totalRooms').textContent = result.data.total_rooms;
            document.getElementById('availableRooms').textContent = result.data.available_rooms;
            document.getElementById('unavailableRooms').textContent = result.data.unavailable_rooms;

            // update availability message
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

            // populate number of rooms dropdown based on available rooms
            updateNumberOfRoomsDropdown(availableRooms);

            console.log('Room availability loaded:', result.data);
        } else {
            console.error('Failed to load room availability:', result.message);
        }
    } catch (error) {
        console.error('Error loading room availability:', error);
    }
}

// update number of rooms dropdown based on available rooms
function updateNumberOfRoomsDropdown(availableRooms) {
    const selectElement = document.getElementById('numberOfRooms');
    if (!selectElement) return;

    // clear existing options
    selectElement.innerHTML = '';

    // add options based on available rooms
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

// update guests dropdown based on max capacity
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

// load hotel data into page (deprecated - now using populateAccommodationDetails)
function loadHotelData() {
    // this function is no longer used - data is loaded via API
    console.log('Using dynamic data loading');
}

// load reviews (placeholder - can be extended later)
function loadReviews() {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = '<p style="text-align: center; color: #666;">No reviews yet. Be the first to review!</p>';
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

// initialize date pickers with minimum date as today
function initializeDatePickers() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const checkinDate = document.getElementById('checkinDate');
    const checkoutDate = document.getElementById('checkoutDate');
    
    // set minimum dates
    checkinDate.min = today.toISOString().split('T')[0];
    checkinDate.value = today.toISOString().split('T')[0];
    
    checkoutDate.min = tomorrow.toISOString().split('T')[0];
    checkoutDate.value = tomorrow.toISOString().split('T')[0];
    
    // update checkout min when checkin changes
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

// gallery functions
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
    
    // update counter
    document.getElementById('imageCounter').textContent = 
        `${currentImageIndex + 1} / ${accommodationData.images.length}`;
    
    // update active thumbnail
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

// calculate price and show summary
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
    
    // validation
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
    
    // calculate pricing
    const basePrice = roomPrice * nights * rooms;
    const taxRate = 0.15; // 15% tax and service charge
    const taxes = Math.round(basePrice * taxRate);
    const totalPrice = basePrice + taxes;
    
    // validate guest count
    const totalGuests = parseInt(adults) + parseInt(children || 0);
    if (totalGuests > accommodationData.maxGuests) {
        showValidationModal(`This property can accommodate maximum ${accommodationData.maxGuests} guests`);
        return;
    }
    
    // update summary display
    document.getElementById('nightsCount').textContent = `${nights} night${nights > 1 ? 's' : ''}`;
    document.getElementById('roomsCount').textContent = `${rooms} room${rooms > 1 ? 's' : ''}`;
    document.getElementById('basePrice').textContent = `Rs.${basePrice.toLocaleString()}`;
    document.getElementById('taxesFees').textContent = `Rs.${taxes.toLocaleString()}`;
    document.getElementById('totalPrice').textContent = `Rs.${totalPrice.toLocaleString()}`;
    
    // Calculate and display 30% payable amount
    const payableAmount = totalPrice * 0.3;
    document.getElementById('payablePrice').textContent = `Rs.${payableAmount.toLocaleString()}`;
    
    // show summary and confirm button
    document.getElementById('bookingSummary').style.display = 'block';
    document.querySelector('.book-now-btn').style.display = 'none';
    document.querySelector('.confirm-booking-btn').style.display = 'block';
    
    // store booking data for submission
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
    
    // store in session storage
    sessionStorage.setItem('pendingBooking', JSON.stringify(bookingData));
    
    console.log('Booking calculation complete:', bookingData);
}

// show validation modal
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

// confirm booking and proceed to booking flow
function confirmBooking() {
    const bookingData = JSON.parse(sessionStorage.getItem('pendingBooking'));
    
    if (!bookingData) {
        showValidationModal('Please calculate the price first');
        return;
    }
    
    console.log('=== Confirm Booking Called ===');
    console.log('Pending booking data from sessionStorage:', bookingData);
    console.log('AccommodationId:', bookingData.accommodationId);
    
    // prepare booking data for multi-step booking process
    const currentBooking = {
        // booking step tracker (2 = details, 3 = payment, 4 = final)
        bookingStep: 2,
        
        // accommodation details
        accommodationId: bookingData.accommodationId,
        accommodationName: bookingData.accommodationName || 'Accommodation',
        
        // room details
        roomId: bookingData.roomId,
        roomName: bookingData.roomName,
        roomPrice: bookingData.roomPrice,
        numberOfRooms: bookingData.numberOfRooms,
        
        // stay details
        checkinDate: bookingData.checkinDate,
        checkoutDate: bookingData.checkoutDate,
        nights: bookingData.nights,
        adults: bookingData.adults,
        children: bookingData.children || 0,
        
        // pricing details
        basePrice: bookingData.basePrice,
        taxes: bookingData.taxes,
        totalPrice: bookingData.totalPrice,
        discount: 0,
        
        // status
        bookingStatus: 'pending',
        paymentStatus: 'pending',
        
        // timestamps
        reservedAt: new Date().toISOString()
    };
    
    console.log('CurrentBooking object created:', currentBooking);
    console.log('AccommodationId in currentBooking:', currentBooking.accommodationId);
    
    // save to localStorage for the booking flow
    localStorage.setItem('currentBooking', JSON.stringify(currentBooking));
    console.log('Saved to localStorage');
    
    // clear session storage
    sessionStorage.removeItem('pendingBooking');
    
    // redirect to booking details page
    console.log('Redirecting to booking_details...');
    window.location.href = 'booking_details';
}

// show success modal
function showSuccessModal(message) {
    const modal = document.getElementById('validationModal');
    const messageElement = document.getElementById('validationMessage');
    const iconSvg = modal.querySelector('.validation-icon svg circle');
    const pathElement = modal.querySelector('.validation-icon svg path');
    
    // change colors to green for success
    if (iconSvg) iconSvg.setAttribute('stroke', '#1abc5b');
    if (pathElement) pathElement.setAttribute('stroke', '#1abc5b');
    
    messageElement.textContent = message;
    modal.querySelector('h2').textContent = 'Success!';
    modal.classList.add('show');
    
    // reset colors after modal closes
    setTimeout(() => {
        if (iconSvg) iconSvg.setAttribute('stroke', '#f59e0b');
        if (pathElement) pathElement.setAttribute('stroke', '#f59e0b');
        modal.querySelector('h2').textContent = 'Incomplete Information';
    }, 3000);
}

// show map (placeholder function)
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