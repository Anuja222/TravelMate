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

    // Helper function to capitalize first letter
    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

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
        
        // Load property type from localStorage and display it
        const propertyType = localStorage.getItem('property_type');
        if (propertyType) {
            const propertyTypeInput = document.getElementById('property_type');
            if (propertyTypeInput) {
                propertyTypeInput.value = propertyType;
            }
        }
        
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
            const propertyTypeValue = localStorage.getItem('property_type');
            
            // Ensure property_type is in the features object
            features['property_type'] = propertyTypeValue;
            
            formData.forEach((value, key) => {
                features[key] = value;
            });
            
            // Store in localStorage
            localStorage.setItem('property_features', JSON.stringify(features));
            
            console.log('Features stored:', features);
            
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

});

    // Global function for property deletion
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