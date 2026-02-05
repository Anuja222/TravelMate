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

        function saveProfile(event) {
            // Prevent default form submission
            if (event) event.preventDefault();
            
            // Collect form data
            const formData = new FormData(document.getElementById('profileForm'));
            
            // Add additional fields from other sections
            formData.append('country', document.getElementById('country').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('timezone', document.getElementById('timezone').value);
            formData.append('travelStyle', document.getElementById('travelStyle').value);
            formData.append('budget', document.getElementById('budget').value);
            formData.append('interests', document.getElementById('interests').value);
            
            // Show loading state
            const saveBtn = document.querySelector('.save-btn');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            
            console.log('Sending profile update...');
            
            // Send data to server
            fetch('profile_setting/update', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                // First check if response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                
                // Try to parse as JSON
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response was:', text);
                        throw new Error('Server returned invalid JSON: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                if (data.success) {
                    showSuccessModal();
                    // Optionally redirect after a delay
                    setTimeout(() => {
                        window.location.href = 'dashboard';
                    }, 2000);
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred while updating profile: ' + error.message);
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
        }

        function cancelChanges() {
            if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
                // Reset form to original values or redirect
                window.location.reload();
            }
        }

        // Show success modal
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        // Close success modal
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                window.location.href = 'dashboard';
            }, 300);
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