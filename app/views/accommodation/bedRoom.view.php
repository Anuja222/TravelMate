<!-- Bed Room Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Room</title>
    <link rel="stylesheet" href="assets/css/Accommodation/bedRoom.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <?php
    $bedNumber = 1;
    if (isset($_GET['bed']) && is_numeric($_GET['bed'])) {
        $bedNumber = (int) $_GET['bed'];
        if ($bedNumber < 1) $bedNumber = 1;
    }
    ?>
    <h1>Bed Room <?php echo $bedNumber; ?></h1>
    <form class="bedroom-form">
        <label>Which beds are available in this room?</label>
        <div class="bed-type-row">
            <div class="bed">
                <select>
                    <option>King bed</option>
                    <option>Queen bed</option>
                    <option>Double bed</option>
                    <option>Twin bed</option>
                    <option>Full bed</option>
                    <option>Single bed</option>
                </select>
            </div>
            <div class="counter">
                <button type="button" class="decrement">-</button>
                <span class="count">2</span>
                <button type="button" class="increment">+</button>
            </div>
        </div>
        <button type="button" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accommodation/bedRoom.js"></script>
    <script>
    // Redirect back to propertyDetails with selected bed info
    (function(){
        const bedNumber = <?php echo (int) $bedNumber; ?>;
        document.querySelector('.save-btn').addEventListener('click', function(e){
            e.preventDefault();
            const select = document.querySelector('select');
            const type = select ? select.value : '';
            const countSpan = document.querySelector('.count');
            const count = countSpan ? parseInt(countSpan.textContent) : 0;
            const params = new URLSearchParams();
            params.set('bed', bedNumber);
            params.set('type', type);
            params.set('count', count);
            // Try multiple redirect fallbacks to work across different setups.
            const relPath = 'public/index.php?url=Accommodation/propertyDetails&' + params.toString();
            const absPath = '/TravelMate/public/index.php?url=Accommodation/propertyDetails&' + params.toString();
            console.log('Attempting redirect (relative):', relPath);
            // First try relative path (works when site is served from /TravelMate/)
            try {
                window.location.href = encodeURI(relPath);
                // Give browser a tick to start navigation; if it doesn't navigate (rare), fallback below
                setTimeout(function(){
                    // If still on page after 500ms, try absolute path and show manual link
                    if (location.href.indexOf('editBedrooms') === -1) {
                        console.log('Relative redirect may have failed; trying absolute path', absPath);
                        window.location.href = encodeURI(absPath);
                        // Insert visible manual link as last resort
                        insertManualFallback(absPath);
                    }
                }, 500);
            } catch (e) {
                console.warn('Relative redirect failed, trying absolute', e);
                window.location.href = encodeURI(absPath);
                insertManualFallback(absPath);
            }

            function insertManualFallback(href) {
                const note = document.createElement('div');
                note.style.marginTop = '1rem';
                note.style.padding = '0.75rem';
                note.style.background = '#fff3cd';
                note.style.border = '1px solid #ffeeba';
                note.innerHTML = 'If you are not redirected automatically, <a href="' + href + '">click here to continue</a>.';
                document.body.appendChild(note);
            }
        });
    })();
    </script>
</body>
</html>
