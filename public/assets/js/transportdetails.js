let currentVehicle = null;
let currentImageIndex = 0;
let vehicleImages = [];
let calculatedBooking = null;

document.addEventListener('DOMContentLoaded', async function () {
    await loadTransportDetails();
    setupDateRestrictions();
    setupFormListeners();
});

function getBaseUrl() {
    const path = window.location.pathname;
    if (path.includes('/TravelMate/public')) return '/TravelMate/public';
    if (path.includes('/public')) {
        const parts = path.split('/');
        const publicIdx = parts.indexOf('public');
        return parts.slice(0, publicIdx + 1).join('/');
    }
    return '/TravelMate/public';
}

function getVehicleIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const id = parseInt(params.get('id'), 10);
    return Number.isFinite(id) ? id : null;
}

function formatCurrency(value) {
    return `Rs.${Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })}`;
}

function parseDocsImages(vehicle) {
    const baseUrl = getBaseUrl();
    const images = [];

    if (vehicle.main_image) {
        images.push(vehicle.main_image.startsWith('/') ? baseUrl + vehicle.main_image : vehicle.main_image);
    }

    if (Array.isArray(vehicle.documents)) {
        vehicle.documents
            .filter(doc => doc.doc_type === 'vehicle_photos' && doc.file_path)
            .forEach(doc => {
                const full = doc.file_path.startsWith('/') ? baseUrl + doc.file_path : doc.file_path;
                if (!images.includes(full)) images.push(full);
            });
    }

    if (images.length === 0) images.push('assets/images/default-vehicle.png');
    return images;
}

function getServiceMultiplier(serviceType) {
    switch (serviceType) {
        case 'airport':
            return 1.0;
        case 'tour':
            return 1.2;
        case 'custom':
            return 1.1;
        case 'daily':
        default:
            return 1.0;
    }
}

function getTransportCategory(vehicleType) {
    const type = (vehicleType || '').toLowerCase();
    if (['luxury', 'suv'].includes(type)) return 'luxury';
    if (['bus', 'van'].includes(type)) return 'express';
    if (['tuk', 'tuk-tuk', 'three-wheeler'].includes(type)) return 'cultural';
    return 'scenic';
}

async function loadTransportDetails() {
    const vehicleId = getVehicleIdFromUrl();
    const baseUrl = getBaseUrl();

    try {
        const response = await fetch(`${baseUrl}/api/vehicle/listAll`, { credentials: 'same-origin' });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const result = await response.json();
        if (!result.success || !Array.isArray(result.data) || result.data.length === 0) {
            alert('No transport data available.');
            return;
        }

        currentVehicle = vehicleId
            ? result.data.find(v => Number(v.id) === Number(vehicleId))
            : result.data[0];

        if (!currentVehicle) {
            alert('Selected transport not found.');
            return;
        }

        vehicleImages = parseDocsImages(currentVehicle);
        currentImageIndex = 0;

        renderBasicDetails();
        renderGallery();
        renderFeatures();
        renderSpecifications();
        renderPricingOptions();
        renderReviews();
    } catch (error) {
        console.error('Failed to load transport details:', error);
        alert('Failed to load transport details. Please try again.');
    }
}

function renderBasicDetails() {
    if (!currentVehicle) return;

    const model = currentVehicle.vehicle_model || 'Vehicle';
    const type = currentVehicle.vehicle_type || 'Transport';
    const district = currentVehicle.working_district || 'Sri Lanka';
    const year = currentVehicle.vehicle_year || 'N/A';
    const color = currentVehicle.vehicle_color || 'N/A';
    const ac = currentVehicle.ac_type === 'ac' ? 'A/C' : 'Non-A/C';
    const passengers = currentVehicle.passenger_count || 'N/A';
    const rate = Number(currentVehicle.cost_per_km || 0);

    document.getElementById('transportTitle').textContent = `${model} - ${type}`;
    document.getElementById('defaultLocation').textContent = district;
    document.getElementById('transportDescription').textContent =
        `Book ${model} (${year}, ${color}) with ${passengers} passenger capacity and ${ac}. Pricing is specific to this vehicle and your total updates automatically based on booked days.`;

    document.getElementById('priceAmount').textContent = formatCurrency(rate);
    document.querySelector('.price-period').textContent = '/ day';

    const badgesContainer = document.getElementById('transportBadges');
    const category = getTransportCategory(type);
    badgesContainer.innerHTML = `<span class="badge ${category}">${type}</span>`;

    // provider Details
    const providerName = currentVehicle.first_name ? `${currentVehicle.first_name} ${currentVehicle.last_name || ''}`.trim() : 'N/A';
    const providerPhone = currentVehicle.phone || 'N/A';
    const providerEmail = currentVehicle.email || 'N/A';
    let providerImage = currentVehicle.profile_image || 'assets/images/default-profile.png';
    
    // ensure correct path if it doesn't start with http or /
    if (providerImage && providerImage !== 'assets/images/default-profile.png' && !providerImage.startsWith('http') && !providerImage.startsWith('/')) {
        const base = window.location.pathname.substring(0, window.location.pathname.indexOf('/TransportDetails'));
        providerImage = base + '/' + providerImage;
    }

    const nameEl = document.getElementById('providerName');
    if (nameEl) nameEl.textContent = providerName;
    
    const phoneEl = document.getElementById('providerPhone');
    if (phoneEl) {
         phoneEl.innerHTML = `<i class="fas fa-phone"></i> ${providerPhone}`;
    }
    
    const emailEl = document.getElementById('providerEmail');
    if (emailEl) {
         emailEl.innerHTML = `<i class="fas fa-envelope"></i> ${providerEmail}`;
    }

    const imgEl = document.getElementById('providerImage');
    if (imgEl && currentVehicle.profile_image) {
         const base = getBaseUrl();
         imgEl.src = base + '/' + currentVehicle.profile_image;
         imgEl.onerror = function() { this.src = 'assets/images/default-profile.png'; };
    }
}

function renderGallery() {
    const mainImage = document.getElementById('mainTransportImage');
    const thumbnailGallery = document.getElementById('thumbnailGallery');

    if (!mainImage || !thumbnailGallery || vehicleImages.length === 0) return;

    mainImage.src = vehicleImages[currentImageIndex];
    mainImage.onerror = function () { this.src = 'assets/images/default-vehicle.png'; };

    thumbnailGallery.innerHTML = '';
    vehicleImages.forEach((image, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `thumbnail ${index === currentImageIndex ? 'active' : ''}`;
        thumbnail.innerHTML = `<img src="${image}" alt="Transport ${index + 1}" onerror="this.src='assets/images/default-vehicle.png'">`;
        thumbnail.onclick = () => showImage(index);
        thumbnailGallery.appendChild(thumbnail);
    });

    updateImageCounter();
}

function showImage(index) {
    if (!vehicleImages.length) return;
    currentImageIndex = index;
    const mainImage = document.getElementById('mainTransportImage');
    if (mainImage) {
        mainImage.src = vehicleImages[index];
        mainImage.onerror = function () { this.src = 'assets/images/default-vehicle.png'; };
    }

    document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });

    updateImageCounter();
}

function previousImage() {
    if (!vehicleImages.length) return;
    currentImageIndex = (currentImageIndex - 1 + vehicleImages.length) % vehicleImages.length;
    showImage(currentImageIndex);
}

function nextImage() {
    if (!vehicleImages.length) return;
    currentImageIndex = (currentImageIndex + 1) % vehicleImages.length;
    showImage(currentImageIndex);
}

function updateImageCounter() {
    const counter = document.getElementById('imageCounter');
    if (!counter) return;
    counter.textContent = `${currentImageIndex + 1} / ${Math.max(vehicleImages.length, 1)}`;
}

function renderFeatures() {
    if (!currentVehicle) return;
    const featuresGrid = document.getElementById('featuresGrid');
    if (!featuresGrid) return;

    const features = [
        { icon: '👥', text: `${currentVehicle.passenger_count || 'N/A'} Passengers` },
        { icon: currentVehicle.ac_type === 'ac' ? '❄️' : '🌤️', text: currentVehicle.ac_type === 'ac' ? 'Air Conditioning' : 'Non-A/C' },
        { icon: '🧾', text: 'Licensed Vehicle' },
        { icon: '🛡️', text: 'Safety Verified' },
        { icon: '⏰', text: 'On-time Service' },
        { icon: '👨‍✈️', text: 'Experienced Driver' }
    ];

    featuresGrid.innerHTML = features.map(feature => `
        <div class="feature-item">
            <span class="feature-icon">${feature.icon}</span>
            <span class="feature-text">${feature.text}</span>
        </div>
    `).join('');
}

function renderSpecifications() {
    if (!currentVehicle) return;
    const specsGrid = document.getElementById('specsGrid');
    if (!specsGrid) return;

    const specs = [
        { label: 'Vehicle Type', value: currentVehicle.vehicle_type || 'N/A' },
        { label: 'Model', value: currentVehicle.vehicle_model || 'N/A' },
        { label: 'Year', value: currentVehicle.vehicle_year || 'N/A' },
        { label: 'Color', value: currentVehicle.vehicle_color || 'N/A' },
        { label: 'Vehicle Number', value: currentVehicle.vehicle_number || 'N/A' },
        { label: 'Base Location', value: currentVehicle.working_district || 'N/A' }
    ];

    specsGrid.innerHTML = specs.map(spec => `
        <div class="spec-item">
            <div class="spec-label">${spec.label}</div>
            <div class="spec-value">${spec.value}</div>
        </div>
    `).join('');
}

function renderPricingOptions() {
    if (!currentVehicle) return;
    const pricingGrid = document.getElementById('pricingGrid');
    if (!pricingGrid) return;

    const rate = Number(currentVehicle.cost_per_km || 0);
    const dayRate = rate;
    const threeDay = dayRate * 3;
    const weekRate = dayRate * 7;

    const options = [
        {
            name: 'Daily Booking',
            price: `${formatCurrency(dayRate)}/day`,
            description: 'Best for short trips and city travel',
            features: ['Price from selected vehicle', 'Driver included', 'Day-based billing'],
            popular: true
        },
        {
            name: '3-Day Package',
            price: `${formatCurrency(threeDay)}`,
            description: 'Great for weekend tours',
            features: ['3-day estimate', 'Flexible schedule', 'Suitable for outstation trips'],
            popular: false
        },
        {
            name: 'Weekly Estimate',
            price: `${formatCurrency(weekRate)}`,
            description: 'For extended travel plans',
            features: ['7-day estimate', 'Consistent pricing', 'Long trip ready'],
            popular: false
        }
    ];

    pricingGrid.innerHTML = options.map(option => `
        <div class="pricing-option ${option.popular ? 'popular' : ''}">
            <div class="pricing-header">
                <div class="pricing-name">${option.name}</div>
                <div class="pricing-price">${option.price}</div>
            </div>
            <div class="pricing-description">${option.description}</div>
            <div class="pricing-features">
                ${option.features.map(feature => `<div class="pricing-feature">${feature}</div>`).join('')}
            </div>
        </div>
    `).join('');
}

function renderReviews() {
    const overallRating = document.getElementById('overallRating');
    const ratingBreakdown = document.getElementById('ratingBreakdown');
    const reviewsList = document.getElementById('reviewsList');

    if (!overallRating || !ratingBreakdown || !reviewsList) return;

    overallRating.textContent = '4.8';
    ratingBreakdown.innerHTML = `
        <div class="rating-item"><span class="rating-category">Vehicle Condition</span><div class="rating-bar"><div class="rating-fill" style="width: 96%"></div></div><span class="rating-value">4.8</span></div>
        <div class="rating-item"><span class="rating-category">Comfort</span><div class="rating-bar"><div class="rating-fill" style="width: 94%"></div></div><span class="rating-value">4.7</span></div>
        <div class="rating-item"><span class="rating-category">Driver Service</span><div class="rating-bar"><div class="rating-fill" style="width: 98%"></div></div><span class="rating-value">4.9</span></div>
    `;

    reviewsList.innerHTML = `
        <div class="review-item">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">TR</div>
                    <div class="reviewer-details"><h4>TravelMate User</h4><p>Recent booking</p></div>
                </div>
                <div class="review-rating"><span class="review-stars">★★★★★</span></div>
            </div>
            <div class="review-text">Clean vehicle, punctual pickup, and smooth booking process.</div>
        </div>
    `;
}

function setupDateRestrictions() {
    const today = new Date().toISOString().split('T')[0];
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');

    if (!pickupDate || !returnDate) return;

    pickupDate.min = today;
    returnDate.min = today;

    pickupDate.addEventListener('change', function () {
        returnDate.min = this.value;
        if (returnDate.value && returnDate.value < this.value) {
            returnDate.value = this.value;
        }
    });
}

function setupFormListeners() {
    const serviceType = document.getElementById('serviceType');
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');

    if (serviceType) serviceType.addEventListener('change', updatePriceDisplay);
    if (pickupDate) pickupDate.addEventListener('change', validateDates);
    if (returnDate) returnDate.addEventListener('change', validateDates);
}

function updatePriceDisplay() {
    if (!currentVehicle) return;

    const serviceType = document.getElementById('serviceType').value;
    const rate = Number(currentVehicle.cost_per_km || 0);
    const priceAmount = document.getElementById('priceAmount');
    const pricePeriod = document.querySelector('.price-period');

    const multiplier = getServiceMultiplier(serviceType);
    const displayRate = rate * multiplier;

    if (priceAmount) priceAmount.textContent = formatCurrency(displayRate);
    if (pricePeriod) pricePeriod.textContent = '/ day';
}

function validateDates() {
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;

    if (pickupDate && returnDate) {
        const pickup = new Date(`${pickupDate}T00:00:00`);
        const returnD = new Date(`${returnDate}T00:00:00`);
        if (returnD < pickup) {
            alert('Return date must be after pickup date');
            document.getElementById('returnDate').value = '';
        }
    }
}

//create booking object and show summary
function calculatePrice() {
    if (!currentVehicle) {
        alert('Transport details not loaded yet.');
        return;
    }

    const serviceType = document.getElementById('serviceType').value;
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;
    const pickupTime = document.getElementById('pickupTime').value;
    const returnTime = document.getElementById('returnTime').value;
    const pickupLocation = document.getElementById('pickupLocationInput').value;
    const dropoffLocation = document.getElementById('dropoffLocationInput').value;

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

    const pickup = new Date(`${pickupDate}T${pickupTime}`);
    const returnD = new Date(`${returnDate}T${returnTime}`);

    if (returnD <= pickup) {
        alert('Return date/time must be after pickup date/time');
        return;
    }

    const duration = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24));
    const days = Math.max(1, duration);

    const unitRate = Number(currentVehicle.cost_per_km || 0);
    const multiplier = getServiceMultiplier(serviceType);
    const effectiveRate = unitRate * multiplier;

    const basePrice = Math.round(effectiveRate * days);
    const serviceCharge = Math.round(basePrice * 0.1);
    const totalPrice = basePrice + serviceCharge;

    document.getElementById('durationCount').textContent = `${days} day${days > 1 ? 's' : ''}`;
    document.getElementById('basePrice').textContent = formatCurrency(basePrice);
    document.getElementById('serviceCharge').textContent = formatCurrency(serviceCharge);
    document.getElementById('totalPrice').textContent = formatCurrency(totalPrice);

    document.getElementById('bookingSummary').style.display = 'block';
    document.querySelector('.book-now-btn').style.display = 'none';
    document.querySelector('.confirm-booking-btn').style.display = 'block';

    calculatedBooking = {
        vehicle_id: currentVehicle.id,
        service_type: serviceType,
        pickup_date: pickupDate,
        pickup_time: pickupTime,
        return_date: returnDate,
        return_time: returnTime,
        pickup_location: pickupLocation,
        dropoff_location: dropoffLocation,
        passengers: parseInt(document.getElementById('passengers').value, 10),
        luggage: parseInt(document.getElementById('luggage').value, 10),
        alternative_contact: document.getElementById('alternativeContact').value || null,
        special_requirements: document.getElementById('specialRequirements').value,
        base_price: basePrice,
        service_charge: serviceCharge,
        total_price: totalPrice
    };
}

async function confirmBooking() {
    if (!calculatedBooking) {
        alert('Please calculate the price first.');
        return;
    }

    const baseUrl = getBaseUrl();
    const confirmBtn = document.querySelector('.confirm-booking-btn');

    try {
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';

        const response = await fetch(`${baseUrl}/api/transport-booking/create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(calculatedBooking),
            credentials: 'same-origin'
        });

        const result = await response.json();
        if (result.success) {
            const bookingId = result?.data?.booking_id || result?.data?.bookingId || 'Pending';
            showBookingSuccessModal(bookingId);
            return;
        }

        if (result.errors?.availability) {
            showDateUnavailableModal('Dates are not available. Please choose different pickup and return dates.');
            return;
        }

        const errorMsg = result.errors?.general || result.errors?.auth || result.errors?.availability || result.errors?.date || 'Failed to initialize booking';
        alert(`Error: ${errorMsg}`);
    } catch (error) {
        console.error('Booking error:', error);
        alert('An error occurred while processing your booking. Please try again.');
    } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Confirm Booking';
    }
}

function showMap() {
    const locationText = document.getElementById('defaultLocation')?.textContent || 'Location unavailable';
    alert(`Map functionality coming soon!\nLocation: ${locationText}`);
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') previousImage();
    if (e.key === 'ArrowRight') nextImage();
});