/**
 * settings.js - Settings page functionality for Transporter
 * Path: TravelMate/assets/js/Transpoter/settings.js
 */

const BASE_PATH = window.location.pathname.includes('/TravelMate/') 
    ? '/TravelMate/public' 
    : '';

// DOM Elements
document.addEventListener('DOMContentLoaded', function() {
    initApp();
});

/**
 * Main initialization function
 */
function initApp() {
    initTabs();
    initEventListeners();
    loadAccountStatus(); // This is the critical function
    initModalClose();
}

/**
 * Initialize all event listeners
 */
function initEventListeners() {
    // Forms
    const profileForm = document.getElementById('profileForm');
    const securityForm = document.getElementById('securityForm');
    const notificationForm = document.getElementById('notificationForm');
    
    if (profileForm) profileForm.addEventListener('submit', handleProfileSubmit);
    if (securityForm) securityForm.addEventListener('submit', handleSecuritySubmit);
    if (notificationForm) notificationForm.addEventListener('submit', handleNotificationSubmit);
    
    // Account Status
    const accountStatusToggle = document.getElementById('accountStatusToggle');
    const deactivationReason = document.getElementById('deactivationReason');
    const confirmDeactivationCheckbox = document.getElementById('confirmDeactivationCheckbox');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    const cancelDeactivationBtn = document.getElementById('cancelDeactivationBtn');
    const confirmReactivationBtn = document.getElementById('confirmReactivationBtn');
    const cancelReactivationBtn = document.getElementById('cancelReactivationBtn');
    
    if (accountStatusToggle) accountStatusToggle.addEventListener('change', handleAccountStatusToggle);
    if (deactivationReason) deactivationReason.addEventListener('change', toggleOtherReasonField);
    if (confirmDeactivationCheckbox) confirmDeactivationCheckbox.addEventListener('change', toggleConfirmDeactivationBtn);
    if (confirmDeactivationBtn) confirmDeactivationBtn.addEventListener('click', confirmDeactivation);
    if (cancelDeactivationBtn) cancelDeactivationBtn.addEventListener('click', cancelDeactivation);
    if (confirmReactivationBtn) confirmReactivationBtn.addEventListener('click', confirmReactivation);
    if (cancelReactivationBtn) cancelReactivationBtn.addEventListener('click', cancelReactivation);
    
    // Delete account
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    
    if (deleteAccountBtn) deleteAccountBtn.addEventListener('click', showDeleteModal);
    if (confirmDeleteBtn) confirmDeleteBtn.addEventListener('click', handleAccountDeletion);
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', hideDeleteModal);
    
    // Profile photo
    const profilePhoto = document.getElementById('profilePhoto');
    const removePhotoBtn = document.getElementById('removePhoto');
    
    if (profilePhoto) profilePhoto.addEventListener('change', handleProfilePhotoUpload);
    if (removePhotoBtn) removePhotoBtn.addEventListener('click', removeProfilePhoto);
    
    // Password strength
    const newPasswordInput = document.getElementById('new_password');
    if (newPasswordInput) newPasswordInput.addEventListener('input', checkPasswordStrength);
    
    // Delete account reason radios
    const reasonRadios = document.querySelectorAll('input[name="delete_reason"]');
    reasonRadios.forEach(radio => radio.addEventListener('change', toggleFeedbackField));
}

/**
 * Tab switching functionality
 */
function initTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all tabs and panes
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));

            // Add active class to clicked tab
            btn.classList.add('active');

            // Show corresponding pane
            const tabId = btn.getAttribute('data-tab');
            const targetPane = document.getElementById(`${tabId}-tab`);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
}

/**
 * Load account status from server - FIXED VERSION
 */
function loadAccountStatus() {
    const accountStatusToggle = document.getElementById('accountStatusToggle');
    const currentStatusBadge = document.getElementById('currentStatusBadge');
    const vehicleVisibilityBadge = document.getElementById('vehicleVisibilityBadge');
    const statusText = document.getElementById('statusText');
    
    if (!accountStatusToggle) return;
    
    // Show loading state
    accountStatusToggle.disabled = true;
    if (currentStatusBadge) currentStatusBadge.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    console.log('Fetching account status from:', BASE_PATH + '/api/settings/account-status');
    
    fetch(`${BASE_PATH}/api/settings/account-status`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(result => {
        console.log('Account status API response:', result);
        
        if (result.success && result.data) {
            // Get the exact status value from API
            const statusValue = result.data.status;
            console.log('Status value from API:', statusValue);
            
            // Determine if active or deactivated - be explicit about this
            const isActive = (statusValue === 'active');
            console.log('Is active?', isActive, '(status was:', statusValue + ')');
            
            // Set toggle state
            accountStatusToggle.checked = isActive;
            
            // Update UI display
            updateStatusUI(isActive, result.data);
            
            // Update guidelines based on upcoming bookings
            updateDeactivationGuidelines(result.data);
        } else {
            console.error('Failed to load account status - API returned:', result);
            showToast('Failed to load account status', 'error');
            // Set to default active state
            accountStatusToggle.checked = true;
            updateStatusUI(true, {});
        }
    })
    .catch(error => {
        console.error('Error loading account status:', error);
        showToast('Error loading account status', 'error');
        // Set to default active state on error
        accountStatusToggle.checked = true;
        updateStatusUI(true, {});
    })
    .finally(() => {
        accountStatusToggle.disabled = false;
    });
}

/**
 * Update deactivation guidelines based on upcoming bookings
 */
function updateDeactivationGuidelines(data) {
    const guidelinesContainer = document.getElementById('deactivationGuidelines');
    if (!guidelinesContainer) return;
    
    if (data.upcoming_bookings > 0) {
        guidelinesContainer.innerHTML = `
            <div class="guidelines-box warning">
                <h4><i class="fas fa-exclamation-triangle"></i> Important: You Have Upcoming Bookings</h4>
                <p>You currently have <strong>${data.upcoming_bookings}</strong> upcoming confirmed booking(s).</p>
                <div class="guidelines-content">
                    <h5>Before Deactivating Your Account:</h5>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> All existing bookings MUST be fulfilled - you cannot cancel them</li>
                        <li><i class="fas fa-check-circle"></i> You are responsible for completing all confirmed trips</li>
                        <li><i class="fas fa-check-circle"></i> If you have vehicle issues, you must arrange alternative transportation</li>
                        <li><i class="fas fa-check-circle"></i> If you have driver issues, you must arrange an alternative driver</li>
                        <li><i class="fas fa-check-circle"></i> Communicate any changes to your travellers immediately</li>
                    </ul>
                    <p class="note"><i class="fas fa-info-circle"></i> After deactivation, your vehicles will be hidden from future searches, but you can still manage your existing bookings through the Bookings page.</p>
                </div>
            </div>
        `;
    } else {
        guidelinesContainer.innerHTML = `
            <div class="guidelines-box info">
                <h4><i class="fas fa-info-circle"></i> Account Deactivation Guidelines</h4>
                <div class="guidelines-content">
                    <ul>
                        <li><i class="fas fa-check-circle"></i> When deactivated, your vehicles will be hidden from traveller searches</li>
                        <li><i class="fas fa-check-circle"></i> You will not receive any new booking requests</li>
                        <li><i class="fas fa-check-circle"></i> You can still manage your existing bookings</li>
                        <li><i class="fas fa-check-circle"></i> You can reactivate your account at any time</li>
                        <li><i class="fas fa-check-circle"></i> All your vehicle data and settings will be preserved</li>
                    </ul>
                </div>
            </div>
        `;
    }
}

/**
 * Update status UI - FIXED VERSION
 */
function updateStatusUI(isActive, data = {}) {
    const currentStatusBadge = document.getElementById('currentStatusBadge');
    const vehicleVisibilityBadge = document.getElementById('vehicleVisibilityBadge');
    const statusText = document.getElementById('statusText');
    const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
    const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
    const accountStatusToggle = document.getElementById('accountStatusToggle');
    
    console.log('updateStatusUI called with isActive =', isActive, 'data =', data);
    
    if (!currentStatusBadge || !vehicleVisibilityBadge || !statusText) {
        console.error('Missing UI elements:', { currentStatusBadge, vehicleVisibilityBadge, statusText });
        return;
    }
    
    if (isActive) {
        console.log('Displaying ACTIVE status');
        currentStatusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Active';
        currentStatusBadge.className = 'status-badge-active';
        vehicleVisibilityBadge.innerHTML = '<i class="fas fa-eye"></i> Vehicles visible to travellers';
        vehicleVisibilityBadge.className = 'visibility-badge-active';
        statusText.textContent = 'Active';
        statusText.style.color = '#10b981';
        
        // Ensure toggle is checked
        if (accountStatusToggle) accountStatusToggle.checked = true;
        
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'none';
    } else {
        console.log('Displaying DEACTIVATED status');
        currentStatusBadge.innerHTML = '<i class="fas fa-pause-circle"></i> Deactivated';
        currentStatusBadge.className = 'status-badge-deactivated';
        vehicleVisibilityBadge.innerHTML = '<i class="fas fa-eye-slash"></i> Vehicles hidden from travellers';
        vehicleVisibilityBadge.className = 'visibility-badge-deactivated';
        statusText.textContent = 'Deactivated';
        statusText.style.color = '#ef4444';
        
        // Ensure toggle is unchecked
        if (accountStatusToggle) accountStatusToggle.checked = false;
        
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'block';
        
        // Show deactivation info if available
        if (data.deactivated_at) {
            console.log('Showing deactivation info for date:', data.deactivated_at);
            const date = new Date(data.deactivated_at);
            const infoEl = document.getElementById('deactivationInfo');
            if (infoEl) {
                infoEl.innerHTML = `
                    <div class="deactivation-info">
                        <p><strong>Deactivated on:</strong> ${date.toLocaleDateString()} at ${date.toLocaleTimeString()}</p>
                        <p><strong>Reason:</strong> ${data.deactivation_reason || 'Not specified'}</p>
                    </div>
                `;
            }
        }
    }
}

/**
 * Handle account status toggle - FIXED VERSION
 */
function handleAccountStatusToggle() {
    const isActive = document.getElementById('accountStatusToggle').checked;
    const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
    const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
    
    if (!isActive) {
        // User is trying to deactivate - show deactivation confirmation
        if (deactivationConfirmBox) {
            deactivationConfirmBox.style.display = 'block';
            document.getElementById('accountStatusToggle').checked = true; // Revert toggle
        }
    } else {
        // User is trying to reactivate - show reactivation confirmation
        if (reactivationConfirmBox) {
            reactivationConfirmBox.style.display = 'block';
            document.getElementById('accountStatusToggle').checked = false; // Revert toggle
        }
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
    }
}

/**
 * Toggle other reason field
 */
function toggleOtherReasonField() {
    const deactivationReason = document.getElementById('deactivationReason');
    const otherReasonGroup = document.getElementById('otherReasonGroup');
    
    if (deactivationReason && deactivationReason.value === 'other') {
        otherReasonGroup.style.display = 'block';
    } else if (otherReasonGroup) {
        otherReasonGroup.style.display = 'none';
    }
}

/**
 * Toggle confirm deactivation button
 */
function toggleConfirmDeactivationBtn() {
    const confirmDeactivationCheckbox = document.getElementById('confirmDeactivationCheckbox');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    
    if (confirmDeactivationBtn) {
        confirmDeactivationBtn.disabled = !confirmDeactivationCheckbox.checked;
    }
}

/**
 * Confirm deactivation - FIXED VERSION
 */
function confirmDeactivation() {
    const deactivationReason = document.getElementById('deactivationReason');
    const otherReason = document.getElementById('otherReason');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    
    // Gather data
    let reason = deactivationReason ? deactivationReason.value : '';
    let feedback = '';
    
    if (reason === 'other') {
        reason = 'other';
        feedback = otherReason ? otherReason.value : '';
    }
    
    // Show loading state
    const originalText = confirmDeactivationBtn.innerHTML;
    confirmDeactivationBtn.innerHTML = '<span class="spinner"></span> Deactivating...';
    confirmDeactivationBtn.disabled = true;
    
    console.log('Sending deactivation request to:', BASE_PATH + '/api/settings/account-status');
    console.log('Payload:', { status: 'deactivated', reason: reason, feedback: feedback });
    
    // Send to API
    fetch(`${BASE_PATH}/api/settings/account-status`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: 'deactivated',
            reason: reason,
            feedback: feedback
        })
    })
    .then(response => response.json())
    .then(result => {
        console.log('Deactivation response:', result);
        
        if (result.success) {
            // Update UI
            document.getElementById('accountStatusToggle').checked = false;
            updateStatusUI(false, result.data);
            
            // Hide confirmation box
            const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
            if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
            
            // Reset form
            if (deactivationReason) deactivationReason.value = '';
            if (otherReason) otherReason.value = '';
            const otherReasonGroup = document.getElementById('otherReasonGroup');
            if (otherReasonGroup) otherReasonGroup.style.display = 'none';
            const confirmCheckbox = document.getElementById('confirmDeactivationCheckbox');
            if (confirmCheckbox) confirmCheckbox.checked = false;
            
            // Show success message
            showToast(result.data?.message || 'Account deactivated successfully', 'success');
            
            // Reload account status to show updated info
            loadAccountStatus();
        } else {
            showToast(result.errors?.general || 'Failed to deactivate account', 'error');
            // Reset button
            confirmDeactivationBtn.innerHTML = originalText;
            confirmDeactivationBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
        // Reset button
        confirmDeactivationBtn.innerHTML = originalText;
        confirmDeactivationBtn.disabled = false;
    });
}

/**
 * Cancel deactivation
 */
function cancelDeactivation() {
    document.getElementById('accountStatusToggle').checked = true;
    const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
    if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
    
    // Reset form
    const deactivationReason = document.getElementById('deactivationReason');
    if (deactivationReason) deactivationReason.value = '';
    
    const otherReason = document.getElementById('otherReason');
    if (otherReason) otherReason.value = '';
    
    const otherReasonGroup = document.getElementById('otherReasonGroup');
    if (otherReasonGroup) otherReasonGroup.style.display = 'none';
    
    const confirmCheckbox = document.getElementById('confirmDeactivationCheckbox');
    if (confirmCheckbox) confirmCheckbox.checked = false;
    
    toggleConfirmDeactivationBtn();
}

/**
 * Confirm reactivation - FIXED VERSION
 */
function confirmReactivation() {
    const confirmReactivationBtn = document.getElementById('confirmReactivationBtn');
    
    // Show loading state
    const originalText = confirmReactivationBtn.innerHTML;
    confirmReactivationBtn.innerHTML = '<span class="spinner"></span> Reactivating...';
    confirmReactivationBtn.disabled = true;
    
    console.log('Sending reactivation request to:', BASE_PATH + '/api/settings/account-status');
    
    // Send to API
    fetch(`${BASE_PATH}/api/settings/account-status`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: 'active',
            reason: '',
            feedback: ''
        })
    })
    .then(response => response.json())
    .then(result => {
        console.log('Reactivation response:', result);
        
        if (result.success) {
            // Update UI
            document.getElementById('accountStatusToggle').checked = true;
            updateStatusUI(true, result.data);
            
            // Hide confirmation box
            const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
            if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'none';
            
            // Show toast
            showToast(result.data?.message || 'Account reactivated successfully', 'success');
            
            // Reload account status
            loadAccountStatus();
        } else {
            showToast(result.errors?.general || 'Failed to reactivate account', 'error');
            // Reset button
            confirmReactivationBtn.innerHTML = originalText;
            confirmReactivationBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
        // Reset button
        confirmReactivationBtn.innerHTML = originalText;
        confirmReactivationBtn.disabled = false;
    });
}

/**
 * Cancel reactivation
 */
function cancelReactivation() {
    document.getElementById('accountStatusToggle').checked = false;
    const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
    if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'none';
}

/**
 * Handle profile form submission
 */
function handleProfileSubmit(e) {
    e.preventDefault();
    
    if (validateProfileForm()) {
        const submitBtn = document.getElementById('profileSaveBtn');
        const spinner = document.getElementById('profileSpinner');
        
        // Show loading state
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        
        // Simulate API call
        setTimeout(() => {
            const formData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                dateOfBirth: document.getElementById('dateOfBirth').value,
                gender: document.getElementById('gender').value
            };
            
            // Save to localStorage (replace with actual API call)
            localStorage.setItem('profileSettings', JSON.stringify(formData));
            
            // Reset button state
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            
            // Show success message
            showToast('Profile updated successfully!', 'success');
        }, 1500);
    }
}

/**
 * Validate profile form
 */
function validateProfileForm() {
    let isValid = true;
    
    resetErrors('profileForm');
    
    const firstName = document.getElementById('firstName').value.trim();
    if (!firstName) {
        showError('firstNameGroup', 'First name is required');
        isValid = false;
    }
    
    const lastName = document.getElementById('lastName').value.trim();
    if (!lastName) {
        showError('lastNameGroup', 'Last name is required');
        isValid = false;
    }
    
    const email = document.getElementById('email').value.trim();
    if (!email) {
        showError('emailGroup', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('emailGroup', 'Please enter a valid email address');
        isValid = false;
    }
    
    const phone = document.getElementById('phone').value.trim();
    if (!phone) {
        showError('phoneGroup', 'Phone number is required');
        isValid = false;
    } else if (!isValidPhone(phone)) {
        showError('phoneGroup', 'Please enter a valid phone number');
        isValid = false;
    }
    
    const dob = document.getElementById('dateOfBirth').value;
    if (!dob) {
        showError('dobGroup', 'Date of birth is required');
        isValid = false;
    } else {
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        if (age < 13) {
            showError('dobGroup', 'You must be at least 13 years old');
            isValid = false;
        }
    }
    
    const gender = document.getElementById('gender').value;
    if (!gender) {
        showError('genderGroup', 'Please select your gender');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Handle security form submission
 */
function handleSecuritySubmit(e) {
    e.preventDefault();
    
    if (validateSecurityForm()) {
        const submitBtn = document.getElementById('securitySaveBtn');
        const spinner = document.getElementById('securitySpinner');
        
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        
        setTimeout(() => {
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            
            showToast('Password updated successfully!', 'success');
            
            // Reset form
            e.target.reset();
            const passwordStrength = document.getElementById('passwordStrength');
            if (passwordStrength) passwordStrength.className = 'password-strength';
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            if (passwordStrengthText) passwordStrengthText.textContent = '';
        }, 1500);
    }
}

/**
 * Validate security form
 */
function validateSecurityForm() {
    let isValid = true;
    
    resetErrors('securityForm');
    
    const currentPassword = document.getElementById('current_password').value;
    if (!currentPassword) {
        showError('currentPasswordGroup', 'Current password is required');
        isValid = false;
    }
    
    const newPassword = document.getElementById('new_password').value;
    if (!newPassword) {
        showError('newPasswordGroup', 'New password is required');
        isValid = false;
    } else if (newPassword.length < 8) {
        showError('newPasswordGroup', 'Password must be at least 8 characters');
        isValid = false;
    } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(newPassword)) {
        showError('newPasswordGroup', 'Password must contain uppercase, lowercase, and numbers');
        isValid = false;
    }
    
    const confirmPassword = document.getElementById('confirm_password').value;
    if (!confirmPassword) {
        showError('confirmPasswordGroup', 'Please confirm your password');
        isValid = false;
    } else if (newPassword !== confirmPassword) {
        showError('confirmPasswordGroup', 'Passwords do not match');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Handle notification form submission
 */
function handleNotificationSubmit(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('notificationSaveBtn');
    const spinner = document.getElementById('notificationSpinner');
    
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    
    const notificationSettings = {};
    const checkboxes = document.querySelectorAll('#notificationForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        notificationSettings[checkbox.name] = checkbox.checked;
    });
    
    setTimeout(() => {
        localStorage.setItem('notificationSettings', JSON.stringify(notificationSettings));
        
        submitBtn.disabled = false;
        spinner.style.display = 'none';
        
        showToast('Notification preferences saved!', 'success');
    }, 1000);
}

/**
 * Show delete account confirmation modal
 */
function showDeleteModal() {
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    if (validateDeleteForm()) {
        deleteAccountModal.style.display = 'flex';
    }
}

/**
 * Hide delete account confirmation modal
 */
function hideDeleteModal() {
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    deleteAccountModal.style.display = 'none';
}

/**
 * Handle account deletion
 */
function handleAccountDeletion() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    confirmDeleteBtn.innerHTML = '<span class="spinner"></span> Deleting account...';
    confirmDeleteBtn.disabled = true;
    
    setTimeout(() => {
        // Clear all saved data
        localStorage.removeItem('profileSettings');
        localStorage.removeItem('notificationSettings');
        localStorage.removeItem('profilePhoto');
        localStorage.removeItem('accountStatus');
        localStorage.removeItem('deactivationInfo');
        
        showToast('Your account has been deleted successfully', 'success');
        
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
    }, 2000);
}

/**
 * Validate delete account form
 */
function validateDeleteForm() {
    let isValid = true;
    
    resetErrors('deleteAccountForm');
    
    const selectedReason = document.querySelector('input[name="delete_reason"]:checked');
    if (!selectedReason) {
        showError('feedbackGroup', 'Please select a reason for deleting your account');
        isValid = false;
    }
    
    if (selectedReason && selectedReason.value === 'other') {
        const feedback = document.getElementById('feedback').value.trim();
        if (!feedback) {
            showError('feedbackGroup', 'Please provide feedback');
            isValid = false;
        }
    }
    
    const confirmText = document.getElementById('confirmDelete').value;
    if (confirmText !== 'DELETE') {
        showError('confirmDeleteGroup', 'Please type DELETE to confirm');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Handle profile photo upload
 */
function handleProfilePhotoUpload(e) {
    const file = e.target.files[0];
    const photoPreview = document.getElementById('photoPreview');
    const removePhotoBtn = document.getElementById('removePhoto');
    const photoError = document.getElementById('photoError');
    
    if (!file) return;
    
    // Validate file type
    if (!file.type.match('image.*')) {
        if (photoError) {
            photoError.textContent = 'Please select an image file';
            photoError.style.display = 'block';
        }
        return;
    }
    
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        if (photoError) {
            photoError.textContent = 'File size must be less than 5MB';
            photoError.style.display = 'block';
        }
        return;
    }
    
    // Clear any previous errors
    if (photoError) {
        photoError.style.display = 'none';
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        photoPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
        removePhotoBtn.style.display = 'block';
        
        // Save to localStorage
        localStorage.setItem('profilePhoto', e.target.result);
        
        showToast('Profile photo updated successfully!', 'success');
    };
    reader.readAsDataURL(file);
}

/**
 * Remove profile photo
 */
function removeProfilePhoto() {
    const photoPreview = document.getElementById('photoPreview');
    const removePhotoBtn = document.getElementById('removePhoto');
    
    photoPreview.innerHTML = `
        <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
    `;
    removePhotoBtn.style.display = 'none';
    
    localStorage.removeItem('profilePhoto');
    
    showToast('Profile photo removed', 'success');
}

/**
 * Check password strength
 */
function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    if (!password) {
        if (strengthBar) strengthBar.className = 'password-strength';
        if (strengthText) strengthText.textContent = '';
        return;
    }
    
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength += 1;
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength += 1;
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength += 1;
    
    // Number check
    if (/\d/.test(password)) strength += 1;
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    // Update UI based on strength
    if (strengthBar) {
        if (strength <= 2) {
            strengthBar.className = 'password-strength strength-weak';
            if (strengthText) {
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#ff4d4f';
            }
        } else if (strength <= 4) {
            strengthBar.className = 'password-strength strength-medium';
            if (strengthText) {
                strengthText.textContent = 'Medium strength';
                strengthText.style.color = '#faad14';
            }
        } else {
            strengthBar.className = 'password-strength strength-strong';
            if (strengthText) {
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#52c41a';
            }
        }
    }
}

/**
 * Toggle feedback field based on selected reason
 */
function toggleFeedbackField() {
    const selectedReason = document.querySelector('input[name="delete_reason"]:checked');
    const feedbackGroup = document.getElementById('feedbackGroup');
    
    if (selectedReason && selectedReason.value === 'other') {
        feedbackGroup.style.display = 'block';
    } else {
        feedbackGroup.style.display = 'none';
    }
}

/**
 * Load saved settings from localStorage
 */
function loadSavedSettings() {
    // Load profile settings
    const savedProfile = JSON.parse(localStorage.getItem('profileSettings')) || {};
    if (savedProfile.firstName) document.getElementById('firstName').value = savedProfile.firstName;
    if (savedProfile.lastName) document.getElementById('lastName').value = savedProfile.lastName;
    if (savedProfile.email) document.getElementById('email').value = savedProfile.email;
    if (savedProfile.phone) document.getElementById('phone').value = savedProfile.phone;
    if (savedProfile.dateOfBirth) document.getElementById('dateOfBirth').value = savedProfile.dateOfBirth;
    if (savedProfile.gender) document.getElementById('gender').value = savedProfile.gender;
    
    // Load notification settings
    const savedNotifications = JSON.parse(localStorage.getItem('notificationSettings')) || {};
    Object.keys(savedNotifications).forEach(key => {
        const checkbox = document.querySelector(`[name="${key}"]`);
        if (checkbox) checkbox.checked = savedNotifications[key];
    });
    
    // Load profile photo
    const savedPhoto = localStorage.getItem('profilePhoto');
    if (savedPhoto) {
        const photoPreview = document.getElementById('photoPreview');
        const removePhotoBtn = document.getElementById('removePhoto');
        
        if (photoPreview) {
            photoPreview.innerHTML = `<img src="${savedPhoto}" alt="Profile Photo">`;
        }
        if (removePhotoBtn) {
            removePhotoBtn.style.display = 'block';
        }
    }
}

/**
 * Modal click outside to close
 */
function initModalClose() {
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    
    window.addEventListener('click', (e) => {
        if (e.target === deleteAccountModal) {
            hideDeleteModal();
        }
    });
}

/**
 * Show error message
 */
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const errorElement = element.querySelector('.error-message') || document.getElementById(elementId + 'Error');
    
    element.classList.add('error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

/**
 * Reset all errors in a form
 */
function resetErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    const errorElements = form.querySelectorAll('.error-message');
    const errorGroups = form.querySelectorAll('.form-group.error');
    
    errorElements.forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    
    errorGroups.forEach(group => {
        group.classList.remove('error');
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.innerHTML = '';
    toast.className = 'toast';
    
    if (type === 'error') {
        toast.classList.add('error');
        toast.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    } else {
        toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    }
    
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

/**
 * Utility: Validate email
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Utility: Validate phone number
 */
function isValidPhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/[\s\-\(\)]/g, ''));
}

/**
 * Utility: Format date for input
 */
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}