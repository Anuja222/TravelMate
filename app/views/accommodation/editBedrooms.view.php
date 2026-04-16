<!-- bed Room Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Room</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/bedRoom.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- header -->
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
        <button type="button" class="save-btn">Save & Continue</button>
    </form>
    <!-- footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/bedRoom.js"></script>
    <script>
    // redirect back to propertyDetails with selected bed info
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
            // redirect back to propertyDetails route using front controller fallback
            window.location.href = '/TravelMate/public/index.php?url=Accomodation_provider/propertyDetails&' + params.toString();
        });
    })();
    </script>
</body>
</html>
