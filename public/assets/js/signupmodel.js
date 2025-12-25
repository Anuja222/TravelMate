document.addEventListener('DOMContentLoaded', function () {

    const signupForm = document.getElementById('signupForm');
    if (!signupForm) return;

    // Get stored role from localStorage
    const userRole = localStorage.getItem('selectedUserRole');

    signupForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(signupForm);
        // Add the role to formData
        formData.append('role', userRole);

        fetch('../public/auth.php?action=register', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Store user ID in localStorage
                    localStorage.setItem('userId', data.user.userId);
                    alert('Account created successfully!');

                    if (userRole === 'traveller') {
                        window.location.href = 'preference';
                    } else if (userRole === 'transport') {
                        window.location.href = 'login';
                    } else if (userRole === 'admin') {
                        window.location.href = 'login';
                    } else if (userRole === 'accommodation') {
                        window.location.href = 'login';
                    }

                    localStorage.removeItem('selectedUserRole');

                } else {
                    let msg = '';
                    for (const key in data.errors) {
                        msg += data.errors[key] + '\n';
                    }
                    alert(msg);
                }
            })
            .catch(() => alert('Error occurred. Please try again.'));
    });
});