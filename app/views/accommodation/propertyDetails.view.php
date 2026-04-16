<!-- edit Your Property Details Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Property Details</title>
    <link rel="stylesheet" href="assets/css/Accommodation/detailsProperty.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>Your Property Details</h1>
    <form class="property-details-form" action="photoUpload" method="get">
        <div class="property-rooms">
            <fieldset>
                <legend> Bedrooms </legend>
                <label>Where can people sleep?</label>
                <div id="bedroom-list">
                    <a href="bedRoom" class="bedroom-slot"><span class="remove-bed" title="Remove"><img src="/TravelMate/public/assets/images/trashBin.png" alt="Remove" width="18" height="18"></span><div class="bedroom-info"><div class="bedroom-label">Bedroom 1</div><div class="bedroom-subtitle"></div></div></a>
                </div>
                <button type="button" class="add-bedroom-btn">+ Add bedroom</button>
            </fieldset>
        </div>
        <div class="property-guests">
            <label>How many guests can stay? (Maximum 2 people can stay in a one room.)</label>
            <div class="counter" data-counter="guests">
                <button type="button" class="decrement">-</button>
                <span class="count">2</span>
                <button type="button" class="increment">+</button>
            </div>
            <!-- hidden input to persist guests count for form submission / JS -->
            <input type="hidden" name="max_guests" class="input-max-guests" value="2">
        </div>
        <div class="property-bathrooms">
            <label>How many bathrooms are there? (Minimum 1 bathroom should be there for a property.)</label>
            <div class="counter" data-counter="bathrooms">
                <button type="button" class="decrement">-</button>
                <span class="count">1</span>
                <button type="button" class="increment">+</button>
            </div>
            <!-- hidden input to persist bathrooms count for form submission / JS -->
            <input type="hidden" name="bathrooms" class="input-bathrooms" value="1">
        </div>
        </fieldset>
        <div class="property-children">
            <label>Do you allow childrens?</label>
            <input type="radio" name="children" value="yes"> Yes
            <input type="radio" name="children" value="no"> No
        </div>
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addBtn = document.querySelector('.add-bedroom-btn');
        const bedroomList = document.getElementById('bedroom-list');
        let bedroomCount = bedroomList.querySelectorAll('.bedroom-slot').length;

        const STORAGE_KEY = 'tm_bedrooms';

        function readStored() {
            try { const raw = sessionStorage.getItem(STORAGE_KEY); return raw ? JSON.parse(raw) : []; } catch(e){ return []; }
        }
        function writeStored(arr){ try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(arr)); } catch(e){} }

        function syncStorageFromDOM(){
            const slots = bedroomList.querySelectorAll('.bedroom-slot');
            const arr = [];
            slots.forEach(s => {
                const subtitle = s.querySelector('.bedroom-subtitle');
                if (subtitle && subtitle.textContent.trim()) arr.push({ subtitle: subtitle.textContent.trim() });
                else arr.push(null);
            });
            writeStored(arr);
        }

        function loadStoredBedrooms(){
            const arr = readStored();
            if (!arr || !arr.length) return;
            while (bedroomCount < arr.length){ bedroomCount++; bedroomList.appendChild(createBedroomSlot(bedroomCount)); }
            const slots = bedroomList.querySelectorAll('.bedroom-slot');
            arr.forEach((item, idx) => {
                const target = slots[idx];
                if (target && item && item.subtitle){
                    const subtitle = target.querySelector('.bedroom-subtitle');
                    if (subtitle) subtitle.textContent = item.subtitle;
                }
            });
        }

        function createBedroomSlot(index) {
            const slot = document.createElement('a');
            slot.href = `/TravelMate/public/index.php?url=Accomodation_provider/bedRoom&bed=${index}`;
            slot.className = 'bedroom-slot';
            slot.innerHTML = `<span class="remove-bed" title="Remove"><img src="/TravelMate/public/assets/images/trashBin.png" alt="Remove" width="18" height="18"></span><div class="bedroom-info"><div class="bedroom-label">Bedroom ${index}</div><div class="bedroom-subtitle"></div></div>`;
            return slot;
        }

        addBtn.addEventListener('click', function() {
            bedroomCount++;
            const newSlot = createBedroomSlot(bedroomCount);
            // insert before the add button (i.e., at the end of bedroom-list)
            bedroomList.appendChild(newSlot);
            // update storage: add placeholder for new slot
            const arr = readStored(); arr.push(null); writeStored(arr);
        });

        // delegate remove click (works when clicking span or the image inside it)
        bedroomList.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-bed');
            if (removeBtn) {
                e.preventDefault();
                const slot = removeBtn.closest('.bedroom-slot');
                if (slot) slot.remove();
                // re-number bedrooms
                const slots = bedroomList.querySelectorAll('.bedroom-slot');
                slots.forEach((s, i) => {
                    const label = s.querySelector('.bedroom-label');
                    if (label) label.textContent = `Bedroom ${i+1}`;
                });
                bedroomCount = slots.length;
                // update storage to reflect removal and new ordering
                syncStorageFromDOM();
            }
        });

    // load any persisted bedroom info
    loadStoredBedrooms();

    // if redirected back from bedRoom page with details, update the corresponding slot
    (function applyIncomingBedData(){
            const params = new URLSearchParams(window.location.search);
            const bed = params.get('bed');
            const type = params.get('type');
            const count = params.get('count');
            if (!bed) return;
            const bedIndex = parseInt(bed, 10);
            if (isNaN(bedIndex) || bedIndex < 1) return;

            // ensure there are enough slots
            while (bedroomCount < bedIndex) {
                bedroomCount++;
                bedroomList.appendChild(createBedroomSlot(bedroomCount));
            }

            // update the target slot label and subtitle
            const slots = bedroomList.querySelectorAll('.bedroom-slot');
            const target = slots[bedIndex - 1];
            if (target) {
                const label = target.querySelector('.bedroom-label');
                const subtitle = target.querySelector('.bedroom-subtitle');
                if (label) label.textContent = `Bedroom ${bedIndex}`;
                let sub = '';
                if (type) sub += type;
                if (count) sub += (sub ? ' ' : '') + `(${count})`;
                if (subtitle) subtitle.textContent = sub;
            }

            // update bedroomCount in case new slots were added
            bedroomCount = bedroomList.querySelectorAll('.bedroom-slot').length;
            // persist the updated info into sessionStorage
            const stored = readStored();
            while (stored.length < bedroomCount) stored.push(null);
            stored[bedIndex - 1] = (type || count) ? { subtitle: `${type ? type : ''}${type && count ? ' ' : ''}${count ? `(${count})` : ''}` } : null;
            writeStored(stored);

            // remove query params from URL to avoid re-applying on refresh
            if (window.history && window.history.replaceState) {
                const cleanUrl = window.location.pathname + window.location.hash;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        })();

        // intercept form submit to sync storage and redirect to photoUpload
        const form = document.querySelector('.property-details-form');
        // counter initialization and handlers
        (function initCounters(){
            // helpers
            function setCounter(container, value){
                const span = container.querySelector('.count');
                const inputName = container.dataset.counter;
                span.textContent = value;
                if (inputName === 'guests') {
                    const hidden = document.querySelector('.input-max-guests');
                    if (hidden) hidden.value = value;
                } else if (inputName === 'bathrooms') {
                    const hidden = document.querySelector('.input-bathrooms');
                    if (hidden) hidden.value = value;
                }
            }

            function changeCounter(container, delta, min, max){
                const span = container.querySelector('.count');
                let val = parseInt(span.textContent, 10) || 0;
                val = Math.min(max, Math.max(min, val + delta));
                setCounter(container, val);
            }

            // load saved counts from localStorage (property_details)
            let savedDetails = {};
            try { savedDetails = JSON.parse(localStorage.getItem('property_details') || '{}'); } catch(e){ savedDetails = {}; }

            const guestCounter = document.querySelector('[data-counter="guests"]');
            const bathCounter = document.querySelector('[data-counter="bathrooms"]');

            if (guestCounter) {
                const initial = parseInt(savedDetails.max_guests, 10) || parseInt(document.querySelector('.input-max-guests').value, 10) || 2;
                setCounter(guestCounter, initial);
                guestCounter.querySelector('.decrement').addEventListener('click', function(){ changeCounter(guestCounter, -1, 1, 100); });
                guestCounter.querySelector('.increment').addEventListener('click', function(){ changeCounter(guestCounter, 1, 1, 100); });
            }

            if (bathCounter) {
                const initialB = parseInt(savedDetails.bathrooms, 10) || parseInt(document.querySelector('.input-bathrooms').value, 10) || 1;
                setCounter(bathCounter, initialB);
                bathCounter.querySelector('.decrement').addEventListener('click', function(){ changeCounter(bathCounter, -1, 1, 100); });
                bathCounter.querySelector('.increment').addEventListener('click', function(){ changeCounter(bathCounter, 1, 1, 100); });
            }
        })();

        form.addEventListener('submit', function(e){
            // sync bedroom info from sessionStorage
            syncStorageFromDOM();

            // build property_details object
            const details = {};
            const formData = new FormData(form);
            formData.forEach((value, key) => {
                details[key] = value;
            });

            // include numeric counts from hidden inputs
            const maxGuestsEl = document.querySelector('.input-max-guests');
            const bathroomsEl = document.querySelector('.input-bathrooms');
            if (maxGuestsEl) details.max_guests = parseInt(maxGuestsEl.value, 10) || 1;
            if (bathroomsEl) details.bathrooms = parseInt(bathroomsEl.value, 10) || 1;

            // include bedroom details from sessionStorage (tm_bedrooms)
            try {
                const bedrooms = JSON.parse(sessionStorage.getItem('tm_bedrooms') || '[]');
                details.bedrooms = bedrooms;
            } catch(e){ details.bedrooms = []; }

            // persist to localStorage
            try { localStorage.setItem('property_details', JSON.stringify(details)); } catch(e){ console.error('Failed to save property_details', e); }

            // allow form to submit normally to photoUpload (method/get or action)
        });
    });
    </script>
</body>
</html>
