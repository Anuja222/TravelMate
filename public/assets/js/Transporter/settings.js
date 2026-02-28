/**
 * setting.js - Settings page functionality for Transporter
 * Path: TravelMate/assets/js/Transpoter/setting.js
 */

// DOM Elements
document.addEventListener('DOMContentLoaded', function() {
    initApp();
});

/**
 * Main initialization function
 */
function initApp() {
    // DOM Elements
    const profileForm = document.getElementById('profileForm');
    const securityForm = document.getElementById('securityForm');
    const notificationForm = document.getElementById('notificationForm');
    const deleteAccountForm = document.getElementById('deleteAccountForm');
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const profilePhoto = document.getElementById('profilePhoto');
    const removePhotoBtn = document.getElementById('removePhoto');
    const newPasswordInput = document.getElementById('new_password');
    const reasonRadios = document.querySelectorAll('input[name="delete_reason"]');
    const feedbackGroup = document.getElementById('feedbackGroup');

    // Account Status Elements
    const accountStatusToggle = document.getElementById('accountStatusToggle');
    const deactivationReason = document.getElementById('deactivationReason');
    const otherReasonGroup = document.getElementById('otherReasonGroup');
    const confirmDeactivationCheckbox = document.getElementById('confirmDeactivationCheckbox');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    const cancelDeactivationBtn = document.getElementById('cancelDeactivationBtn');
    const confirmReactivationBtn = document.getElementById('confirmReactivationBtn');
    const cancelReactivationBtn = document.getElementById('cancelReactivationBtn');

    // Initialize all functionality
    initTabs();
    initEventListeners();
    loadSavedSettings();
    loadAccountStatus();
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
            document.getElementById('passwordStrength').className = 'password-strength';
            document.getElementById('passwordStrengthText').textContent = '';
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
        strengthBar.className = 'password-strength';
        strengthText.textContent = '';
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
    if (strength <= 2) {
        strengthBar.className = 'password-strength strength-weak';
        strengthText.textContent = 'Weak password';
        strengthText.style.color = '#ff4d4f';
    } else if (strength <= 4) {
        strengthBar.className = 'password-strength strength-medium';
        strengthText.textContent = 'Medium strength';
        strengthText.style.color = '#faad14';
    } else {
        strengthBar.className = 'password-strength strength-strong';
        strengthText.textContent = 'Strong password';
        strengthText.style.color = '#52c41a';
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
 * Load account status from localStorage
 */
function loadAccountStatus() {
    const accountStatusToggle = document.getElementById('accountStatusToggle');
    const currentStatusBadge = document.getElementById('currentStatusBadge');
    const vehicleVisibilityBadge = document.getElementById('vehicleVisibilityBadge');
    const statusText = document.getElementById('statusText');
    
    if (!accountStatusToggle) return;
    
    const accountStatus = localStorage.getItem('accountStatus') || 'active';
    const isActive = accountStatus === 'active';
    
    accountStatusToggle.checked = isActive;
    updateStatusUI(isActive);
}

/**
 * Update status UI
 */
function updateStatusUI(isActive) {
    const currentStatusBadge = document.getElementById('currentStatusBadge');
    const vehicleVisibilityBadge = document.getElementById('vehicleVisibilityBadge');
    const statusText = document.getElementById('statusText');
    const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
    const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
    
    if (!currentStatusBadge || !vehicleVisibilityBadge || !statusText) return;
    
    if (isActive) {
        currentStatusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Active';
        currentStatusBadge.style.background = 'rgba(255,255,255,0.2)';
        vehicleVisibilityBadge.innerHTML = '<i class="fas fa-eye"></i> Vehicles visible to travellers';
        statusText.textContent = 'Active';
        
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'none';
    } else {
        currentStatusBadge.innerHTML = '<i class="fas fa-pause-circle"></i> Deactivated';
        currentStatusBadge.style.background = 'rgba(231, 76, 60, 0.2)';
        vehicleVisibilityBadge.innerHTML = '<i class="fas fa-eye-slash"></i> Vehicles hidden from travellers';
        statusText.textContent = 'Deactivated';
        
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'block';
    }
}

/**
 * Handle account status toggle
 */
function handleAccountStatusToggle() {
    const isActive = document.getElementById('accountStatusToggle').checked;
    const deactivationConfirmBox = document.getElementById('deactivationConfirmBox');
    const reactivationConfirmBox = document.getElementById('reactivationConfirmBox');
    
    if (!isActive) {
        // Show deactivation confirmation
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'block';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'none';
    } else {
        // Show reactivation confirmation
        if (deactivationConfirmBox) deactivationConfirmBox.style.display = 'none';
        if (reactivationConfirmBox) reactivationConfirmBox.style.display = 'block';
    }
}

/**
 * Toggle other reason field
 */
function toggleOtherReasonField() {
    const deactivationReason = document.getElementById('deactivationReason');
    const otherReasonGroup = document.getElementById('otherReasonGroup');
    
    if (deactivationReason.value === 'other') {
        otherReasonGroup.style.display = 'block';
    } else {
        otherReasonGroup.style.display = 'none';
    }
}

/**
 * Toggle confirm deactivation button
 */
function toggleConfirmDeactivationBtn() {
    const confirmDeactivationCheckbox = document.getElementById('confirmDeactivationCheckbox');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    
    confirmDeactivationBtn.disabled = !confirmDeactivationCheckbox.checked;
}

/**
 * Confirm deactivation
 */
function confirmDeactivation() {
    const deactivationReason = document.getElementById('deactivationReason');
    const otherReason = document.getElementById('otherReason');
    const confirmDeactivationBtn = document.getElementById('confirmDeactivationBtn');
    
    // Show loading state
    confirmDeactivationBtn.innerHTML = '<span class="spinner"></span> Deactivating...';
    confirmDeactivationBtn.disabled = true;
    
    setTimeout(() => {
        // Save deactivation info
        const deactivationInfo = {
            status: 'deactivated',
            reason: deactivationReason.value,
            otherReason: deactivationReason.value === 'other' ? otherReason.value : '',
            deactivatedAt: new Date().toISOString()
        };
        
        localStorage.setItem('accountStatus', 'deactivated');
        localStorage.setItem('deactivationInfo', JSON.stringify(deactivationInfo));
        
        // Update UI
        document.getElementById('accountStatusToggle').checked = false;
        updateStatusUI(false);
        
        // Hide confirmation box
        document.getElementById('deactivationConfirmBox').style.display = 'none';
        
        // Reset form
        deactivationReason.value = '';
        otherReason.value = '';
        document.getElementById('otherReasonGroup').style.display = 'none';
        document.getElementById('confirmDeactivationCheckbox').checked = false;
        confirmDeactivationBtn.disabled = true;
        
        // Show toast
        showToast('Account deactivated successfully. Your vehicles are now hidden from travellers.', 'success');
        
        // Reset button
        confirmDeactivationBtn.innerHTML = '<i class="fas fa-pause-circle"></i> Confirm Deactivation';
    }, 1500);
}

/**
 * Cancel deactivation
 */
function cancelDeactivation() {
    document.getElementById('accountStatusToggle').checked = true;
    document.getElementById('deactivationConfirmBox').style.display = 'none';
    
    // Reset form
    document.getElementById('deactivationReason').value = '';
    document.getElementById('otherReason').value = '';
    document.getElementById('otherReasonGroup').style.display = 'none';
    document.getElementById('confirmDeactivationCheckbox').checked = false;
    document.getElementById('confirmDeactivationBtn').disabled = true;
}

/**
 * Confirm reactivation
 */
function confirmReactivation() {
    const confirmReactivationBtn = document.getElementById('confirmReactivationBtn');
    
    // Show loading state
    confirmReactivationBtn.innerHTML = '<span class="spinner"></span> Reactivating...';
    confirmReactivationBtn.disabled = true;
    
    setTimeout(() => {
        // Save reactivation
        localStorage.setItem('accountStatus', 'active');
        localStorage.removeItem('deactivationInfo');
        
        // Update UI
        document.getElementById('accountStatusToggle').checked = true;
        updateStatusUI(true);
        
        // Hide confirmation box
        document.getElementById('reactivationConfirmBox').style.display = 'none';
        
        // Show toast
        showToast('Account reactivated successfully. Your vehicles are now visible to travellers.', 'success');
        
        // Reset button
        confirmReactivationBtn.innerHTML = '<i class="fas fa-play-circle"></i> Reactivate Account';
        confirmReactivationBtn.disabled = false;
    }, 1500);
}

/**
 * Cancel reactivation
 */
function cancelReactivation() {
    document.getElementById('accountStatusToggle').checked = false;
    document.getElementById('reactivationConfirmBox').style.display = 'none';
    updateStatusUI(false);
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