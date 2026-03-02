// Global variables
let allTransports = [];
let filteredTransports = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTransports();
    
    // Add event listeners
    document.getElementById('btnApplyFilter').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});

// Get base URL helper
function getBaseUrl() {
    const path = window.location.pathname;
    const parts = path.split('/');
    const publicIndex = parts.indexOf('public');
    if (publicIndex !== -1) {
        return '/' + parts.slice(1, publicIndex + 1).join('/');
    }
    return '/TravelMate/public';
}

// Load all transports
function loadTransports() {
    const baseUrl = getBaseUrl();
    
    fetch(`${baseUrl}/api/vehicle/listAll`, {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            allTransports = data.data || [];
            filteredTransports = [...allTransports];
            updateStatistics();
            displayTransports(filteredTransports);
        } else {
            console.error('Failed to load transports:', data.errors);
            showEmptyState();
        }
    })
    .catch(error => {
        console.error('Error loading transports:', error);
        showEmptyState();
    });
}

// Update statistics cards
function updateStatistics() {
    const active = allTransports.filter(v => v.status === 'active').length;
    const pending = allTransports.filter(v => v.status === 'pending').length;
    const inactive = allTransports.filter(v => v.status === 'inactive').length;
    const total = allTransports.length;
    
    document.getElementById('activeCount').textContent = active;
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('inactiveCount').textContent = inactive;
    document.getElementById('totalCount').textContent = total;
}

// Display transports in grid
function displayTransports(transports) {
    const grid = document.getElementById('transportsGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (transports.length === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    grid.style.display = 'grid';
    emptyState.style.display = 'none';
    
    grid.innerHTML = transports.map(transport => createTransportCard(transport)).join('');
}

// Show empty state
function showEmptyState() {
    const grid = document.getElementById('transportsGrid');
    const emptyState = document.getElementById('emptyState');
    grid.style.display = 'none';
    emptyState.style.display = 'block';
}

// Create transport card HTML
function createTransportCard(transport) {
    const baseUrl = getBaseUrl();
    const image = transport.main_image 
        ? `${baseUrl}${transport.main_image}` 
        : 'assets/images/default-vehicle.jpg';
    
    const statusClass = `badge-${transport.status || 'active'}`;
    const statusText = (transport.status || 'active').toUpperCase();
    
    const vehicleType = transport.vehicle_type || 'Vehicle';
    const model = transport.vehicle_model || 'Model not specified';
    const district = transport.working_district || 'District not specified';
    const passengers = transport.passenger_count || 0;
    const acType = transport.ac_type || 'N/A';
    const vehicleNumber = transport.vehicle_number || 'N/A';
    const year = transport.vehicle_year || 'N/A';
    
    return `
        <div class="transport-card" onclick="viewTransport(${transport.id})">
            <div class="transport-image">
                <img src="${image}" alt="${escapeHtml(model)}" onerror="this.src='assets/images/default-vehicle.jpg'">
                <span class="transport-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="transport-content">
                <div class="transport-type">${escapeHtml(vehicleType)}</div>
                <h3 class="transport-title">${escapeHtml(model)}</h3>
                <div class="transport-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${escapeHtml(district)}</span>
                </div>
                <div class="transport-details">
                    <div class="detail-item">
                        <i class="fas fa-users"></i>
                        <span>${passengers} Seats</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-wind"></i>
                        <span>${acType.toUpperCase()}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>${year}</span>
                    </div>
                </div>
                <div class="transport-footer">
                    <div>
                        <div class="transport-price">${escapeHtml(vehicleNumber)}</div>
                        <div class="price-label">Vehicle No.</div>
                    </div>
                    <button class="btn-view" onclick="event.stopPropagation(); viewTransport(${transport.id})">
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
    const acFilter = document.getElementById('acFilter').value.toLowerCase();
    
    filteredTransports = allTransports.filter(transport => {
        const matchesSearch = !searchTerm || 
            (transport.vehicle_model && transport.vehicle_model.toLowerCase().includes(searchTerm)) ||
            (transport.vehicle_number && transport.vehicle_number.toLowerCase().includes(searchTerm)) ||
            (transport.working_district && transport.working_district.toLowerCase().includes(searchTerm)) ||
            (transport.vehicle_type && transport.vehicle_type.toLowerCase().includes(searchTerm));
        
        const matchesStatus = !statusFilter || transport.status === statusFilter;
        
        const matchesType = !typeFilter || 
            (transport.vehicle_type && transport.vehicle_type.toLowerCase() === typeFilter);
        
        const matchesAc = !acFilter || 
            (transport.ac_type && transport.ac_type.toLowerCase() === acFilter);
        
        return matchesSearch && matchesStatus && matchesType && matchesAc;
    });
    
    displayTransports(filteredTransports);
}

// View transport details in modal
function viewTransport(id) {
    const transport = allTransports.find(v => v.id === id);
    if (!transport) return;
    
    const baseUrl = getBaseUrl();
    
    // Set modal title
    const modelText = transport.vehicle_model || 'Vehicle';
    document.getElementById('modalTitle').textContent = `${modelText} Details`;
    
    // Set basic information
    document.getElementById('modalVehicleId').textContent = `#${transport.id}`;
    document.getElementById('modalVehicleType').textContent = transport.vehicle_type || 'N/A';
    document.getElementById('modalModel').textContent = transport.vehicle_model || 'N/A';
    document.getElementById('modalYear').textContent = transport.vehicle_year || 'N/A';
    document.getElementById('modalStatus').textContent = (transport.status || 'active').toUpperCase();
    document.getElementById('modalStatus').className = 'detail-value status-' + (transport.status || 'active');
    
    // Set vehicle details
    document.getElementById('modalColor').textContent = transport.vehicle_color || 'N/A';
    document.getElementById('modalNumber').textContent = transport.vehicle_number || 'N/A';
    document.getElementById('modalPassengers').textContent = transport.passenger_count || '0';
    document.getElementById('modalAcType').textContent = (transport.ac_type || 'N/A').toUpperCase();
    
    // Set location & service
    document.getElementById('modalDistrict').textContent = transport.working_district || 'N/A';
    
    // Set provider information
    document.getElementById('modalUserId').textContent = `#${transport.user_id || 'N/A'}`;
    document.getElementById('modalCreatedAt').textContent = transport.created_at 
        ? formatDateTime(transport.created_at) 
        : 'N/A';
    document.getElementById('modalUpdatedAt').textContent = transport.updated_at 
        ? formatDateTime(transport.updated_at) 
        : 'N/A';
    
    // Set document information
    const documents = transport.documents || [];
    document.getElementById('modalDocCount').textContent = documents.length;
    const hasPhotos = documents.some(doc => doc.doc_type === 'vehicle_photos' || doc.doc_type === 'vehicle_photo');
    document.getElementById('modalHasPhotos').textContent = hasPhotos ? 'Yes' : 'No';
    
    // Display document list
    const docList = document.getElementById('modalDocumentList');
    if (documents.length > 0) {
        docList.innerHTML = documents.map(doc => {
            const docType = doc.doc_type.replace(/_/g, ' ');
            const fileName = doc.file_path.split('/').pop();
            return `
                <div class="document-item">
                    <i class="fas fa-file-alt"></i>
                    <div class="document-item-info">
                        <div class="document-type">${escapeHtml(docType)}</div>
                        <div class="document-name">${escapeHtml(fileName)}</div>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        docList.innerHTML = '<p style="color: #7f8c8d; text-align: center; padding: 20px;">No documents uploaded</p>';
    }
    
    // Set up image gallery
    const mainImage = document.getElementById('mainImage');
    const imageGallery = document.getElementById('imageGallery');
    
    // Get all image documents
    const imageDocuments = documents.filter(doc => 
        doc.doc_type === 'vehicle_photos' || 
        doc.doc_type === 'vehicle_photo' ||
        doc.file_path.match(/\.(jpg|jpeg|png|gif)$/i)
    );
    
    if (imageDocuments.length > 0) {
        // Set main image
        const mainImageSrc = transport.main_image ? `${baseUrl}${transport.main_image}` : `${baseUrl}${imageDocuments[0].file_path}`;
        mainImage.innerHTML = `<img src="${mainImageSrc}" alt="Vehicle" onerror="this.src='assets/images/default-vehicle.jpg'">`;
        
        // Set gallery thumbnails
        if (imageDocuments.length > 1) {
            imageGallery.innerHTML = imageDocuments.map((doc, index) => {
                const imageSrc = `${baseUrl}${doc.file_path}`;
                const activeClass = index === 0 ? 'active' : '';
                return `
                    <div class="gallery-thumb ${activeClass}" onclick="changeMainImage('${imageSrc}', this)">
                        <img src="${imageSrc}" alt="Vehicle ${index + 1}" onerror="this.src='assets/images/default-vehicle.jpg'">
                    </div>
                `;
            }).join('');
        } else {
            imageGallery.innerHTML = '';
        }
    } else {
        mainImage.innerHTML = `<img src="assets/images/default-vehicle.jpg" alt="No image available">`;
        imageGallery.innerHTML = '';
    }
    
    // Show modal
    document.getElementById('viewModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Change main image in gallery
function changeMainImage(imageSrc, thumbElement) {
    const mainImage = document.getElementById('mainImage');
    mainImage.innerHTML = `<img src="${imageSrc}" alt="Vehicle" onerror="this.src='assets/images/default-vehicle.jpg'">`;
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbElement.classList.add('active');
}

// Close modal
function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Format date time
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        const options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit' 
        };
        return date.toLocaleDateString('en-US', options);
    } catch (e) {
        return dateString;
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Status color helper
function getStatusColor(status) {
    switch(status) {
        case 'active': return '#1abc5b';
        case 'pending': return '#f39c12';
        case 'inactive': return '#e74c3c';
        default: return '#7f8c8d';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('viewModal');
    if (event.target === modal) {
        closeViewModal();
    }
}
