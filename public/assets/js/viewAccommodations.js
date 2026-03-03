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
            filteredAccommodations = allAccommodations.filter(a => a.status !== 'pending');
            updateStatistics();
            displayPendingAccommodations();
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
    
    grid.innerHTML = accommodations.map(accommodation => createAccommodationCard(accommodation, 'other')).join('');
}

function displayPendingAccommodations() {
    const grid = document.getElementById('pendingAccommodationsGrid');
    const emptyState = document.getElementById('pendingEmptyState');

    if (!grid || !emptyState) {
        return;
    }

    const pendingAccommodations = allAccommodations.filter(a => a.status === 'pending');

    if (pendingAccommodations.length === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';
    emptyState.style.display = 'none';
    grid.innerHTML = pendingAccommodations.map(accommodation => createAccommodationCard(accommodation, 'pending')).join('');
}

// Create accommodation card HTML
function createAccommodationCard(accommodation, section = 'other') {
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
    const description = accommodation.description || 'No description provided for this property.';
    const shortDescription = description.length > 120 ? `${description.substring(0, 120)}...` : description;
    
    return `
        <div class="accommodation-card" onclick="viewAccommodation(${accommodation.id})">
            <div class="accommodation-image">
                <img src="${image}" alt="${escapeHtml(title)}" onerror="this.src='assets/images/default-accommodation.jpg'">
                <span class="accommodation-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="accommodation-content">
                <div class="accommodation-type">${escapeHtml(propertyType)}</div>
                <h3 class="accommodation-title">${escapeHtml(title)}</h3>

                <div class="accommodation-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${escapeHtml(location)}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-bed"></i>
                        <span>${rooms} Rooms • ${bathrooms} Baths • ${maxGuests} Guests</span>
                    </div>
                </div>

                <p class="accommodation-description">${escapeHtml(shortDescription)}</p>

                <div class="accommodation-footer">
                    <div>
                        <div class="accommodation-price">${price}</div>
                        <div class="price-label">per night</div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-view" onclick="event.stopPropagation(); viewAccommodation(${accommodation.id})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            View Full
                        </button>
                        ${section === 'pending' ? `
                            <button class="btn-approve" onclick="event.stopPropagation(); moderateAccommodation(${accommodation.id}, 'approve')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Approve
                            </button>
                            <button class="btn-reject" onclick="event.stopPropagation(); moderateAccommodation(${accommodation.id}, 'reject')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Reject
                            </button>
                        ` : `
                            <button class="btn-delete" onclick="event.stopPropagation(); deleteAccommodationByAdmin(${accommodation.id})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Delete
                            </button>
                        `}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function deleteAccommodationByAdmin(id) {
    if (!confirm('Are you sure you want to permanently delete this accommodation?')) {
        return;
    }

    const baseUrl = getBaseUrl();
    fetch(`${baseUrl}/api/accommodation/deleteByAdmin`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${encodeURIComponent(id)}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.errors?.[0] || 'Failed to delete accommodation');
            return;
        }

        allAccommodations = allAccommodations.filter(a => Number(a.id) !== Number(id));
        updateStatistics();
        displayPendingAccommodations();
        applyFilters();
    })
    .catch(error => {
        console.error('Error deleting accommodation:', error);
        alert('Failed to delete accommodation');
    });
}

function moderateAccommodation(id, action) {
    const endpoint = action === 'approve' ? 'approve' : 'reject';
    const label = action === 'approve' ? 'approve' : 'reject';

    if (!confirm(`Are you sure you want to ${label} this property?`)) {
        return;
    }

    const baseUrl = getBaseUrl();
    fetch(`${baseUrl}/api/accommodation/${endpoint}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${encodeURIComponent(id)}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.errors?.[0] || `Failed to ${label} accommodation`);
            return;
        }

        const accommodation = allAccommodations.find(a => Number(a.id) === Number(id));
        if (accommodation) {
            accommodation.status = data.data?.status || (action === 'approve' ? 'active' : 'inactive');
        }

        updateStatistics();
        displayPendingAccommodations();
        applyFilters();
    })
    .catch(error => {
        console.error(`Error trying to ${label} accommodation:`, error);
        alert(`Failed to ${label} accommodation`);
    });
}

// Apply filters
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
    
    const nonPendingAccommodations = allAccommodations.filter(a => a.status !== 'pending');

    filteredAccommodations = nonPendingAccommodations.filter(accommodation => {
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
    const pendingGrid = document.getElementById('pendingAccommodationsGrid');
    const pendingEmptyState = document.getElementById('pendingEmptyState');
    
    grid.style.display = 'none';
    emptyState.style.display = 'block';

    if (pendingGrid && pendingEmptyState) {
        pendingGrid.style.display = 'none';
        pendingEmptyState.style.display = 'block';
    }
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
