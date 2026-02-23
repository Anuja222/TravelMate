document.addEventListener('DOMContentLoaded', function() {
    // Helper function to get base URL
    function getBaseUrl() {
        const path = window.location.pathname;
        const parts = path.split('/');
        const publicIndex = parts.indexOf('public');
        if (publicIndex !== -1) {
            return parts.slice(0, publicIndex + 1).join('/');
        }
        return '/TravelMate/public';
    }

    const baseUrl = getBaseUrl();
    // expose helper globally for inline handlers and other scripts
    window.getBaseUrl = getBaseUrl;
    console.log('accommodation.js loaded, baseUrl=', baseUrl);

    // ========== PROPERTY LISTING START PAGE ==========
    const propertyTypes = document.querySelectorAll('.property-type');
    if (propertyTypes.length > 0) {
        propertyTypes.forEach(type => {
            type.addEventListener('click', function() {
                // Remove active class from all types
                propertyTypes.forEach(t => t.classList.remove('active'));
                // Add active class to clicked type
                this.classList.add('active');
                
                // Store property type in localStorage
                const propertyType = this.querySelector('h3').textContent.trim().toLowerCase();
                localStorage.setItem('property_type', propertyType);
                
                // Navigate to next page
                window.location.href = baseUrl + '/accommodationFeatures';
            });
        });
    }

    // ========== ACCOMMODATION FEATURES PAGE ==========
    const featuresForm = document.querySelector('.features-form');
    if (featuresForm) {
        const features = {};
        
        // Load any existing data
        const savedFeatures = localStorage.getItem('property_features');
        if (savedFeatures) {
            const parsedFeatures = JSON.parse(savedFeatures);
            Object.keys(parsedFeatures).forEach(key => {
                const input = featuresForm.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = parsedFeatures[key];
                    } else {
                        input.value = parsedFeatures[key];
                    }
                }
            });
        }

        featuresForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all form data
            const formData = new FormData(this);
            formData.forEach((value, key) => {
                features[key] = value;
            });
            
            // Store in localStorage
            localStorage.setItem('property_features', JSON.stringify(features));
            
            // Navigate to next page
            window.location.href = 'propertyDetails';
            
        });
    }

    // ========== PROPERTY DETAILS PAGE ==========
    const detailsForm = document.querySelector('.property-details-form');
    if (detailsForm) {
        // Load any existing data
        const savedDetails = localStorage.getItem('property_details');
        if (savedDetails) {
            const parsedDetails = JSON.parse(savedDetails);
            Object.keys(parsedDetails).forEach(key => {
                const input = detailsForm.querySelector(`[name="${key}"]`);
                if (input) input.value = parsedDetails[key];
            });
        }

        detailsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect form data
            const details = {};
            const formData = new FormData(this);
            formData.forEach((value, key) => {
                details[key] = value;
            });
            
            // Store in localStorage
            localStorage.setItem('property_details', JSON.stringify(details));
            
            // Navigate to next page
            window.location.href = baseUrl + '/photoUpload';
        });
    }

    // ========== PHOTO UPLOAD PAGE ==========
    const photoForm = document.querySelector('.photo-upload-form');
    if (photoForm) {
        const imagesList = [];
        const photoInput = document.getElementById('photoInput');
        
        photoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                imagesList.push(files[i]);
            }
            // Show preview of images
            displayImagePreviews(imagesList);
        });

        photoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store description in localStorage
            const description = document.getElementById('propertyDescription').value;
            localStorage.setItem('property_description', description);
            
            // Navigate to next page
            window.location.href = baseUrl + '/houseRules';
        });

        function displayImagePreviews(files) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'image-previews';
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            });
            
            // Replace existing previews
            const existing = document.querySelector('.image-previews');
            if (existing) existing.remove();
            photoForm.insertBefore(previewContainer, photoForm.querySelector('button'));
        }
    }

    // ========== HOUSE RULES PAGE ==========
    const rulesForm = document.querySelector('.house-rules-form');
    if (rulesForm) {
        rulesForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Collect all data from localStorage
            const propertyType = localStorage.getItem('property_type');
            const features = JSON.parse(localStorage.getItem('property_features') || '{}');
            const details = JSON.parse(localStorage.getItem('property_details') || '{}');
            const description = localStorage.getItem('property_description');
            
            // Collect house rules data
            const formData = new FormData(this);
            const rules = {};
            formData.forEach((value, key) => {
                rules[key] = value;
            });
            
            // Create final FormData object
            const finalFormData = new FormData();
            finalFormData.append('property_type', propertyType);
            finalFormData.append('description', description);
            
            // Add all collected data
            Object.keys(features).forEach(key => {
                finalFormData.append(key, features[key]);
            });
            
            Object.keys(details).forEach(key => {
                finalFormData.append(key, details[key]);
            });
            
            Object.keys(rules).forEach(key => {
                finalFormData.append(key, rules[key]);
            });
            
            // Add images if any
            const imageInput = document.getElementById('photoInput');
            if (imageInput && imageInput.files.length > 0) {
                Array.from(imageInput.files).forEach(file => {
                    finalFormData.append('images[]', file);
                });
            }
            
            try {
                const response = await fetch(baseUrl + '/api/accommodation/create', {
                    method: 'POST',
                    body: finalFormData
                });

                console.log('POST', baseUrl + '/api/accommodation/create', response.status, response.statusText);
                const ct = response.headers.get('content-type') || '';
                let result;
                if (ct.includes('application/json')) {
                    try {
                        result = await response.json();
                    } catch (err) {
                        const text = await response.text();
                        console.error('Invalid JSON from server (create):', text, err);
                        alert('Server returned invalid response. Check console for details.');
                        return;
                    }
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response from server (create):', response.status, text);
                    alert('Server returned an unexpected response. See console for details.');
                    return;
                }

                if (result && result.success) {
                    // Clear localStorage
                    localStorage.removeItem('property_type');
                    localStorage.removeItem('property_features');
                    localStorage.removeItem('property_details');
                    localStorage.removeItem('property_description');
                    
                    // Navigate to success page
                    window.location.href = baseUrl + '/success';
                } else {
                    alert('Failed to create property: ' + (result.errors ? result.errors.join(', ') : 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating property:', error);
                alert('Failed to create property. Please try again.');
            }
        });
    }

    // ========== DASHBOARD - LIST PROPERTIES ==========
    const propertyListContainer = document.querySelector('.property-cards-grid');
    if (propertyListContainer) {
        const summaryListingsEl = document.getElementById('summaryListings');
        const summaryBookedEl = document.getElementById('summaryBooked');
        const summaryBookingsReceivedEl = document.getElementById('summaryBookingsReceived');
        const summaryOverallRatingEl = document.getElementById('summaryOverallRating');
        const summaryOverallRatingNoteEl = document.getElementById('summaryOverallRatingNote');

        function updateActivitySummary(properties) {
            const safeProperties = Array.isArray(properties) ? properties : [];
            const listings = safeProperties.length;
            const booked = safeProperties.reduce((sum, property) => {
                return sum + (parseInt(property.booked_rooms, 10) || 0);
            }, 0);
            const bookingsReceived = safeProperties.reduce((sum, property) => {
                return sum + (parseInt(property.bookings_received, 10) || 0);
            }, 0);
            const ratingTotals = safeProperties.reduce((totals, property) => {
                const ratingCount = parseInt(property.rating_count, 10) || 0;
                const averageRating = parseFloat(property.avg_rating || 0);

                if (ratingCount > 0 && averageRating > 0) {
                    totals.weightedSum += averageRating * ratingCount;
                    totals.totalReviews += ratingCount;
                }

                return totals;
            }, { weightedSum: 0, totalReviews: 0 });
            const overallRating = ratingTotals.totalReviews > 0
                ? ratingTotals.weightedSum / ratingTotals.totalReviews
                : 0;

            if (summaryListingsEl) summaryListingsEl.textContent = String(listings);
            if (summaryBookedEl) summaryBookedEl.textContent = String(booked);
            if (summaryBookingsReceivedEl) summaryBookingsReceivedEl.textContent = String(bookingsReceived);
            if (summaryOverallRatingEl) {
                summaryOverallRatingEl.textContent = ratingTotals.totalReviews > 0
                    ? `${overallRating.toFixed(1)}`
                    : '-';
            }
            if (summaryOverallRatingNoteEl) {
                summaryOverallRatingNoteEl.textContent = ratingTotals.totalReviews > 0
                    ? `Based on ${ratingTotals.totalReviews} review${ratingTotals.totalReviews > 1 ? 's' : ''}`
                    : 'Not yet rated';
            }
        }

        // Function to load user's properties
        async function loadUserProperties() {
            try {
                const response = await fetch(baseUrl + '/api/accommodation/list');
                console.log('GET', baseUrl + '/api/accommodation/list', response.status, response.statusText);
                const ctList = response.headers.get('content-type') || '';
                if (ctList.includes('application/json')) {
                    try {
                        const result = await response.json();
                        if (result.success) {
                            displayProperties(result.data);
                            updateActivitySummary(result.data);
                        } else {
                            console.error('Failed to load properties:', result.errors);
                            updateActivitySummary([]);
                        }
                    } catch (err) {
                        const text = await response.text();
                        console.error('Invalid JSON from server (list):', text, err);
                        updateActivitySummary([]);
                    }
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response from server (list):', response.status, text);
                    updateActivitySummary([]);
                }
            } catch (error) {
                console.error('Error loading properties:', error);
                updateActivitySummary([]);
            }
        }

        function displayProperties(properties) {
            propertyListContainer.innerHTML = '';
            
            // Update the listings count in activity summary
            const listingsCountElement = document.querySelector('.activity-summary .stat:first-child .stat-num');
            if (listingsCountElement) {
                listingsCountElement.textContent = properties.length;
            }
            
            if (properties.length === 0) {
                propertyListContainer.innerHTML = `
                    <div class="no-properties-message">
                        <i class="fas fa-home"></i>
                        <h3>No Properties Yet</h3>
                        <p>Start listing your properties to reach more guests and grow your business.</p>
                        <button onclick="window.location.href='${baseUrl}/index.php?url=Accomodation_provider/propertyListingStep1'">
                            <i class="fas fa-plus"></i> List Your First Property
                        </button>
                    </div>
                `;
                return;
            }
            
            properties.forEach(property => {
                const card = createPropertyCard(property);
                propertyListContainer.appendChild(card);
            });
        }

        function createPropertyCard(property) {
            const card = document.createElement('div');
            card.className = 'property-card';
            
            const statusClass = property.status === 'active' ? '' : property.status;
            const imagePath = property.main_image || 'assets/images/default-property.jpg';
            
            // Format price with commas
            const formattedPrice = property.price_per_night ? 
                parseFloat(property.price_per_night).toLocaleString('en-US') : '0';
            
            // Truncate description if too long
            const description = property.description || 'No description available';
            const shortDescription = description.length > 120 ? 
                description.substring(0, 120) + '...' : description;

            const ratingCount = parseInt(property.rating_count, 10) || 0;
            const avgRatingValue = parseFloat(property.avg_rating || 0);

            function getRatingStarsHtml(avg) {
                let html = '';
                for (let index = 1; index <= 5; index++) {
                    if (avg >= index) {
                        html += '<i class="fas fa-star"></i>';
                    } else if (avg >= index - 0.5) {
                        html += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        html += '<i class="far fa-star"></i>';
                    }
                }
                return html;
            }

            const ratingStarsHtml = getRatingStarsHtml(avgRatingValue);
            const ratingLabel = ratingCount > 0
                ? `${avgRatingValue.toFixed(1)} (${ratingCount})`
                : 'Not yet rated';
            
            // Format property type for display
            const propertyType = property.property_type ? 
                property.property_type.charAt(0).toUpperCase() + property.property_type.slice(1).replace(/_/g, ' ') : '';
            
            card.innerHTML = `
                <div class="property-card-image">
                    <img src="${baseUrl}/${imagePath}" alt="${property.title}" 
                         onerror="this.src='${baseUrl}/assets/images/default-property.jpg'">
                    <div class="property-card-badge">${propertyType}</div>
                    ${statusClass ? `<div class="property-card-status ${statusClass}">${statusClass.toUpperCase()}</div>` : ''}
                </div>
                <div class="property-card-content">
                    <h3 class="property-card-title">${property.title}</h3>
                    
                    <div class="property-card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${property.location}</span>
                    </div>

                    <div class="property-card-rating">
                        <div class="rating-stars">${ratingStarsHtml}</div>
                        <span>${ratingLabel}</span>
                    </div>
                    
                    <p class="property-card-description">${shortDescription}</p>
                    
                    <div class="property-card-footer">
                        <div class="property-card-price-row">
                            <div class="property-card-price">
                                <div class="property-card-price-amount">
                                    <span class="currency">LKR</span>
                                    <span>${formattedPrice}</span>
                                </div>
                                <span class="property-card-price-label">per night</span>
                            </div>
                        </div>
                        <div class="property-card-actions">
                            <button type="button" class="property-card-btn property-card-btn-edit" data-id="${property.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="property-card-btn property-card-btn-delete delete-btn" data-id="${property.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="property-card-btn property-card-btn-toggle toggle-btn ${property.status === 'active' ? 'active' : ''}" data-id="${property.id}" data-status="${property.status}">
                                <i class="fas fa-power-off"></i> ${property.status === 'active' ? 'Inactive' : 'Active'}
                            </button>
                        </div>
                    </div>
                </div>
            `;

            card.addEventListener('click', function() {
                window.location.href = `${baseUrl}/detailsProperty?id=${property.id}`;
            });
            
            // Add click handler to edit button
            const editBtn = card.querySelector('.property-card-btn-edit');
            editBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Store property info for breadcrumb
                sessionStorage.setItem('currentPropertyTitle', property.title || 'Property');
                sessionStorage.setItem('currentPropertyId', property.id);
                window.location.href = `${baseUrl}/index.php?url=Accomodation_provider/updateProperty&id=${property.id}`;
            });

            const deleteBtn = card.querySelector('.delete-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    if (typeof window.showConfirmDeleteModal === 'function') {
                        window.showConfirmDeleteModal(property.id);
                    } else {
                        if (!confirm('Are you sure you want to delete this property?')) return;
                        performDelete(property.id, deleteBtn);
                    }
                });
            }

            const toggleBtn = card.querySelector('.toggle-btn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const currentStatus = toggleBtn.dataset.status || property.status;
                    handleToggleStatus(property.id, currentStatus, toggleBtn);
                });
            }

            const actionsContainer = card.querySelector('.property-card-actions');
            if (actionsContainer) {
                actionsContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            return card;
        }

        // Load properties when page loads
        loadUserProperties();
            // expose loader for debugging and manual triggering
            try { window.loadUserProperties = loadUserProperties; } catch(e){}
    }
});

function resolveBaseUrl() {
    if (typeof window.getBaseUrl === 'function') {
        return window.getBaseUrl();
    }
    return '/TravelMate/public';
}

async function handleToggleStatus(id, currentStatus, toggleBtn) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    try {
        const res = await fetch(`${resolveBaseUrl()}/api/accommodation/toggleStatus`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}&status=${encodeURIComponent(newStatus)}`
        });
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) {
            const json = await res.json();
            if (json.success) {
                toggleBtn.dataset.status = newStatus;
                if (newStatus === 'active') {
                    toggleBtn.classList.add('active');
                    toggleBtn.innerHTML = '<i class="fas fa-power-off"></i> Inactive';
                } else {
                    toggleBtn.classList.remove('active');
                    toggleBtn.innerHTML = '<i class="fas fa-power-off"></i> Active';
                }

                const card = toggleBtn.closest('.property-card');
                if (card) {
                    const statusBadge = card.querySelector('.property-card-status');
                    if (newStatus === 'active') {
                        if (statusBadge) statusBadge.remove();
                    } else {
                        if (!statusBadge) {
                            const imageDiv = card.querySelector('.property-card-image');
                            const badge = document.createElement('div');
                            badge.className = `property-card-status ${newStatus}`;
                            badge.textContent = newStatus.toUpperCase();
                            imageDiv.appendChild(badge);
                        } else {
                            statusBadge.className = `property-card-status ${newStatus}`;
                            statusBadge.textContent = newStatus.toUpperCase();
                        }
                    }
                }

                if (typeof window.showStatusModal === 'function') {
                    window.showStatusModal(newStatus === 'active');
                } else {
                    alert(`Property ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`);
                }

                if (typeof window.loadUserProperties === 'function') {
                    window.loadUserProperties();
                }
            } else {
                alert('Failed to update status: ' + (json.errors || json.data || 'unknown'));
            }
        } else {
            const text = await res.text();
            console.error('Toggle non-json response:', text);
            alert('Failed to update status. See console for details.');
        }
    } catch (err) {
        console.error('Toggle error', err);
        alert('Failed to update status. See console.');
    }
}

    // Delegated click handlers for property action buttons (View / Update / Delete)
    document.addEventListener('click', function(e){
        const viewBtn = e.target.closest('.view-btn');
        if (viewBtn) {
            const id = viewBtn.dataset.id;
            if (id) {
                window.location.href = `${resolveBaseUrl()}/detailsProperty?id=${id}`;
            }
            return;
        }

        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            if (id) {
                window.location.href = `${resolveBaseUrl()}/updateProperty?id=${id}`;
            }
            return;
        }

        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
            const id = delBtn.dataset.id;
            if (!id) return;
            
            // Show custom confirmation modal instead of browser confirm
            if (typeof window.showConfirmDeleteModal === 'function') {
                window.showConfirmDeleteModal(id);
            } else {
                // Fallback to browser confirm
                if (!confirm('Are you sure you want to delete this property?')) return;
                performDelete(id, delBtn);
            }
            return;
        }

        const toggleBtn = e.target.closest('.toggle-btn');
        if (toggleBtn) {
            const id = toggleBtn.dataset.id;
            const currentStatus = toggleBtn.dataset.status;
            if (!id) return;

            handleToggleStatus(id, currentStatus, toggleBtn);
            return;
        }
    });

// Function to perform the actual delete operation
async function performDelete(id, buttonElement) {
    try {
        const res = await fetch(`${resolveBaseUrl()}/api/accommodation/delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}`
        });
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) {
            const json = await res.json();
            if (json.success) {
                // remove card from DOM
                const card = buttonElement ? buttonElement.closest('.property-card') : document.querySelector(`[data-id="${id}"]`)?.closest('.property-card');
                if (card) card.remove();

                if (typeof window.loadUserProperties === 'function') {
                    window.loadUserProperties();
                }
                
                // Update the listings count in activity summary
                const listingsCountElement = document.querySelector('.activity-summary .stat:first-child .stat-num');
                if (listingsCountElement) {
                    const currentCount = parseInt(listingsCountElement.textContent) || 0;
                    listingsCountElement.textContent = Math.max(0, currentCount - 1);
                }
                
                // Show success modal
                if (typeof window.showDeleteModal === 'function') {
                    window.showDeleteModal();
                } else {
                    alert('Property deleted');
                }
            } else {
                alert('Failed to delete property: ' + (json.errors || json.data || 'unknown'));
            }
        } else {
            const text = await res.text();
            console.error('Delete non-json response:', text);
            alert('Failed to delete property. See console for details.');
        }
    } catch (err) {
        console.error('Delete error', err);
        alert('Failed to delete property. See console.');
    }
}

// Listen for custom delete confirmation event
document.addEventListener('confirmDeleteProperty', function(e) {
    if (e.detail && e.detail.id) {
        performDelete(e.detail.id, null);
    }
});

// Global functions for property actions
function editProperty(id) {
    window.location.href = `${resolveBaseUrl()}/updateProperty?id=${id}`;
}

async function deleteProperty(id) {
    if (!confirm('Are you sure you want to delete this property?')) {
        return;
    }
    
    try {
        const response = await fetch(`${resolveBaseUrl()}/api/accommodation/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        });

        console.log('POST delete', `${resolveBaseUrl()}/api/accommodation/delete`, response.status, response.statusText);
        const ctDel = response.headers.get('content-type') || '';
        if (ctDel.includes('application/json')) {
            try {
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Failed to delete property: ' + (result.errors ? result.errors.join(', ') : 'Unknown error'));
                }
            } catch (err) {
                const text = await response.text();
                console.error('Invalid JSON from server (delete):', text, err);
                alert('Failed to delete property. See console for details.');
            }
        } else {
            const text = await response.text();
            console.error('Non-JSON response from server (delete):', response.status, text);
            alert('Failed to delete property. See console for details.');
        }
    } catch (error) {
        console.error('Error deleting property:', error);
        alert('Failed to delete property. Please try again.');
    }
}