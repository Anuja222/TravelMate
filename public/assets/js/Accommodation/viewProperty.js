// View Property Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Image Gallery Navigation
    const mainImage = document.querySelector('.main-image img');
    const galleryImages = document.querySelectorAll('.image-grid img');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    let currentImageIndex = 0;
    const images = Array.from(galleryImages).map(img => img.src);
    images.unshift(mainImage.src);

    // Update main image
    function updateMainImage(index) {
        mainImage.src = images[index];
        currentImageIndex = index;
        updateGalleryState();
    }

    // Update gallery state (e.g., active states, etc)
    function updateGalleryState() {
        galleryImages.forEach((img, index) => {
            if (index + 1 === currentImageIndex) {
                img.style.opacity = '0.6';
            } else {
                img.style.opacity = '1';
            }
        });
    }

    // Next button click handler
    nextBtn.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        updateMainImage(currentImageIndex);
    });

    // Previous button click handler
    prevBtn.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        updateMainImage(currentImageIndex);
    });

    // Thumbnail click handlers
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', () => {
            updateMainImage(index + 1); // +1 because main image is at index 0
        });
    });

    // Delete property button
    // NOTE: Removed JavaScript confirmation + API call so the anchor
    // <a class="delete-btn" href="deleteProperty.view.php"> will navigate directly
    // to the delete page. If you want a client-side confirmation, re-add here.

    // Note: edit buttons are plain anchors in the view; do not override their default navigation.

    // Helper function to get property ID from URL or data attribute
    function getPropertyId() {
        // Implement this based on your URL structure or data attributes
        return document.querySelector('[data-property-id]')?.dataset.propertyId;
    }

    // Initialize the gallery state
    updateGalleryState();
    // Apply any saved accommodation features from sessionStorage
    (function applySavedFeatures(){
        try {
            const raw = sessionStorage.getItem('tm_features');
            if (!raw) return;
            const features = JSON.parse(raw);
            if (!Array.isArray(features)) return;
            const thingsSection = document.querySelector('.property-things ul');
            if (!thingsSection) return;
            // Clear existing and render saved list
            thingsSection.innerHTML = '';
            features.forEach(f => {
                const li = document.createElement('li');
                // human-friendly labels (simple mapping)
                const map = {
                    air_conditioning: 'Air conditioning',
                    heating: 'Heating',
                    wifi: 'Free Wifi',
                    ev_charging: 'Electric vehicle charging station',
                    kitchen: 'Kitchen',
                    kitchenette: 'Kitchenette',
                    washing_machine: 'Washing machine',
                    tv: 'Flat-screen TV',
                    pool: 'Swimming pool',
                    hot_tub: 'Hot tub',
                    minibar: 'Minibar',
                    sauna: 'Sauna',
                    balcony: 'Balcony',
                    garden_view: 'Garden view',
                    terrace: 'Terrace',
                    view: 'View'
                };
                li.textContent = map[f] || f;
                thingsSection.appendChild(li);
            });
        } catch (e) {
            console.warn('Unable to apply saved features', e);
        }
    })();

    // Apply saved house rules from sessionStorage (if any)
    (function applySavedHouseRules(){
        try {
            const raw = sessionStorage.getItem('tm_houseRules');
            if (!raw) return;
            const rules = JSON.parse(raw);
            if (!rules || typeof rules !== 'object') return;
            const rulesList = document.querySelector('.house-rules .rules-list');
            if (!rulesList) return;
            // Build new rules HTML
            const rows = [];
            // toggles: assume order [smoking, parties]
            const toggles = Array.isArray(rules.toggles) ? rules.toggles : [];
            rows.push(`<div class="rule-row"><span>Smoking Allowed</span><span>${toggles[0] ? 'Yes' : 'No'}</span></div>`);
            rows.push(`<div class="rule-row"><span>Parties/Events Allowed</span><span>${toggles[1] ? 'Yes' : 'No'}</span></div>`);
            // pets
            rows.push(`<div class="rule-row"><span>Pets Allowed</span><span>${rules.pets === 'yes' ? 'Yes' : (rules.pets === 'request' ? 'Upon request' : 'No')}</span></div>`);
            // check-in/out
            const checkinText = (rules.checkinFrom && rules.checkinUntil) ? `${rules.checkinFrom} - ${rules.checkinUntil}` : (rules.checkinFrom || rules.checkinUntil || '');
            const checkoutText = (rules.checkoutFrom && rules.checkoutUntil) ? `${rules.checkoutFrom} - ${rules.checkoutUntil}` : (rules.checkoutFrom || rules.checkoutUntil || '');
            rows.push(`<div class="rule-row"><span>Check-in</span><span>${checkinText}</span></div>`);
            rows.push(`<div class="rule-row"><span>Check-out</span><span>${checkoutText}</span></div>`);

            rulesList.innerHTML = rows.join('');
            // clear key to avoid reapplying old values
            sessionStorage.removeItem('tm_houseRules');
        } catch (e) {
            console.warn('Unable to apply saved house rules', e);
        }
    })();

    // Apply saved services from sessionStorage
    (function applySavedServices(){
        try {
            const raw = sessionStorage.getItem('tm_services');
            if (!raw) return;
            const services = JSON.parse(raw);
            const servicesList = document.querySelector('.services ul');
            if (!servicesList) return;
            const items = [];
            if (services.breakfast === 'yes') items.push('Breakfast');
            if (services.parking === 'free') items.push('Parking (free)');
            if (services.parking === 'paid') items.push('Parking (paid)');
            if (items.length === 0) items.push('No services');
            servicesList.innerHTML = items.map(i => `<li>${i}</li>`).join('');
            sessionStorage.removeItem('tm_services');
        } catch (e) {
            console.warn('Unable to apply saved services', e);
        }
    })();

    // Apply saved property details (guests, bathrooms, children) from sessionStorage
    (function applySavedPropertyDetails(){
        try {
            const raw = sessionStorage.getItem('tm_propertyDetails');
            console.debug('viewProperty: tm_propertyDetails raw=', raw);
            if (!raw) return;
            const prop = JSON.parse(raw);
            console.debug('viewProperty: parsed prop=', prop);
            if (!prop || typeof prop !== 'object') return;

            // Update maximum guests display
            if (prop.guests) {
                const rows = Array.from(document.querySelectorAll('.property-details .detail-row'));
                let updated = false;
                for (const row of rows) {
                    const label = row.querySelector('.detail-item span:first-child');
                    if (label) {
                        const text = label.textContent.trim().toLowerCase();
                        if (text === 'maximum guests' || text === 'maximum guests' || text === 'maximum guests') {
                            const valueSpan = row.querySelector('.detail-item span:nth-child(2)');
                            if (valueSpan) { valueSpan.textContent = prop.guests; updated = true; break; }
                        }
                    }
                }
                console.debug('viewProperty: guests updated=', updated);
            }

            // Update bathroom count
            if (prop.bathrooms) {
                const rows = Array.from(document.querySelectorAll('.property-details .detail-row'));
                let updated = false;
                for (const row of rows) {
                    const label = row.querySelector('.detail-item span:first-child');
                    if (label) {
                        const text = label.textContent.trim().toLowerCase();
                        if (text === 'bathroom count' || text === 'bathrooms' || text === 'bathroom') {
                            const valueSpan = row.querySelector('.detail-item span:nth-child(2)');
                            if (valueSpan) { valueSpan.textContent = prop.bathrooms; updated = true; break; }
                        }
                    }
                }
                console.debug('viewProperty: bathrooms updated=', updated);
            }

            // Update children allow
            if (typeof prop.children !== 'undefined') {
                const rows = Array.from(document.querySelectorAll('.property-details .detail-row'));
                let updated = false;
                for (const row of rows) {
                    const label = row.querySelector('.detail-item span:first-child');
                    if (label) {
                        const text = label.textContent.trim().toLowerCase();
                        if (text === 'children allow' || text === 'children allowed' || text === 'children') {
                            const valueSpan = row.querySelector('.detail-item span:nth-child(2)');
                            if (valueSpan) { valueSpan.textContent = (prop.children === 'yes' ? 'Yes' : (prop.children === 'no' ? 'No' : prop.children)); updated = true; break; }
                        }
                    }
                }
                console.debug('viewProperty: children updated=', updated);
            }

            // We don't need to persist this preview beyond the immediate apply
            sessionStorage.removeItem('tm_propertyDetails');
        } catch (e) {
            console.warn('Unable to apply saved property details', e);
        }
    })();

    // Apply saved price preview (if any)
    (function applySavedPrice(){
        try {
            const raw = sessionStorage.getItem('tm_price');
            if (!raw) return;
            const p = JSON.parse(raw);
            if (!p || typeof p !== 'object') return;
            // find price rows and update values
            const priceRows = Array.from(document.querySelectorAll('.prices .price-row'));
            for (const row of priceRows) {
                const label = row.querySelector('span:first-child')?.textContent?.trim().toLowerCase();
                const valueSpan = row.querySelector('span:nth-child(2)');
                if (!label || !valueSpan) continue;
                if (label.includes('price per night') && p.pricePerNight) {
                    valueSpan.textContent = `${p.pricePerNight} LKR`;
                }
                if (label.includes('price per guests') && p.pricePerGuest) {
                    valueSpan.textContent = `${p.pricePerGuest} LKR`;
                }
            }
            sessionStorage.removeItem('tm_price');
        } catch (e) {
            console.warn('Unable to apply saved price', e);
        }
    })();
});
