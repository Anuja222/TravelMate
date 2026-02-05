document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.querySelector('.next-btn');
    if (!submitBtn) return;

    submitBtn.addEventListener('click', function() {
        const userId = localStorage.getItem('userId');
        if (!userId) {
            alert('User ID not found. Please register again.');
            window.location.href = 'signup';
            return;
        }

        // Get selected environments
        const selectedEnvironments = Array.from(document.querySelectorAll('.category-card.selected'))
            .map(card => card.dataset.category);

        // Get selected activities
        const selectedActivities = Array.from(document.querySelectorAll('.activity-card.selected'))
            .map(card => card.dataset.activity);

        if (selectedEnvironments.length === 0 || selectedActivities.length === 0) {
            alert('Please select at least one environment and one activity');
            return;
        }

        const formData = new FormData();
        formData.append('userId', userId);
        formData.append('environments', JSON.stringify(selectedEnvironments));
        formData.append('activities', JSON.stringify(selectedActivities));

        fetch('../public/preference/save', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                localStorage.removeItem('userId');
                showPreferenceSuccessModal();
            } else {
                const errorMessage = data.errors?.error || 'Failed to save preferences';
                alert(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error occurred while saving preferences. Please try again.');
        });
    });
});

function showPreferenceSuccessModal() {
    const modal = document.getElementById('preferenceSuccessModal');
    modal.classList.add('show');
    
    let countdown = 3;
    const countdownElement = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(timer);
            redirectToLogin();
        }
    }, 1000);
}

function redirectToLogin() {
    window.location.href = 'login';
}