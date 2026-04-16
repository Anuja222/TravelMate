// services.js
// jS for Services page

document.addEventListener('DOMContentLoaded', function() {
    // save & Continue handler
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            try {
                const breakfast = document.querySelector('input[name="breakfast"]:checked')?.value || 'no';
                const parking = document.querySelector('input[name="parking"]:checked')?.value || 'no';
                const services = { breakfast, parking };
                sessionStorage.setItem('tm_services', JSON.stringify(services));
            } catch (e) {
                console.warn('Failed to save services to sessionStorage', e);
            }
            window.location.href = '/TravelMate/Accomodation_provider/viewProperty';
        });
    }
});
