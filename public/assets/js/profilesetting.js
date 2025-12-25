function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhoto').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

        function removePhoto() {
            document.getElementById('profilePhoto').src = '../../images/default-avatar.png';
            document.getElementById('photoInput').value = '';
        }

        function toggleSetting(element) {
            element.classList.toggle('active');
        }

        function saveProfile() {
            // Collect form data
            const formData = new FormData(document.getElementById('profileForm'));
            
            // Here you would typically send the data to your server
            alert('Profile updated successfully!');
            
            // You can add actual API call here
            console.log('Saving profile data:', Object.fromEntries(formData));
        }

        function cancelChanges() {
            if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
                // Reset form to original values or redirect
                window.location.reload();
            }
        }

        // Auto-save functionality
        let saveTimeout;
        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                console.log('Auto-saving profile...');
                // Implement auto-save logic here
            }, 2000);
        }

        // Add auto-save listeners to form inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', autoSave);
            });
        });