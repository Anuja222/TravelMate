// propertyDetails.js
// jS for Property Details page

document.addEventListener('DOMContentLoaded', function() {
    // counter logic for guests and bathrooms
    document.querySelectorAll('.counter').forEach(function(counter) {
        const decrement = counter.querySelector('.decrement');
        const increment = counter.querySelector('.increment');
        const countSpan = counter.querySelector('.count');
        let count = parseInt(countSpan.textContent);
        decrement.addEventListener('click', function() {
            if (count > 1) count--;
            countSpan.textContent = count;
        });
        increment.addEventListener('click', function() {
            count++;
            countSpan.textContent = count;
        });
    });
    // bedroom add/remove handled inline in the view to avoid duplicate behavior.
});
