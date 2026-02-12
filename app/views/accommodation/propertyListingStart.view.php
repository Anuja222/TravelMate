<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Property - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyListingStart.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <main class="listing-main">
        <h1>List your property on TravelMate and start welcoming guests in no time!</h1>
        <p class="subtitle">To get started, select the type of property you want to list on TravelMate</p>

        <form method="POST" action="/TravelMate/public/index.php?url=Accomodation_provider/propertyListingStep1">
            <div class="property-types">
                <div class="property-row">
                    <div class="property-type hotel-link" data-type="hotel" style="cursor:pointer;" onclick="selectAndSubmit(this, 'hotel')">
                        <div class="icon hotel-icon">
                            <img src="/TravelMate/public/assets/images/hotel.png" alt="Hotel" />
                        </div>
                        <h3>Hotel</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>

                    <div class="property-type guest-house-link" data-type="guest_house" style="cursor:pointer;" onclick="selectAndSubmit(this, 'guest_house')">
                        <div class="icon guest-house-icon">
                            <img src="/TravelMate/public/assets/images/guesthouse.png" alt="Guest House" />
                        </div>
                        <h3>Guest House</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>

                    <div class="property-type home-stay-link" data-type="home_stay" style="cursor:pointer;" onclick="selectAndSubmit(this, 'home_stay')">
                        <div class="icon home-stay-icon">
                            <img src="/TravelMate/public/assets/images/homestay.png" alt="Home Stay" />
                        </div>
                        <h3>Home Stay</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </div>
                <div class="property-row">
                    <div class="property-type villa-link" data-type="villa" style="cursor:pointer;" onclick="selectAndSubmit(this, 'villa')">
                        <div class="icon villa-icon">
                            <img src="/TravelMate/public/assets/images/villa.png" alt="Villa" />
                        </div>
                        <h3>Villa</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>

                    <div class="property-type apartment-link" data-type="apartment" style="cursor:pointer;" onclick="selectAndSubmit(this, 'apartment')">
                        <div class="icon apartment-icon">
                            <img src="/TravelMate/public/assets/images/apartment.png" alt="Apartment" />
                        </div>
                        <h3>Apartment</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>

                    <div class="property-type alternative-link" data-type="alternative" style="cursor:pointer;" onclick="selectAndSubmit(this, 'alternative')">
                        <div class="icon alternative-icon">
                            <img src="/TravelMate/public/assets/images/alterplaces.png" alt="Alternative Places" />
                        </div>
                        <h3>Alternative Places</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="property_type" id="propertyTypeInput" value="">
        </form>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>
        // Submit form with selected property type
        function selectAndSubmit(element, type) {
            document.getElementById('propertyTypeInput').value = type;
            element.closest('form').submit();
        }
    </script>
</body>
</html>
