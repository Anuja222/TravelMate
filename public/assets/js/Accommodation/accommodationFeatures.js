// accommodationFeatures.js
// jS for Accommodation Features page

document.addEventListener('DOMContentLoaded', function() {
    // example: Add active state to checkboxes
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

    // example: Save & Continue button click
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function(e) {
            // collect selected features
            const checked = Array.from(document.querySelectorAll('.features-form input[type="checkbox"]:checked')).map(i => i.value);
            try {
                sessionStorage.setItem('tm_features', JSON.stringify(checked));
            } catch (err) {
                console.warn('Failed to save features to sessionStorage', err);
            }
            // redirect back to viewProperty using MVC route so the front controller handles it
            window.location.href = 'viewProperty';
        });
    }
});
