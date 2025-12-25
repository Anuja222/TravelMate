// JS for house rules page
document.querySelector('.rules-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    // prevent default form submit — we handle saving via JS
});

document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.querySelector('.continue-btn');
    if (!saveBtn) return;

    saveBtn.addEventListener('click', function() {
        try {
            const toggles = Array.from(document.querySelectorAll('.toggle-switch')).map(cb => cb.checked);
            const pets = document.querySelector('input[name="pets"]:checked')?.value || 'no';
            // selects are inside child divs; use querySelectorAll to reliably pick both
            const checkinSelects = Array.from(document.querySelectorAll('.checkin-row select'));
            const checkinFrom = (checkinSelects[0]?.value) || '';
            const checkinUntil = (checkinSelects[1]?.value) || '';
            const checkoutSelects = Array.from(document.querySelectorAll('.checkout-row select'));
            const checkoutFrom = (checkoutSelects[0]?.value) || '';
            const checkoutUntil = (checkoutSelects[1]?.value) || '';

            const houseRules = {
                toggles,
                pets,
                checkinFrom,
                checkinUntil,
                checkoutFrom,
                checkoutUntil
            };
            sessionStorage.setItem('tm_houseRules', JSON.stringify(houseRules));
        } catch (e) {
            console.warn('Failed to persist house rules to sessionStorage', e);
        }
        // Redirect using MVC route
        window.location.href = '/TravelMate/Accomodation_provider/viewProperty';
    });
});
