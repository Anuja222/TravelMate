<!-- Edit Your Property Details Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Property Details</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/propertyDetails.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>Edit Your Property Details</h1>
    <form class="property-details-form" action="photoUpload.view.php" method="get">
        <div class="property-rooms">
            <fieldset>
                <legend> Bedrooms </legend>
                <label>Where can people sleep?</label>
                <div id="bedroom-list">
                    <a href="/TravelMate/public/index.php?url=Accomodation_provider/bedRoom&bed=1" class="bedroom-slot"><span class="remove-bed" title="Remove"><img src="/TravelMate/public/assets/images/trashBin.png" alt="Remove" width="18" height="18"></span><div class="bedroom-info"><div class="bedroom-label">Bedroom 1</div><div class="bedroom-subtitle"></div></div></a>
                </div>
                <button type="button" class="add-bedroom-btn">+ Add bedroom</button>
            </fieldset>
        </div>
        <div class="property-guests">
            <label>How many guests can stay? (Maximum 2 people can stay in a one room.)</label>
            <div class="counter">
                <button type="button" class="decrement">-</button>
                <span class="count">2</span>
                <button type="button" class="increment">+</button>
            </div>
        </div>
        <div class="property-bathrooms">
            <label>How many bathrooms are there? (Minimum 1 bathroom should be there for a property.)</label>
                        <div class="counter">
                            <button type="button" class="decrement">-</button>
                            <span class="count">2</span>
                            <button type="button" class="increment">+</button>
                        </div>
                    </div>
            </fieldset>
        <div class="property-children">
            <label>Do you allow childrens?</label>
            <input type="radio" name="children" value="yes"> Yes
            <input type="radio" name="children" value="no"> No
        </div>
    <button type="button" class="save-btn">Save & Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/propertyDetails.js"></script>
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

        // Delegate remove click (works when clicking span or the image inside it)
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

    // If redirected back from bedRoom page with details, update the corresponding slot
    (function applyIncomingBedData(){
            const params = new URLSearchParams(window.location.search);
            const bed = params.get('bed');
            const type = params.get('type');
            const count = params.get('count');
            if (!bed) return;
            const bedIndex = parseInt(bed, 10);
            if (isNaN(bedIndex) || bedIndex < 1) return;

            // Ensure there are enough slots
            while (bedroomCount < bedIndex) {
                bedroomCount++;
                bedroomList.appendChild(createBedroomSlot(bedroomCount));
            }

            // Update the target slot label and subtitle
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

            // Update bedroomCount in case new slots were added
            bedroomCount = bedroomList.querySelectorAll('.bedroom-slot').length;
            // persist the updated info into sessionStorage
            const stored = readStored();
            while (stored.length < bedroomCount) stored.push(null);
            stored[bedIndex - 1] = (type || count) ? { subtitle: `${type ? type : ''}${type && count ? ' ' : ''}${count ? `(${count})` : ''}` } : null;
            writeStored(stored);

            // Remove query params from URL to avoid re-applying on refresh
            if (window.history && window.history.replaceState) {
                const cleanUrl = window.location.pathname + window.location.hash;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        })();

        // Intercept Save & Continue button to sync storage and redirect to viewProperty
        const form = document.querySelector('.property-details-form');
        const saveBtn = document.querySelector('.save-btn');
        saveBtn.addEventListener('click', function(e){
            // gather current values
            syncStorageFromDOM();
            const guests = document.querySelector('.property-guests .count')?.textContent?.trim() || '';
            const bathrooms = document.querySelector('.property-bathrooms .count')?.textContent?.trim() || '';
            const childrenRadio = document.querySelector('input[name="children"]:checked');
            const children = childrenRadio ? childrenRadio.value : '';

            // Build property details object and persist to sessionStorage for preview
            const prop = {
                guests: guests,
                bathrooms: bathrooms,
                children: children
            };
            try { sessionStorage.setItem('tm_propertyDetails', JSON.stringify(prop)); } catch (err) { console.warn('Could not save property details to sessionStorage', err); }

            // Choose the working route: probe the pretty route and fall back to front-controller if it 404s
            (function chooseAndNavigate(){
                const pretty = '/TravelMate/Accomodation_provider/viewProperty';
                const fallback = '/TravelMate/public/index.php?url=Accomodation_provider/viewProperty';
                // Use a HEAD request to check existence without downloading the full page
                fetch(pretty, { method: 'HEAD' }).then(resp => {
                    if (resp && resp.ok) {
                        window.location.assign(pretty);
                    } else {
                        window.location.assign(fallback);
                    }
                }).catch(() => {
                    // network error or CORS; use fallback
                    window.location.assign(fallback);
                });
            })();
        });
    });
    </script>
</body>
</html>
