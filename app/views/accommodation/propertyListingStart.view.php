<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Property - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyListingStart.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <script src="/TravelMate/public/assets/js/propertyListing.js" defer></script>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <main class="listing-main">
        <h1>List your property on TravelMate and start welcoming guests in no time!</h1>
        <p class="subtitle">To get started, select the type of property you want to list on TravelMate</p>

        <div class="property-types">
            <div class="property-row">
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type hotel-link" data-type="Hotel" style="cursor:pointer;">
                        <div class="icon hotel-icon">
                            <img src="/TravelMate/public/assets/images/hotel.png" alt="Hotel" />
                        </div>
                        <h3>Hotel</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type guest-house-link" data-type="Guest House" style="cursor:pointer;">
                        <div class="icon guest-house-icon">
                            <img src="/TravelMate/public/assets/images/guesthouse.png" alt="Guest House" />
                        </div>
                        <h3>Guest House</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type home-stay-link" data-type="Home Stay" style="cursor:pointer;">
                        <div class="icon home-stay-icon">
                            <img src="/TravelMate/public/assets/images/homestay.png" alt="Home Stay" />
                        </div>
                        <h3>Home Stay</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
            </div>
            <div class="property-row">
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type villa-link" data-type="Villa" style="cursor:pointer;">
                        <div class="icon villa-icon">
                            <img src="/TravelMate/public/assets/images/villa.png" alt="Villa" />
                        </div>
                        <h3>Villa</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type apartment-link" data-type="Apartment" style="cursor:pointer;">
                        <div class="icon apartment-icon">
                            <img src="/TravelMate/public/assets/images/apartment.png" alt="Apartment" />
                        </div>
                        <h3>Apartment</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
                <!-- <a href="/TravelMate/Accomodation_provider/accommodationFeatures" style="text-decoration:none;color:inherit;"> -->
                    <div class="property-type alternative-link" data-type="Alternative" style="cursor:pointer;">
                        <div class="icon alternative-icon">
                            <img src="/TravelMate/public/assets/images/alterplaces.png" alt="Alternative Places" />
                        </div>
                        <h3>Alternative Places</h3>
                        <button class="list-property-btn" type="button">Select</button>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script></script>
</body>
</html>
