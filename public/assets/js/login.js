document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    function getBasePath() {
        const path = window.location.pathname;
        const marker = '/public/';
        const index = path.indexOf(marker);
        return index !== -1 ? path.substring(0, index + marker.length - 1) : '';
    }

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(loginForm);
        const loginUrl = `${getBasePath()}/loginUser`;

        fetch(loginUrl, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const userRole = (data.user && data.user.role ? data.user.role : '').toLowerCase();

                // Show success modal
                const modal = document.getElementById('successModal');
                modal.classList.add('show');
                
                // Redirect after 1.5 seconds
                setTimeout(() => {
                    if (userRole === 'transport') {
                        window.location.href = 'tr_dashboard';
                    } else if (userRole === 'traveller') {
                        window.location.href = 'homet';
                    } else if (userRole === 'admin') {
                        window.location.href = 'ad_dashboard';
                    } else if (userRole === 'accommodation') {
                        window.location.href = 'ac_dashboard';
                    }
                }, 1500);
            }else {
                // Show error modal
                const errorModal = document.getElementById('errorModal');
                const errorMessage = document.getElementById('errorMessage');
                
                let msg = '';
                for (const key in data.errors) {
                    msg += data.errors[key] + ' ';
                }
                
                errorMessage.textContent = msg || 'Invalid email or password';
                errorModal.classList.add('show');
            }
        })
        .catch(() => alert('Error occurred. Please try again.'));
    });
});