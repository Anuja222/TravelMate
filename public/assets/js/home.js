// Simple form handling for the footer subscription
document.addEventListener('DOMContentLoaded', function() {
  const subscribeForm = document.querySelector('.subscribe-form');
  if (subscribeForm) {
    subscribeForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const emailInput = subscribeForm.querySelector('input[type="email"]');
      if (emailInput.value) {
        alert('Thank you for subscribing, ' + emailInput.value + '!');
        emailInput.value = '';
      }
    });
  }
});