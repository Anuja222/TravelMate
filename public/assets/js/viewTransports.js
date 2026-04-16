// Global variables
let allTransports = [];
let filteredTransports = [];
let pendingActionResolve = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTransports();
    
    // Add event listeners securely
    const btnApplyFilter = document.getElementById('btnApplyFilter');
    if (btnApplyFilter) btnApplyFilter.addEventListener('click', applyFilters);
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }

    const confirmProceedBtn = document.getElementById('actionConfirmProceed');
    const confirmCancelBtn = document.getElementById('actionConfirmCancel');
    const successOkBtn = document.getElementById('actionSuccessOk');
    const confirmModal = document.getElementById('actionConfirmModal');
    const successModal = document.getElementById('actionSuccessModal');

    if (confirmProceedBtn) {
        confirmProceedBtn.addEventListener('click', function() {
            closeActionConfirmModal(true);
        });
    }

    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function() {
            closeActionConfirmModal(false);
        });
    }

    if (successOkBtn) {
        successOkBtn.addEventListener('click', function() {
            closeActionSuccessModal();
        });
    }

    if (confirmModal) {
        confirmModal.addEventListener('click', function(event) {
            if (event.target === confirmModal) {
                closeActionConfirmModal(false);
            }
        });
    }

    if (successModal) {
        successModal.addEventListener('click', function(event) {
            if (event.target === successModal) {
                closeActionSuccessModal();
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (successModal && successModal.style.display === 'flex') {
            closeActionSuccessModal();
            return;
        }

        if (confirmModal && confirmModal.style.display === 'flex') {
            closeActionConfirmModal(false);
        }
    });

    document.addEventListener('click', function(event) {
        const actionButton = event.target.closest('.btn-view, .btn-approve, .btn-reject, .btn-delete');
        if (actionButton) {
            event.preventDefault();
            event.stopPropagation();

            const id = Number(actionButton.getAttribute('data-vehicle-id'));
            if (!id) {
                return;
            }

            const action = actionButton.getAttribute('data-action');
            if (action === 'view') {
                viewTransport(id);
                return;
            }

            if (action === 'approve') {
                moderateVehicle(id, 'approve');
                return;
            }

            if (action === 'reject') {
                moderateVehicle(id, 'reject');
                return;
            }

            if (action === 'delete') {
                deleteVehicle(id);
                return;
            }
        }

        const card = event.target.closest('.transport-card');
        if (card) {
            const cardId = Number(card.getAttribute('data-vehicle-id'));
            if (cardId) {
                viewTransport(cardId);
            }
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
            filteredTransports = allTransports.filter(v => v.status === 'active');
            updateStatistics();
            displayPendingTransports();
            displayRejectedTransports();
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
    const rejected = allTransports.filter(v => v.status === 'inactive').length;
    const total = allTransports.length;
    
    document.getElementById('activeCount').textContent = active;
    document.getElementById('pendingCount').textContent = pending;
    const rejectedCountEl = document.getElementById('rejectedCount') || document.getElementById('inactiveCount');
    if (rejectedCountEl) {
        rejectedCountEl.textContent = rejected;
    }
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

function displayPendingTransports() {
    const grid = document.getElementById('pendingTransportsGrid');
    const emptyState = document.getElementById('pendingEmptyState');

    if (!grid || !emptyState) {
        return;
    }

    const pendingTransports = allTransports.filter(v => v.status === 'pending');

    if (pendingTransports.length === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';
    emptyState.style.display = 'none';
    grid.innerHTML = pendingTransports.map(transport => createTransportCard(transport)).join('');
}

function displayRejectedTransports() {
    const grid = document.getElementById('rejectedTransportsGrid');
    const emptyState = document.getElementById('rejectedEmptyState');

    if (!grid || !emptyState) {
        return;
    }

    const rejectedTransports = allTransports.filter(v => v.status === 'inactive');

    if (rejectedTransports.length === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';
    emptyState.style.display = 'none';
    grid.innerHTML = rejectedTransports.map(transport => createTransportCard(transport)).join('');
}

// Show empty state
function showEmptyState() {
    const grid = document.getElementById('transportsGrid');
    const emptyState = document.getElementById('emptyState');
    const pendingGrid = document.getElementById('pendingTransportsGrid');
    const pendingEmptyState = document.getElementById('pendingEmptyState');
    const rejectedGrid = document.getElementById('rejectedTransportsGrid');
    const rejectedEmptyState = document.getElementById('rejectedEmptyState');
    grid.style.display = 'none';
    emptyState.style.display = 'block';

    if (pendingGrid && pendingEmptyState) {
        pendingGrid.style.display = 'none';
        pendingEmptyState.style.display = 'block';
    }

    if (rejectedGrid && rejectedEmptyState) {
        rejectedGrid.style.display = 'none';
        rejectedEmptyState.style.display = 'block';
    }
}

// Create transport card HTML
function createTransportCard(transport) {
    const baseUrl = getBaseUrl();
    const image = transport.main_image 
        ? `${baseUrl}${transport.main_image}` 
        : 'assets/images/default-vehicle.jpg';
    
    const rawStatus = transport.status || 'active';
    const statusClass = rawStatus === 'inactive' ? 'badge-rejected' : `badge-${rawStatus}`;
    const statusText = rawStatus === 'inactive' ? 'REJECTED' : rawStatus.toUpperCase();
    
    const vehicleType = transport.vehicle_type || 'Vehicle';
    const model = transport.vehicle_model || 'Model not specified';
    const district = transport.working_district || 'District not specified';
    const passengers = transport.passenger_count || 0;
    const acType = transport.ac_type || 'N/A';
    const vehicleNumber = transport.vehicle_number || 'N/A';
    const year = transport.vehicle_year || 'N/A';
    const color = transport.vehicle_color || 'Not specified';
    
    return `
        <div class="transport-card" data-vehicle-id="${transport.id}">
            <div class="transport-image">
                <img src="${image}" alt="${escapeHtml(model)}" onerror="this.src='assets/images/default-vehicle.jpg'">
                <span class="transport-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="transport-content">
                <div class="transport-type">${escapeHtml(vehicleType)}</div>
                <h3 class="transport-title">${escapeHtml(model)}</h3>

                <div class="transport-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${escapeHtml(district)}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-car"></i>
                        <span>${passengers} Seats • ${acType.toUpperCase()} • ${year}</span>
                    </div>
                </div>

                <p class="transport-description">Vehicle No: ${escapeHtml(vehicleNumber)} • Color: ${escapeHtml(color)}</p>

                <div class="transport-footer">
                    <div>
                        <div class="transport-price">${escapeHtml(vehicleNumber)}</div>
                        <div class="price-label">Vehicle No.</div>
                    </div>
                    <div class="card-actions">
                        <button type="button" class="btn-view" data-vehicle-id="${transport.id}" data-action="view">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            View Full
                        </button>
                        ${transport.status === 'pending' ? `
                            <button type="button" class="btn-approve" data-vehicle-id="${transport.id}" data-action="approve">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Approve
                            </button>
                            <button type="button" class="btn-reject" data-vehicle-id="${transport.id}" data-action="reject">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Reject
                            </button>
                        ` : ''}
                        <button type="button" class="btn-delete" data-vehicle-id="${transport.id}" data-action="delete">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function moderateVehicle(id, action) {
    const isApprove = action === 'approve';

    openActionConfirmModal({
        title: isApprove ? 'Approve Vehicle' : 'Reject Vehicle',
        message: isApprove
            ? 'Are you sure you want to approve this vehicle listing? It will become active immediately.'
            : 'Are you sure you want to reject this vehicle listing? It will be moved to inactive status.',
        confirmText: isApprove ? 'Approve' : 'Reject',
        actionType: action
    }).then(confirmed => {
        if (!confirmed) {
            return;
        }

        runVehicleModeration(id, action);
    });
}

function runVehicleModeration(id, action) {
    const endpoint = action === 'approve' ? 'approve' : 'reject';
    const label = action === 'approve' ? 'approve' : 'reject';
    const baseUrl = getBaseUrl();
    fetch(`${baseUrl}/api/vehicle/${endpoint}`, {
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
            alert(data.errors?.error || `Failed to ${label} vehicle`);
            return;
        }

        const vehicle = allTransports.find(v => Number(v.id) === Number(id));
        if (vehicle) {
            vehicle.status = data.data?.status || (action === 'approve' ? 'active' : 'inactive');
        }

        updateStatistics();
        displayPendingTransports();
        displayRejectedTransports();
        applyFilters();

        if (action === 'approve') {
            openActionSuccessModal({
                title: 'Approved Successfully',
                message: 'Vehicle has been approved and is now active.',
                actionType: 'approve'
            });
        } else {
            openActionSuccessModal({
                title: 'Rejected Successfully',
                message: 'Vehicle has been rejected and moved to the rejected section.',
                actionType: 'reject'
            });
        }
    })
    .catch(error => {
        console.error(`Error trying to ${label} vehicle:`, error);
        alert(`Failed to ${label} vehicle`);
    });
}

function deleteVehicle(id) {
    openActionConfirmModal({
        title: 'Delete Vehicle',
        message: 'Are you sure you want to permanently delete this vehicle? This cannot be undone.',
        confirmText: 'Delete',
        actionType: 'delete'
    }).then(confirmed => {
        if (!confirmed) {
            return;
        }

        const baseUrl = getBaseUrl();
        fetch(`${baseUrl}/api/vehicle/deleteByAdmin`, {
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
                alert(data.errors?.error || 'Failed to delete vehicle');
                return;
            }

            allTransports = allTransports.filter(v => Number(v.id) !== Number(id));

            updateStatistics();
            displayPendingTransports();
            displayRejectedTransports();
            applyFilters();

            openActionSuccessModal({
                title: 'Deleted Successfully',
                message: 'Vehicle has been permanently deleted.',
                actionType: 'delete'
            });
        })
        .catch(error => {
            console.error('Error trying to delete vehicle:', error);
            alert('Failed to delete vehicle');
        });
    });
}

function openActionConfirmModal({ title, message, confirmText, actionType }) {
    const modal = document.getElementById('actionConfirmModal');
    const titleEl = document.getElementById('actionConfirmTitle');
    const messageEl = document.getElementById('actionConfirmMessage');
    const iconEl = document.getElementById('actionConfirmIcon');
    const confirmBtn = document.getElementById('actionConfirmProceed');
    const cancelBtn = document.getElementById('actionConfirmCancel');

    if (!modal || !titleEl || !messageEl || !confirmBtn || !cancelBtn) {
        return Promise.resolve(confirm(message));
    }

    titleEl.textContent = title || 'Are you sure?';
    messageEl.textContent = message || 'Please confirm this action.';
    confirmBtn.textContent = confirmText || 'Confirm';

    const isRejectOrDelete = actionType === 'reject' || actionType === 'delete';
    confirmBtn.style.background = isRejectOrDelete
        ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'
        : 'linear-gradient(135deg, #10b981 0%, #059669 100%)';

    if (iconEl) {
        iconEl.innerHTML = isRejectOrDelete
            ? '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
            : '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline></svg>';
    }

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    return new Promise(resolve => {
        pendingActionResolve = resolve;
    });
}

function closeActionConfirmModal(confirmed) {
    const modal = document.getElementById('actionConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
    document.body.style.overflow = 'auto';

    if (pendingActionResolve) {
        pendingActionResolve(Boolean(confirmed));
        pendingActionResolve = null;
    }
}

function openActionSuccessModal({ title, message, actionType }) {
    const modal = document.getElementById('actionSuccessModal');
    const titleEl = document.getElementById('actionSuccessTitle');
    const messageEl = document.getElementById('actionSuccessMessage');
    const iconWrap = document.getElementById('actionSuccessIcon');
    const okBtn = document.getElementById('actionSuccessOk');

    if (!modal || !titleEl || !messageEl) {
        alert(message || 'Action completed successfully.');
        return;
    }

    const isReject = actionType === 'reject';

    if (iconWrap) {
        iconWrap.style.background = isReject ? '#fee2e2' : '#d1fae5';
        iconWrap.innerHTML = isReject
            ? '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
            : '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline></svg>';
    }

    if (okBtn) {
        okBtn.style.background = isReject
            ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'
            : 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
    }

    titleEl.textContent = title || 'Success';
    messageEl.textContent = message || 'Action completed successfully.';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeActionSuccessModal() {
    const modal = document.getElementById('actionSuccessModal');
    if (modal) {
        modal.style.display = 'none';
    }
    document.body.style.overflow = 'auto';
}

// Apply filters
function applyFilters() {
    const searchInputEl = document.getElementById('searchInput');
    const statusFilterEl = document.getElementById('statusFilter');
    const typeFilterEl = document.getElementById('typeFilter');
    const acFilterEl = document.getElementById('acFilter');

    const searchTerm = searchInputEl ? searchInputEl.value.toLowerCase() : '';
    const statusFilter = statusFilterEl ? statusFilterEl.value : '';
    const typeFilter = typeFilterEl ? typeFilterEl.value.toLowerCase() : '';
    const acFilter = acFilterEl ? acFilterEl.value.toLowerCase() : '';
    
    const nonPendingTransports = allTransports.filter(v => v.status === 'active');

    filteredTransports = nonPendingTransports.filter(transport => {
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
