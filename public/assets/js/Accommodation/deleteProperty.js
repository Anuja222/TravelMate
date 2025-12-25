// JS for delete property confirmation page
document.addEventListener('DOMContentLoaded', function() {
    console.log('[deleteProperty.js] script loaded');
    const yesBtn = document.querySelector('.yes-btn');
    const noBtn = document.querySelector('.no-btn');
    if (!yesBtn) console.warn('[deleteProperty.js] .yes-btn not found');
    if (!noBtn) console.warn('[deleteProperty.js] .no-btn not found');

    yesBtn?.addEventListener('click', function() {
        const propertyName = document.querySelector('.property-name')?.textContent || 'this property';
        const message = `The property "${propertyName}" will be deleted. OK to proceed?`;
        if (confirm(message)) {
             // Redirect to dashboard route (front controller handles views)
             window.location.href = '/TravelMate/Accomodation_provider/dashboard';
        } else {
            // cancelled — stay on page
        }
    });

    noBtn?.addEventListener('click', function() {
        // Redirect back to property view
          window.location.href = '/TravelMate/Accomodation_provider/viewProperty';
    });
});
