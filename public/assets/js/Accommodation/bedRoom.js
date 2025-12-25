// bedRoom.js
// JS for Bed Room page

document.addEventListener('DOMContentLoaded', function() {
    // Counter logic for bed types
    document.querySelectorAll('.bed-type-row').forEach(function(row) {
        const decrement = row.querySelector('.decrement');
        const increment = row.querySelector('.increment');
        const countSpan = row.querySelector('.count');
        let count = parseInt(countSpan.textContent);
        decrement.addEventListener('click', function() {
            if (count > 0) count--;
            countSpan.textContent = count;
        });
        increment.addEventListener('click', function() {
            count++;
            countSpan.textContent = count;
        });
    });
});
