console.log('preference.js loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Preference page DOM loaded');
    
    const submitBtn = document.querySelector('.next-btn');
    if (!submitBtn) {
        console.error('Submit button not found');
        return;
    }
    
    console.log('Submit button found');

    submitBtn.addEventListener('click', function() {
        console.log('Submit button clicked');
        
        const userId = localStorage.getItem('userId');
        console.log('User ID from localStorage:', userId);
        
        if (!userId) {
            alert('User ID not found. Please register again.');
            window.location.href = 'signup';
            return;
        }

        // get selected environments
        const selectedEnvironments = Array.from(document.querySelectorAll('.category-card.selected'))
            .map(card => card.dataset.category);

        // get selected activities
        const selectedActivities = Array.from(document.querySelectorAll('.activity-card.selected'))
            .map(card => card.dataset.activity);

        console.log('Selected environments:', selectedEnvironments);
        console.log('Selected activities:', selectedActivities);

        if (selectedEnvironments.length === 0 || selectedActivities.length === 0) {
            alert('Please select at least one environment and one activity');
            return;
        }

        const formData = new FormData();
        formData.append('userId', userId);
        formData.append('environments', JSON.stringify(selectedEnvironments));
        formData.append('activities', JSON.stringify(selectedActivities));

        console.log('Sending preference data to: preference/save');

        fetch('preference/save', {
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
    
    console.log('Event listener attached to submit button');
});

function showPreferenceSuccessModal() {
    const modal = document.getElementById('preferenceSuccessModal');
    if (modal) {
        modal.classList.add('show');
        
        let countdown = 3;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(timer);
                redirectToLogin();
            }
        }, 1000);
    }
}

function redirectToLogin() {
    window.location.href = 'login';
}