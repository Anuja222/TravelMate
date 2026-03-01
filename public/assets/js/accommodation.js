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
                        } else {
                            console.error('Failed to load properties:', result.errors);
                        }
                    } catch (err) {
                        const text = await response.text();
                        console.error('Invalid JSON from server (list):', text, err);
                    }
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response from server (list):', response.status, text);
                }
            } catch (error) {
                console.error('Error loading properties:', error);
            }
        }

        function displayProperties(properties) {
            propertyListContainer.innerHTML = '';
            
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
            
            // Add click handler to edit button
            const editBtn = card.querySelector('.property-card-btn-edit');
            editBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                window.location.href = `${baseUrl}/index.php?url=Accomodation_provider/updateProperty&id=${property.id}`;
            });
            
            return card;
        }

        // Load properties when page loads
        loadUserProperties();
            // expose loader for debugging and manual triggering
            try { window.loadUserProperties = loadUserProperties; } catch(e){}
    }
});

    // Delegated click handlers for property action buttons (View / Update / Delete)
    document.addEventListener('click', function(e){
        const viewBtn = e.target.closest('.view-btn');
        if (viewBtn) {
            const id = viewBtn.dataset.id;
            if (id) {
                window.location.href = `${getBaseUrl()}/detailsProperty?id=${id}`;
            }
            return;
        }

        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            if (id) {
                window.location.href = `${getBaseUrl()}/updateProperty?id=${id}`;
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
            
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            
            // call toggle API
            (async function(){
                try {
                    const res = await fetch(`${getBaseUrl()}/api/accommodation/toggleStatus`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${encodeURIComponent(id)}&status=${encodeURIComponent(newStatus)}`
                    });
                    const ct = res.headers.get('content-type') || '';
                    if (ct.includes('application/json')) {
                        const json = await res.json();
                        if (json.success) {
                            // Update button appearance and status
                            toggleBtn.dataset.status = newStatus;
                            if (newStatus === 'active') {
                                toggleBtn.classList.add('active');
                                toggleBtn.innerHTML = '<i class="fas fa-power-off"></i> Inactive';
                            } else {
                                toggleBtn.classList.remove('active');
                                toggleBtn.innerHTML = '<i class="fas fa-power-off"></i> Active';
                            }
                            
                            // Update status badge
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
                            
                            // Show success modal
                            if (typeof window.showStatusModal === 'function') {
                                window.showStatusModal(newStatus === 'active');
                            } else {
                                alert(`Property ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`);
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
            })();
            return;
        }
    });

// Function to perform the actual delete operation
async function performDelete(id, buttonElement) {
    try {
        const res = await fetch(`${getBaseUrl()}/api/accommodation/delete`, {
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
    window.location.href = `${getBaseUrl()}/updateProperty?id=${id}`;
}

async function deleteProperty(id) {
    if (!confirm('Are you sure you want to delete this property?')) {
        return;
    }
    
    try {
        const response = await fetch(`${getBaseUrl()}/api/accommodation/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        });

        console.log('POST delete', `${getBaseUrl()}/api/accommodation/delete`, response.status, response.statusText);
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