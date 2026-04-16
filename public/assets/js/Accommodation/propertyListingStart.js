// property Listing Start Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
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
    const propertyTypes = document.querySelectorAll('.property-type');
    const propertyForm = document.querySelector('.property-form');
    const listingButtons = document.querySelectorAll('.list-property-btn');
    const continueButton = document.querySelector('.continue-btn');

    // show property form when a property type is selected
    listingButtons.forEach(button => {
        button.addEventListener('click', function() {
            propertyTypes.forEach(type => {
                type.classList.remove('selected');
            });
            this.closest('.property-type').classList.add('selected');
            propertyForm.style.display = 'block';
            propertyForm.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // handle form submission
    continueButton.addEventListener('click', function(e) {
        e.preventDefault();
        // get form values
    const propertyNameInput = document.querySelector('input[placeholder="Enter your property name"]');
    const propertyName = propertyNameInput ? propertyNameInput.value : '';
        const location = document.querySelector('input[placeholder="Enter location"]').value;
        const floorNumber = document.querySelector('input[placeholder="Enter apartment/floor number"]').value;
        const country = document.querySelector('input[placeholder="Enter country"]').value;
        const city = document.querySelector('input[placeholder="Enter city"]').value;
        const postalCode = document.querySelector('input[placeholder="Enter postal code"]').value;

        // validate form
        if (!propertyName || !location || !country || !city) {
            alert('Please fill in all required fields');
            return;
        }

        // submit form data
        console.log('Form submitted:', {
            propertyName,
            location,
            floorNumber,
            country,
            city,
            postalCode
        });
        
    // save property title to localStorage for later steps
    if (propertyName) localStorage.setItem('property_title', propertyName);
    // redirect to next step (you can change this URL as needed)
    window.location.href = baseUrl + '/accommodation-provider/property-details';
    });
});
