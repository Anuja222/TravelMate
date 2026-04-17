<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - List Your Vehicle</title>
  <link rel="stylesheet" href="assets/css/Transpoter/vehicleType.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- MAIN CONTENT -->
  <main>

    <div class="dashboard-content">
      <section class="vehicle-listing">
        <h2>List your vehicle on TravelMate and take bookings for your vehicle</h2>
        <p>To get started, select the type of vehicle you have:</p>

        <div class="vehicle-cards">
          <!-- Vehicle Card 1: Car -->
          <div class="vehicle-card" data-vehicle-type="car">
            <img src="assets/trimages/caricon.webp" alt="Car">
            <h3>Car</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
          <!-- Vehicle Card 2: Bus -->
          <div class="vehicle-card" data-vehicle-type="bus">
            <img src="assets/trimages/busicon.jpg" alt="Bus">
            <h3>Bus</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
          <!-- Vehicle Card 3: Jeep -->
          <div class="vehicle-card" data-vehicle-type="jeep">
            <img src="assets/trimages/jeepicon.webp" alt="Jeep">
            <h3>Jeep</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
          <!-- Vehicle Card 4: Van -->
          <div class="vehicle-card" data-vehicle-type="van">
            <img src="assets/trimages/vanicon.jpg" alt="Van">
            <h3>Van</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
          <!-- Vehicle Card 5: Tuk -->
          <div class="vehicle-card" data-vehicle-type="tuk">
            <img src="assets/trimages/tukicon.jpg" alt="Tuk">
            <h3>Tuk</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
        </div>

        
    <div class="ownership-section">
        <h2 class="section-title">Vehicle ownership</h2>
        <div class="radio-group">
          <div class="radio-option">
            <input type="radio" id="owner" name="ownership" value="owner">
            <label for="owner">I am the owner of this vehicle. I am about to register</label>
          </div>
          <div class="radio-option">
            <input type="radio" id="not-owner" name="ownership" value="not-owner">
            <label for="not-owner">I am not the owner of this vehicle</label>
          </div>
        </div>
        
        <div class="error-message" id="ownership-error">
          Please select an ownership option to continue
        </div>
        
        <div class="error-message" id="vehicle-error">
          Please select a vehicle type to continue
        </div>
        
        <button class="continue-btn" id="continue-btn" disabled>
          Continue
        </button>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Vehicle selection functionality
      const vehicleCards = document.querySelectorAll('.vehicle-card');
      const continueBtn = document.getElementById('continue-btn');
      const ownershipRadios = document.querySelectorAll('input[name="ownership"]');
      const vehicleError = document.getElementById('vehicle-error');
      const ownershipError = document.getElementById('ownership-error');
      
      let selectedVehicle = null;
      
      // Handle vehicle selection
      vehicleCards.forEach(card => {
        card.addEventListener('click', function(e) {
          // Don't trigger if the click was on the button itself
          if (e.target.classList.contains('btn-list-vehicle')) return;
          
          // Remove selected class from all cards
          vehicleCards.forEach(c => c.classList.remove('selected'));
          
          // Add selected class to clicked card
          card.classList.add('selected');
          
          // Store selected vehicle
          selectedVehicle = card.dataset.vehicleType;
          
          // Hide vehicle error if shown
          vehicleError.style.display = 'none';
          
          // Enable continue button if ownership is also selected
          checkFormCompletion();
        });
        
        // Also make the button select the vehicle
        const button = card.querySelector('.btn-list-vehicle');
        button.addEventListener('click', function() {
          vehicleCards.forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          selectedVehicle = card.dataset.vehicleType;
          vehicleError.style.display = 'none';
          checkFormCompletion();
        });
      });
      
      // Handle ownership selection
      ownershipRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          // Hide ownership error if shown
          ownershipError.style.display = 'none';
          
          // Enable continue button if vehicle is also selected
          checkFormCompletion();
        });
      });
      
      // Check if form is complete
      function checkFormCompletion() {
        const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
        
        if (selectedVehicle && ownershipSelected) {
          continueBtn.disabled = false;
        } else {
          continueBtn.disabled = true;
        }
      }
      
      // Handle continue button click
      continueBtn.addEventListener('click', function() {
        const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
        let isValid = true;
        
        // Validate vehicle selection
        if (!selectedVehicle) {
          vehicleError.style.display = 'block';
          isValid = false;
        } else {
          vehicleError.style.display = 'none';
        }
        
        // Validate ownership selection
        if (!ownershipSelected) {
          ownershipError.style.display = 'block';
          isValid = false;
        } else {
          ownershipError.style.display = 'none';
        }
        
        // If valid, proceed to next page
        if (isValid) {
          // Store selections in sessionStorage or form data
          sessionStorage.setItem('vehicleType', selectedVehicle);
          sessionStorage.setItem('ownership', ownershipSelected.value);
          
          // Redirect to next page
          window.location.href = 'personalDetails';
        }
      });
      
      // Load previously selected values if any
      const savedVehicle = sessionStorage.getItem('vehicleType');
      const savedOwnership = sessionStorage.getItem('ownership');
      
      if (savedVehicle) {
        const vehicleCard = document.querySelector(`.vehicle-card[data-vehicle-type="${savedVehicle}"]`);
        if (vehicleCard) {
          vehicleCard.classList.add('selected');
          selectedVehicle = savedVehicle;
        }
      }
      
      if (savedOwnership) {
        const ownershipRadio = document.querySelector(`input[name="ownership"][value="${savedOwnership}"]`);
        if (ownershipRadio) {
          ownershipRadio.checked = true;
        }
      }
      
      // Check initial form state
      checkFormCompletion();
    });
  </script>
</body>
</html>