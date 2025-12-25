// photoUpload.js
// JS for Photo Upload page

document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photoInput');
    const form = document.querySelector('.photo-upload-form');
    form.addEventListener('submit', function(e) {
        if (photoInput.files.length < 5) {
            e.preventDefault();
            alert('Please upload at least 5 photos.');
        }
    });
});
