// photoGallery.js
// JS for Photo Gallery page

document.addEventListener('DOMContentLoaded', function() {
    // Delete photo button logic
    document.querySelectorAll('.delete-photo-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.parentElement.remove();
        });
    });
    // Intercept the photo form submit and redirect to the property view
    // This mirrors other editor flows: do a quick probe of pretty route and fall back
    const addPhotoForm = document.querySelector('.add-photo-form');
    if (addPhotoForm) {
        addPhotoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // If you need to actually upload files first, remove this preventDefault
            // and implement an AJAX upload or let the form submit normally.

            const pretty = '/TravelMate/Accomodation_provider/viewProperty';
            const fallback = '/TravelMate/public/index.php?url=Accomodation_provider/viewProperty';
            fetch(pretty, { method: 'HEAD' }).then(resp => {
                if (resp && resp.ok) window.location.assign(pretty);
                else window.location.assign(fallback);
            }).catch(() => {
                window.location.assign(fallback);
            });
        });
    }
});
