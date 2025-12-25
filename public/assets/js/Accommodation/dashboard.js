// JS for dashboard page
// Add event listeners for Edit/Delete buttons, List Property, etc.
document.querySelectorAll('.listing-actions button').forEach(btn => {
    btn.addEventListener('click', function() {
        alert('This is a demo action.');
    });
});
document.querySelector('.list-property-btn')?.addEventListener('click', function() {
    alert('List your property clicked!');
});
