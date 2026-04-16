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
            document.getElementById('profilePhoto').src = 'assets/images/profile.jpg';
            document.getElementById('photoInput').value = '';
        }

        function toggleSetting(element) {
            element.classList.toggle('active');
        }

        function saveProfile(event) {
            // prevent default form submission
            if (event) event.preventDefault();
            
            // collect form data
            const formData = new FormData(document.getElementById('profileForm'));
            
            // add additional fields from other sections
            formData.append('country', document.getElementById('country').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('timezone', document.getElementById('timezone').value);
            formData.append('travelStyle', document.getElementById('travelStyle').value);
            formData.append('budget', document.getElementById('budget').value);
            formData.append('interests', document.getElementById('interests').value);
            
            // add profile photo if uploaded
            const photoInput = document.getElementById('photoInput');
            if(photoInput.files && photoInput.files.length > 0) {
                formData.append('profilePhoto', photoInput.files[0]);
            }
            
            // add flag to handle remove photo
            if (document.getElementById('profilePhoto').src.includes('assets/images/profile.jpg')) {
                formData.append('removePhoto', 'true');
            }
            
            // show loading state
            const saveBtn = document.querySelector('.save-btn');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            
            console.log('Sending profile update...');
            
            // send data to server
            // determine the base path so fetch works from any URL nesting
            const basePath = window.location.pathname.split('/').slice(0, window.location.pathname.split('/').indexOf('public') + 1).join('/');
            
            fetch(basePath + '/profile_setting/update', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                // first check if response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                
                // try to parse as JSON
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
                    // optionally redirect after a delay
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
                // reset form to original values or redirect
                window.location.reload();
            }
        }

        // show success modal
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        // close success modal
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                window.location.href = 'dashboard';
            }, 300);
        }

        // auto-save functionality
        let saveTimeout;
        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                console.log('Auto-saving profile...');
                // implement auto-save logic here
            }, 2000);
        }

        // add auto-save listeners to form inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', autoSave);
            });
        });