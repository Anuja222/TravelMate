document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(loginForm);

        fetch('../public/auth.php?action=login', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Show success modal
                const modal = document.getElementById('successModal');
                modal.classList.add('show');
                
                // Redirect after 1.5 seconds
                setTimeout(() => {
                    if (data.user.role === 'transport') {
                        window.location.href = 'tr_dashboard';
                    } else if (data.user.role === 'traveller') {
                        window.location.href = 'homet';
                    } else if (data.user.role === 'admin') {
                        window.location.href = 'ad_dashboard';
                    } else if (data.user.role === 'accommodation') {
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