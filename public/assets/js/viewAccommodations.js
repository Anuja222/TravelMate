// Global variables
let allAccommodations = [];
let filteredAccommodations = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAccommodations();
    
    // Add event listeners
    document.getElementById('btnApplyFilter').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

// Load all accommodations
function loadAccommodations() {
    const baseUrl = getBaseUrl();
    
    fetch(`${baseUrl}/api/accommodation/listAll`, {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            allAccommodations = data.data || [];
            filteredAccommodations = [...allAccommodations];
            updateStatistics();
            displayAccommodations(filteredAccommodations);
        } else {
            console.error('Failed to load accommodations:', data.errors);
            showEmptyState();
        }
    })
    .catch(error => {
        console.error('Error loading accommodations:', error);
        showEmptyState();
    });
}

// Update statistics cards
function updateStatistics() {
    const active = allAccommodations.filter(a => a.status === 'active').length;
    const pending = allAccommodations.filter(a => a.status === 'pending').length;
    const inactive = allAccommodations.filter(a => a.status === 'inactive').length;
    const total = allAccommodations.length;
    
    document.getElementById('activeCount').textContent = active;
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('inactiveCount').textContent = inactive;
    document.getElementById('totalCount').textContent = total;
}

// Display accommodations in grid
function displayAccommodations(accommodations) {
    const grid = document.getElementById('accommodationsGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (accommodations.length === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    grid.style.display = 'grid';
    emptyState.style.display = 'none';
    
    grid.innerHTML = accommodations.map(accommodation => createAccommodationCard(accommodation)).join('');
}

// Create accommodation card HTML
function createAccommodationCard(accommodation) {
    const baseUrl = getBaseUrl();
    const image = accommodation.main_image 
        ? `${baseUrl}/${accommodation.main_image}` 
        : 'assets/images/default-accommodation.jpg';
    
    const statusClass = `badge-${accommodation.status || 'active'}`;
    const statusText = (accommodation.status || 'active').toUpperCase();
    
    const price = accommodation.price_per_night 
        ? `Rs ${parseFloat(accommodation.price_per_night).toFixed(2)}` 
        : 'N/A';
    
    const propertyType = accommodation.property_type || 'Property';
    const title = accommodation.title || 'Untitled Property';
    const location = accommodation.location || 'Location not specified';
    const rooms = accommodation.rooms || 0;
    const bathrooms = accommodation.bathrooms || 0;
    const maxGuests = accommodation.max_guests || 0;
    
    return `
        <div class="accommodation-card" onclick="viewAccommodation(${accommodation.id})">
            <div class="accommodation-image">
                <img src="${image}" alt="${escapeHtml(title)}" onerror="this.src='assets/images/default-accommodation.jpg'">
                <span class="accommodation-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="accommodation-content">
                <div class="accommodation-type">${escapeHtml(propertyType)}</div>
                <h3 class="accommodation-title">${escapeHtml(title)}</h3>
                <div class="accommodation-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${escapeHtml(location)}</span>
                </div>
                <div class="accommodation-details">
                    <div class="detail-item">
                        <i class="fas fa-bed"></i>
                        <span>${rooms} Rooms</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-bath"></i>
                        <span>${bathrooms} Baths</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-users"></i>
                        <span>${maxGuests} Guests</span>
                    </div>
                </div>
                <div class="accommodation-footer">
                    <div>
                        <div class="accommodation-price">${price}</div>
                        <div class="price-label">per night</div>
                    </div>
                    <button class="btn-view" onclick="event.stopPropagation(); viewAccommodation(${accommodation.id})">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Apply filters
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
    
    filteredAccommodations = allAccommodations.filter(accommodation => {
        const matchesSearch = !searchTerm || 
            (accommodation.title && accommodation.title.toLowerCase().includes(searchTerm)) ||
            (accommodation.location && accommodation.location.toLowerCase().includes(searchTerm)) ||
            (accommodation.description && accommodation.description.toLowerCase().includes(searchTerm));
        
        const matchesStatus = !statusFilter || accommodation.status === statusFilter;
        
        const matchesType = !typeFilter || 
            (accommodation.property_type && accommodation.property_type.toLowerCase() === typeFilter);
        
        return matchesSearch && matchesStatus && matchesType;
    });
    
    displayAccommodations(filteredAccommodations);
}

// View accommodation details in modal
function viewAccommodation(id) {
    const accommodation = allAccommodations.find(a => a.id === id);
    if (!accommodation) return;
    
    const baseUrl = getBaseUrl();
    
    // Set modal title
    document.getElementById('modalTitle').textContent = accommodation.title || 'Accommodation Details';
    
    // Set basic information
    document.getElementById('modalPropertyId').textContent = `#${accommodation.id}`;
    document.getElementById('modalPropertyType').textContent = accommodation.property_type || 'N/A';
    document.getElementById('modalAccomTitle').textContent = accommodation.title || 'N/A';
    document.getElementById('modalLocation').textContent = accommodation.location || 'N/A';
    document.getElementById('modalStatus').textContent = (accommodation.status || 'active').toUpperCase();
    document.getElementById('modalStatus').style.color = getStatusColor(accommodation.status);
    
    // Set property details
    document.getElementById('modalRooms').textContent = accommodation.rooms || '0';
    document.getElementById('modalBathrooms').textContent = accommodation.bathrooms || '0';
    document.getElementById('modalMaxGuests').textContent = accommodation.max_guests || '0';
    document.getElementById('modalPrice').textContent = accommodation.price_per_night 
        ? `Rs ${parseFloat(accommodation.price_per_night).toFixed(2)} per night` 
        : 'N/A';
    
    // Set check-in/out times
    document.getElementById('modalCheckInStart').textContent = accommodation.check_in_start || 'N/A';
    document.getElementById('modalCheckInEnd').textContent = accommodation.check_in_end || 'N/A';
    document.getElementById('modalCheckOut').textContent = accommodation.check_out_time || 'N/A';
    
    // Set house rules
    document.getElementById('modalSmoking').textContent = accommodation.smoking == 1 ? 'Allowed' : 'Not Allowed';
    document.getElementById('modalParties').textContent = accommodation.parties == 1 ? 'Allowed' : 'Not Allowed';
    document.getElementById('modalPets').textContent = accommodation.pets || 'Not Allowed';
    
    // Set provider information
    document.getElementById('modalUserId').textContent = `#${accommodation.user_id || 'N/A'}`;
    document.getElementById('modalCreatedAt').textContent = accommodation.created_at 
        ? formatDateTime(accommodation.created_at) 
        : 'N/A';
    document.getElementById('modalUpdatedAt').textContent = accommodation.updated_at 
        ? formatDateTime(accommodation.updated_at) 
        : 'N/A';
    
    // Set media information
    const imageCount = accommodation.images ? accommodation.images.length : 0;
    document.getElementById('modalImageCount').textContent = imageCount;
    document.getElementById('modalHasMainImage').textContent = accommodation.main_image ? 'Yes' : 'No';
    
    // Set description
    document.getElementById('modalDescription').textContent = accommodation.description || 'No description provided.';
    
    // Set images
    const mainImage = document.querySelector('#mainImage img');
    if (accommodation.main_image) {
        mainImage.src = `${baseUrl}/${accommodation.main_image}`;
    } else {
        mainImage.src = 'assets/images/default-accommodation.jpg';
    }
    
    // Set image gallery
    const gallery = document.getElementById('imageGallery');
    if (accommodation.images && accommodation.images.length > 0) {
        gallery.innerHTML = accommodation.images.map((img, index) => `
            <div class="gallery-thumb ${index === 0 ? 'active' : ''}" onclick="changeMainImage('${baseUrl}/${img.image_path}', this)">
                <img src="${baseUrl}/${img.image_path}" alt="Image ${index + 1}">
            </div>
        `).join('');
    } else {
        gallery.innerHTML = '<p style="color: #7f8c8d;">No additional images</p>';
    }
    
    // Show modal
    document.getElementById('viewModal').style.display = 'flex';
}

// Change main image in modal
function changeMainImage(imagePath, thumbElement) {
    document.querySelector('#mainImage img').src = imagePath;
    
    // Update active state
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbElement.classList.add('active');
}

// Close modal
function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('viewModal');
    if (e.target === modal) {
        closeViewModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
    }
});

// Show empty state
function showEmptyState() {
    const grid = document.getElementById('accommodationsGrid');
    const emptyState = document.getElementById('emptyState');
    
    grid.style.display = 'none';
    emptyState.style.display = 'block';
}

// Get status color
function getStatusColor(status) {
    switch(status) {
        case 'active': return '#1abc5b';
        case 'pending': return '#f39c12';
        case 'inactive': return '#e74c3c';
        default: return '#7f8c8d';
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Format date and time
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('en-US', options);
}

// Get base URL
function getBaseUrl() {
    return window.location.origin + '/TravelMate/public';
}
