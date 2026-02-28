document.addEventListener('DOMContentLoaded', function() {
    // Helper function to get base URL
    function getBaseUrl() {
        const path = window.location.pathname;
        const parts = path.split('/');
        const publicIndex = parts.indexOf('public');
        if (publicIndex !== -1) {
            return parts.slice(0, publicIndex + 1).join('/');
        }
        return '/TravelMate/public';
    }

    const baseUrl = getBaseUrl();
    // expose helper globally for inline handlers and other scripts
    window.getBaseUrl = getBaseUrl;
    console.log('accommodation.js loaded, baseUrl=', baseUrl);

    // Helper function to capitalize first letter
    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
});

// Global function for property deletion

async function deleteProperty(id) {
    if (!confirm('Are you sure you want to delete this property?')) {
        return;
    }
    
    try {
        const response = await fetch(`${getBaseUrl()}/api/accommodation/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        });

        console.log('POST delete', `${getBaseUrl()}/api/accommodation/delete`, response.status, response.statusText);
        const ctDel = response.headers.get('content-type') || '';
        if (ctDel.includes('application/json')) {
            try {
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Failed to delete property: ' + (result.errors ? result.errors.join(', ') : 'Unknown error'));
                }
            } catch (err) {
                const text = await response.text();
                console.error('Invalid JSON from server (delete):', text, err);
                alert('Failed to delete property. See console for details.');
            }
        } else {
            const text = await response.text();
            console.error('Non-JSON response from server (delete):', response.status, text);
            alert('Failed to delete property. See console for details.');
        }
    } catch (error) {
        console.error('Error deleting property:', error);
        alert('Failed to delete property. Please try again.');
    }
}