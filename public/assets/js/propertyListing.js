console.log('propertyListing.js loaded');
document.addEventListener('DOMContentLoaded', function() {
    // Helper to compute base URL similar to accommodation.js
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
    // Property Type Selection
    const propertyTypes = document.querySelectorAll('.property-type');
    propertyTypes.forEach(type => {
        const selectButton = type.querySelector('.list-property-btn');
        if (selectButton) {
            selectButton.addEventListener('click', function() {
                const propertyType = type.dataset.type;
                if (propertyType) {
                    localStorage.setItem('property_type', propertyType);
                    window.location.href = 'accommodationFeatures';
                } else {
                    console.error('Property type not found');
                }
            });
        }
    });

    // Accommodation Features Form - Allow normal form submission to /saveFeatures
    // (No JavaScript hijacking needed - forms submit normally via POST)

    // Property Details Form - Allow normal form submission to /saveDetails
    // (No JavaScript hijacking needed - forms submit normally via POST)

    // Photo Upload Form
    const photoForm = document.querySelector('.photo-upload-form');
    console.log('photoForm element found:', photoForm);
    if (photoForm) {
        console.log('Initializing photo upload form handlers');
        const MAX_IMAGES = 25;
        const photoInput = document.getElementById('photoInput');
        console.log('photoInput element found:', photoInput);

        function fileToDataURL(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        async function handleFiles(files) {
            const stored = JSON.parse(localStorage.getItem('property_images') || '[]');
            for (let i = 0; i < files.length && stored.length < MAX_IMAGES; i++) {
                const f = files[i];
                try {
                    const dataUrl = await fileToDataURL(f);
                    stored.push({ name: f.name, type: f.type, dataUrl });
                } catch (e) {
                    console.error('Failed to read file', e);
                }
            }
            try { localStorage.setItem('property_images', JSON.stringify(stored)); } catch(e){ console.error(e); }
            displayImagePreviewsFromStored();
        }

        photoInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files || []);
            if (files.length) handleFiles(files);
            // clear input so same file can be selected again
            photoInput.value = '';
        });

        // Photo form will submit normally to /savePhoto - no hijacking needed

        function displayImagePreviewsFromStored() {
            const stored = JSON.parse(localStorage.getItem('property_images') || '[]');
            
            // Get or create the persistent preview container
            let previewContainer = document.querySelector('.image-previews');
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.className = 'image-previews';
                const container = document.querySelector('.photo-upload-box');
                if (container) container.after(previewContainer);
            }
            
            // Clear and re-render
            previewContainer.innerHTML = '';
            stored.forEach((item, idx) => {
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.innerHTML = `\
                    <img src="${item.dataUrl}" alt="Preview ${idx+1}">\
                    <button type="button" class="remove-image" data-idx="${idx}">&times;</button>\
                `;
                previewContainer.appendChild(preview);
            });
        }

        // Remove image handler (delegated)
        document.addEventListener('click', function(e){
            const btn = e.target.closest('.remove-image');
            if (!btn) return;
            const idx = parseInt(btn.dataset.idx, 10);
            const stored = JSON.parse(localStorage.getItem('property_images') || '[]');
            if (!isNaN(idx) && stored[idx]){
                stored.splice(idx, 1);
                try { localStorage.setItem('property_images', JSON.stringify(stored)); } catch(e){console.error(e);} 
                displayImagePreviewsFromStored();
            }
        });

        // show previews on load
        console.log('About to call displayImagePreviewsFromStored()');
        displayImagePreviewsFromStored();
        console.log('About to add submit event listener');
        photoForm.addEventListener('submit', async function(e) {
            console.log('SUBMIT EVENT FIRED - e.preventDefault() will be called');
            e.preventDefault();
            console.log('Photo form submit intercepted');
            const stored = JSON.parse(localStorage.getItem('property_images') || '[]');
            const description = document.getElementById('propertyDescription').value;
            
            console.log('Stored images count:', stored.length);
            console.log('Description:', description);
            
            if (!description.trim()) {
                alert('Please provide a property description');
                return;
            }
            
            // Create FormData with files from localStorage
            const formData = new FormData();
            formData.append('propertyDescription', description);
            
            // Convert dataURLs back to blobs and append as files
            for (let i = 0; i < stored.length; i++) {
                const item = stored[i];
                try {
                    const blob = dataURLtoBlob(item.dataUrl);
                    formData.append('images[]', blob, item.name || `image_${i}.jpg`);
                    console.log('Added image', i, 'to FormData:', item.name);
                } catch (e) {
                    console.error('Failed to convert image', i, e);
                }
            }
            
            console.log('Sending FormData to /TravelMate/public/savePhoto');
            try {
                const response = await fetch('/TravelMate/public/savePhoto', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('Response status:', response.status);
                if (response.ok) {
                    console.log('Success, redirecting to houseRules');
                    // Redirect to house rules page
                    window.location.href = '/TravelMate/public/houseRules';
                } else {
                    const text = await response.text();
                    console.error('Error response:', text);
                    alert('Error uploading photos. Please try again.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Error uploading photos. Please try again.');
            }
        });
    }
    // House Rules Form - Allow normal form submission to /saveAccommodation
    // (No JavaScript hijacking - just a normal POST submission)

    // Helper function to display image previews
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
                    <button type="button" class="remove-image">&times;</button>
                `;
                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
        
        const existingPreviews = document.querySelector('.image-previews');
        if (existingPreviews) {
            existingPreviews.remove();
        }
        document.querySelector('.photo-upload-box').after(previewContainer);
    }

    // Convert dataURL to Blob
    function dataURLtoBlob(dataurl) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1], bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){ u8arr[n] = bstr.charCodeAt(n); }
        return new Blob([u8arr], {type:mime});
    }
});