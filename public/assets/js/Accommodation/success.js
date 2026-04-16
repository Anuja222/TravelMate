// success.js
// jS for Success/Thank You page

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.dashboard-btn').addEventListener('click', function() {
        window.location.href = '/dashboard';
    });
    document.querySelector('.add-property-btn').addEventListener('click', function() {
        window.location.href = '/add-property';
    });
});
