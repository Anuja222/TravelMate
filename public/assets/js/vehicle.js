document.addEventListener('DOMContentLoaded', function () {
    // Helper function to get base URL
    function getBaseUrl() {
        const path = window.location.pathname;
        const parts = path.split('/');
        const publicIndex = parts.indexOf('public');
        if (publicIndex !== -1) {
            return parts.slice(0, publicIndex + 1).join('/');
        }
        return '/TravelMate/public';
    }

    const baseUrl = getBaseUrl();

    // ========== VEHICLE TYPE SELECTION PAGE ==========
    const continueBtn = document.getElementById('continue-btn');
    if (continueBtn) {
        const vehicleCards = document.querySelectorAll('.vehicle-card');
        const ownershipRadios = document.querySelectorAll('input[name="ownership"]');
        const vehicleError = document.getElementById('vehicle-error');
        const ownershipError = document.getElementById('ownership-error');
        let selectedVehicle = sessionStorage.getItem('vehicleType') || null;

        // Handle vehicle card clicks
        vehicleCards.forEach(card => {
            card.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-list-vehicle')) return;
                
                vehicleCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                selectedVehicle = card.dataset.vehicleType;
                sessionStorage.setItem('vehicleType', selectedVehicle);
                
                if (vehicleError) vehicleError.style.display = 'none';
                checkFormCompletion();
            });

            const btn = card.querySelector('.btn-list-vehicle');
            if (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    card.click();
                });
            }
        });

        // Handle ownership selection
        ownershipRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (ownershipError) ownershipError.style.display = 'none';
                checkFormCompletion();
            });
        });

        function checkFormCompletion() {
            const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
            continueBtn.disabled = !(selectedVehicle && ownershipSelected);
        }

        // Continue button click
        continueBtn.addEventListener('click', function () {
            const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
            
            if (!selectedVehicle) {
                if (vehicleError) vehicleError.style.display = 'block';
                return;
            }
            
            if (!ownershipSelected) {
                if (ownershipError) ownershipError.style.display = 'block';
                return;
            }

            sessionStorage.setItem('ownership', ownershipSelected.value);
            window.location.href = 'personalDetails';
        });

        // Load saved selections
        if (selectedVehicle) {
            const card = document.querySelector(`.vehicle-card[data-vehicle-type="${selectedVehicle}"]`);
            if (card) card.classList.add('selected');
        }

        const savedOwnership = sessionStorage.getItem('ownership');
        if (savedOwnership) {
            const radio = document.querySelector(`input[name="ownership"][value="${savedOwnership}"]`);
            if (radio) radio.checked = true;
        }

        checkFormCompletion();
    }

    // ========== PERSONAL DETAILS PAGE ==========
    const personalForm = document.getElementById('personal-docs-form');
    if (personalForm) {
        personalForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Store form data
            const formData = new FormData(personalForm);
            sessionStorage.setItem('working_district', formData.get('working_district') || '');
            
            // Get AC type from radio buttons
            const acTypeRadio = document.querySelector('input[name="ac-type"]:checked');
            if (acTypeRadio) {
                sessionStorage.setItem('ac_type', acTypeRadio.value);
            }

            // Navigate to vehicle document page
            window.location.href = 'vehicleDocument';
        });
    }

    // ========== VEHICLE DOCUMENT PAGE (Final Submission) ==========
    const docsForm = document.getElementById('vehicle-docs-form');
    if (docsForm) {
        docsForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = docsForm.querySelector('.continue-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;

            // Build complete form data
            const fd = new FormData();
            
            // Add stored session data
            fd.append('vehicle_type', sessionStorage.getItem('vehicleType') || '');
            fd.append('working_district', sessionStorage.getItem('working_district') || '');
            fd.append('ac_type', sessionStorage.getItem('ac_type') || 'non-ac');

            // Add fields from current form
            const inputs = docsForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files && input.files.length > 0) {
                        if (input.multiple) {
                            Array.from(input.files).forEach(file => {
                                fd.append(input.name + '[]', file);
                            });
                        } else {
                            fd.append(input.name, input.files[0]);
                        }
                    }
                } else if (input.type === 'radio') {
                    if (input.checked && input.name) {
                        fd.append(input.name, input.value);
                    }
                } else if (input.name) {
                    fd.append(input.name, input.value || '');
                }
            });

            // Submit to API
            fetch(baseUrl + '/api/vehicle/create', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Clear session storage
                    sessionStorage.removeItem('vehicleType');
                    sessionStorage.removeItem('working_district');
                    sessionStorage.removeItem('ac_type');
                    sessionStorage.removeItem('ownership');

                    alert('Vehicle registered successfully!');
                    window.location.href = 'tr_dashboard';
                } else {
                    const errorMsg = data.errors && data.errors.error ? data.errors.error : 'Failed to save vehicle';
                    alert(errorMsg);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Network error. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // ========== DASHBOARD - LIST VEHICLES ==========
    const vehicleListContainer = document.querySelector('.my-vehicle-list');
    if (vehicleListContainer) {
        fetch(baseUrl + '/api/vehicle/list', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) {
                vehicleListContainer.innerHTML = '<p>Failed to load vehicles.</p>';
                return;
            }

            const vehicles = res.data || [];
            if (vehicles.length === 0) {
                vehicleListContainer.innerHTML = '<p>No vehicles listed yet.</p>';
                return;
            }

            vehicleListContainer.innerHTML = vehicles.map(v => {
                const type = escapeHtml(v.vehicle_type || 'Vehicle');
                const model = escapeHtml(v.vehicle_model || '');
                const number = escapeHtml(v.vehicle_number || '');
                const district = escapeHtml(v.working_district || '');
                
                return `
                    <div class="vehicle-item" data-id="${v.id}">
                        <h4>${model || type}</h4>
                        <p>${number} ${district ? '• ' + district : ''}</p>
                        <div class="actions">
                            <button class="btn-edit" data-id="${v.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-delete" data-id="${v.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            // Attach edit handlers
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    window.location.href = 'editVehicle?id=' + id;
                });
            });

            // Attach delete handlers
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    window.location.href = 'DeleteVehicle?id=' + id;
                });
            });
        })
        .catch(err => {
            console.error('Error loading vehicles:', err);
            vehicleListContainer.innerHTML = '<p>Error loading vehicles.</p>';
        });
    }

    // ========== EDIT VEHICLE PAGE ==========
    const vehicleEditForm = document.getElementById('vehicle-edit-form');
    if (vehicleEditForm) {
        const params = new URLSearchParams(window.location.search);
        const vehicleId = params.get('id');

        if (vehicleId) {
            // Fetch vehicle data
            fetch(baseUrl + '/api/vehicle/get?id=' + encodeURIComponent(vehicleId), {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.data) {
                    alert('Vehicle not found');
                    window.location.href = 'tr_dashboard';
                    return;
                }

                const v = res.data;

                // Populate form fields
                if (document.getElementById('working-district')) {
                    document.getElementById('working-district').value = v.working_district || '';
                }
                if (document.getElementById('passenger-count')) {
                    document.getElementById('passenger-count').value = v.passenger_count || 2;
                }
                
                // Set AC type radio buttons
                if (v.ac_type === 'ac' && document.getElementById('ac')) {
                    document.getElementById('ac').checked = true;
                    document.getElementById('ac-option').classList.add('selected');
                    document.getElementById('non-ac-option').classList.remove('selected');
                } else if (document.getElementById('non-ac')) {
                    document.getElementById('non-ac').checked = true;
                    document.getElementById('non-ac-option').classList.add('selected');
                    document.getElementById('ac-option').classList.remove('selected');
                }

                if (document.getElementById('vehicle-model')) {
                    document.getElementById('vehicle-model').value = v.vehicle_model || '';
                }
                if (document.getElementById('vehicle-year')) {
                    document.getElementById('vehicle-year').value = v.vehicle_year || '';
                }
                if (document.getElementById('vehicle-color')) {
                    document.getElementById('vehicle-color').value = v.vehicle_color || '';
                }
                if (document.getElementById('vehicle-number')) {
                    document.getElementById('vehicle-number').value = v.vehicle_number || '';
                }

                // Add hidden ID field
                if (!vehicleEditForm.querySelector('input[name="id"]')) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'id';
                    hidden.value = vehicleId;
                    vehicleEditForm.appendChild(hidden);
                }
            })
            .catch(err => {
                console.error('Error fetching vehicle:', err);
                alert('Error loading vehicle data');
            });
        }

        // Handle form submission
        vehicleEditForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = vehicleEditForm.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            submitBtn.disabled = true;

            const fd = new FormData(vehicleEditForm);

            fetch(baseUrl + '/api/vehicle/update', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Vehicle updated successfully!');
                    window.location.href = 'tr_dashboard';
                } else {
                    alert('Failed to update vehicle');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Network error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Cancel button
        const cancelBtn = vehicleEditForm.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                window.location.href = 'tr_dashboard';
            });
        }
    }

    // ========== DELETE VEHICLE PAGE ==========
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');

    if (confirmDeleteBtn) {
        const params = new URLSearchParams(window.location.search);
        const vehicleId = params.get('id');

        confirmDeleteBtn.addEventListener('click', function () {
            if (!vehicleId) {
                alert('No vehicle selected');
                return;
            }

            if (!confirm('Are you absolutely sure? This action cannot be undone.')) {
                return;
            }

            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> DELETING...';
            this.disabled = true;

            const fd = new FormData();
            fd.append('id', vehicleId);

            fetch(baseUrl + '/api/vehicle/delete', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Vehicle deleted successfully');
                    window.location.href = 'tr_dashboard';
                } else {
                    alert('Failed to delete vehicle');
                    this.innerHTML = 'YES, DELETE';
                    this.disabled = false;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Network error');
                this.innerHTML = 'YES, DELETE';
                this.disabled = false;
            });
        });
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function () {
            window.location.href = 'tr_dashboard';
        });
    }

    // Utility function
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
});