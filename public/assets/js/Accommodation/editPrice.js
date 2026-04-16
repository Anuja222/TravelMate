// jS for edit price page
document.querySelector('.price-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    // collect price inputs
    const inputs = document.querySelectorAll('.price-form input[type="text"]');
    const pricePerNight = inputs[0]?.value?.trim() || '';
    const pricePerGuest = inputs[1]?.value?.trim() || '';

    const obj = { pricePerNight, pricePerGuest };
    try { sessionStorage.setItem('tm_price', JSON.stringify(obj)); } catch (err) { console.warn('Could not save price preview', err); }

    const pretty = '/TravelMate/Accomodation_provider/viewProperty';
    const fallback = '/TravelMate/public/index.php?url=Accomodation_provider/viewProperty';
    fetch(pretty, { method: 'HEAD' }).then(resp => {
        if (resp && resp.ok) window.location.assign(pretty);
        else window.location.assign(fallback);
    }).catch(() => window.location.assign(fallback));
});
