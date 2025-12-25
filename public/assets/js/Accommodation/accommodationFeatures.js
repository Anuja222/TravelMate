// accommodationFeatures.js
// JS for Accommodation Features page

document.addEventListener('DOMContentLoaded', function() {
    // Example: Add active state to checkboxes
    const checkboxes = document.querySelectorAll('.features-form input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                checkbox.parentElement.classList.add('checked');
            } else {
                checkbox.parentElement.classList.remove('checked');
            }
        });
    });

    // Example: Save & Continue button click
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function(e) {
            // Collect selected features
            const checked = Array.from(document.querySelectorAll('.features-form input[type="checkbox"]:checked')).map(i => i.value);
            try {
                sessionStorage.setItem('tm_features', JSON.stringify(checked));
            } catch (err) {
                console.warn('Failed to save features to sessionStorage', err);
            }
            // Redirect back to viewProperty using MVC route so the front controller handles it
            window.location.href = 'viewProperty';
        });
    }
});
