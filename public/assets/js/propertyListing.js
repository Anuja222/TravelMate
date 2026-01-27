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
    if (photoForm) {
        const MAX_IMAGES = 25;
        const photoInput = document.getElementById('photoInput');

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
            const previewContainer = document.createElement('div');
            previewContainer.className = 'image-previews';

            stored.forEach((item, idx) => {
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.innerHTML = `\
                    <img src="${item.dataUrl}" alt="Preview ${idx+1}">\
                    <button type="button" class="remove-image" data-idx="${idx}">&times;</button>\
                `;
                previewContainer.appendChild(preview);
            });

            const existing = document.querySelector('.image-previews');
            if (existing) existing.remove();
            const container = document.querySelector('.photo-upload-box');
            if (container) container.after(previewContainer);
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
        displayImagePreviewsFromStored();
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