document.addEventListener('DOMContentLoaded', function() {
    // helper to compute base URL similar to accommodation.js
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
    // property Type Selection
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

    // accommodation Features Form
    const featuresForm = document.querySelector('.features-form');
    if (featuresForm) {
        featuresForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const features = Array.from(document.querySelectorAll('input[name="features[]"]:checked'))
                .map(cb => cb.value);
            
            localStorage.setItem('property_features', JSON.stringify(features));
            window.location.href = 'propertyDetails';
        });
    }

    // property Details Form
    const propertyDetailsForm = document.querySelector('.property-details-form');
    if (propertyDetailsForm) {
        propertyDetailsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const details = {
                rooms: document.querySelector('input[name="rooms"]').value,
                bathrooms: document.querySelector('input[name="bathrooms"]').value,
                maxGuests: document.querySelector('input[name="max_guests"]').value
            };
            
            localStorage.setItem('property_details', JSON.stringify(details));
            window.location.href = baseUrl + '/photoUpload';
        });
    }

    // photo Upload Form
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

        photoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const description = document.getElementById('propertyDescription').value;
            try { localStorage.setItem('property_description', description); } catch(e){ console.error(e); }
            window.location.href = baseUrl + '/houseRules';
        });

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

        // remove image handler (delegated)
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

    // house Rules Form
    const rulesForm = document.querySelector('.house-rules-form');
    if (rulesForm) {
        rulesForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            
            // gather all data from localStorage
            const propertyType = localStorage.getItem('property_type');
            const features = JSON.parse(localStorage.getItem('property_features') || '[]');
            const details = JSON.parse(localStorage.getItem('property_details') || '{}');
            const description = localStorage.getItem('property_description');
            
            // add all data to FormData (normalize detail keys and coerce types)
            formData.append('property_type', propertyType);
            formData.append('features', JSON.stringify(features));
            // details may use different key names (max_guests or maxGuests). Normalize safely.
            const rawRooms = details.rooms ?? details.room ?? details.rooms_count ?? details.room_count ?? 0;
            const rawBathrooms = details.bathrooms ?? details.bath ?? details.bathroom_count ?? 0;
            const rawMaxGuests = details.max_guests ?? details.maxGuests ?? details.guests ?? details.maxGuestsCount ?? 0;
            const rooms = Number.isFinite(Number(rawRooms)) ? Number(rawRooms) : 0;
            const bathrooms = Number.isFinite(Number(rawBathrooms)) ? Number(rawBathrooms) : 0;
            const maxGuests = Number.isFinite(Number(rawMaxGuests)) ? Number(rawMaxGuests) : 0;
            formData.append('rooms', rooms);
            formData.append('bathrooms', bathrooms);
            formData.append('max_guests', maxGuests);
            formData.append('description', description);
            
            // add house rules data (safe checks)
            const smokingEl = document.querySelector('input[name="smoking"]');
            const partiesEl = document.querySelector('input[name="parties"]');
            const petsEl = document.querySelector('input[name="pets"]:checked');
            formData.append('smoking', smokingEl ? (smokingEl.checked ? 1 : 0) : 0);
            formData.append('parties', partiesEl ? (partiesEl.checked ? 1 : 0) : 0);
            formData.append('pets', petsEl ? petsEl.value : 'no');
            
            // add check-in/out times
            const checkInStart = (document.querySelector('select[name="check_in_start"]') || {}).value || '';
            const checkInEnd = (document.querySelector('select[name="check_in_end"]') || {}).value || '';
            // for check out we used names check_out_start / check_out_end in view
            const checkOutStart = (document.querySelector('select[name="check_out_start"]') || {}).value || '';
            const checkOutEnd = (document.querySelector('select[name="check_out_end"]') || {}).value || '';
            
            formData.append('check_in_start', checkInStart);
            formData.append('check_in_end', checkInEnd);
            // send check out as combined or the end value
            formData.append('check_out_time', checkOutEnd || checkOutStart);

            // include the saved property title if present, otherwise fallback to property_type or a default
            const propertyTitle = localStorage.getItem('property_title') || '';
            const titleToSend = propertyTitle.trim() || (propertyType ? propertyType.replace(/[_-]/g, ' ') : 'Untitled property');
            formData.append('title', titleToSend);

            try {
                // append stored images (dataURLs) as Blob files to the final FormData
                const stored = JSON.parse(localStorage.getItem('property_images') || '[]');
                for (let i = 0; i < stored.length; i++) {
                    const item = stored[i];
                    try {
                        const blob = dataURLtoBlob(item.dataUrl);
                        // use a predictable field name that matches server handling
                        formData.append('images[]', blob, item.name || `image_${i}.jpg`);
                    } catch(e){ console.error('Failed to convert image', e); }
                }

                const response = await fetch(baseUrl + '/api/accommodation/create', {
                    method: 'POST',
                    body: formData
                });

                console.log('POST /api/accommodation/create response:', response.status, response.statusText);
                const contentType = response.headers.get('content-type') || '';
                let result;

                if (contentType.includes('application/json')) {
                    try {
                        result = await response.json();
                    } catch (parseErr) {
                        const text = await response.text();
                        console.error('Failed to parse JSON response from server:', text, parseErr);
                        alert('Server returned invalid JSON. Check console for details.');
                        return;
                    }
                } else {
                    const text = await response.text();
                    console.error('Server returned non-JSON response:', response.status, text);
                    alert('Server returned an unexpected response. See console for details.');
                    return;
                }

                if (result && result.success) {
                    // clear all localStorage
                    localStorage.removeItem('property_type');
                    localStorage.removeItem('property_features');
                    localStorage.removeItem('property_details');
                    localStorage.removeItem('property_description');
                    localStorage.removeItem('property_images');
                    
                    window.location.href = baseUrl + '/success';
                } else {
                    alert('Error creating property: ' + result.errors.join('\n'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while saving the property.');
            }
        });
    }

    // helper function to display image previews
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

    // convert dataURL to Blob
    function dataURLtoBlob(dataurl) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1], bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){ u8arr[n] = bstr.charCodeAt(n); }
        return new Blob([u8arr], {type:mime});
    }
});