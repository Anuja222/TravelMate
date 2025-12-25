document.addEventListener('DOMContentLoaded', function() {
    // Helper function to get base URL
    function getBaseUrl() {
        return '/TravelMate';
    }

    const baseUrl = getBaseUrl();

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
            window.location.href = baseUrl + '/propertyDetails';
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
            
            try {
                const response = await fetch(baseUrl + '/api/accommodation/create', {
                    method: 'POST',
                    body: finalFormData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Clear localStorage
                    localStorage.removeItem('property_type');
                    localStorage.removeItem('property_features');
                    localStorage.removeItem('property_details');
                    localStorage.removeItem('property_description');
                    
                    // Navigate to success page
                    window.location.href = baseUrl + '/accommodation/success';
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
        loadUserProperties();
    }
});