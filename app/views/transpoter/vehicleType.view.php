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

  <main>

    <div class="dashboard-content">
      <section class="vehicle-listing">
        <h2>List your vehicle on TravelMate and get your vehicle booked for a trip!</h2>
        <p>To get started, select the type of vehicle you have:</p>

        <div class="vehicle-cards">
          
          <div class="vehicle-card" data-vehicle-type="car">
            <img src="assets/trimages/caricon.webp" alt="Car">
            <h3>Car</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
         
          <div class="vehicle-card" data-vehicle-type="bus">
            <img src="assets/trimages/busicon.jpg" alt="Bus">
            <h3>Bus</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
         
          <div class="vehicle-card" data-vehicle-type="jeep">
            <img src="assets/trimages/jeepicon.webp" alt="Jeep">
            <h3>Jeep</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
         
          <div class="vehicle-card" data-vehicle-type="van">
            <img src="assets/trimages/vanicon.jpg" alt="Van">
            <h3>Van</h3>
            <button class="btn-list-vehicle">Select</button>
          </div>
       
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
    
      const vehicleCards = document.querySelectorAll('.vehicle-card');
      const continueBtn = document.getElementById('continue-btn');
      const ownershipRadios = document.querySelectorAll('input[name="ownership"]');
      const vehicleError = document.getElementById('vehicle-error');
      const ownershipError = document.getElementById('ownership-error');
      
      let selectedVehicle = null;
      
      // handle vehicle selection
      vehicleCards.forEach(card => {
        card.addEventListener('click', function(e) {
         
          if (e.target.classList.contains('btn-list-vehicle')) return;
          
          
          vehicleCards.forEach(c => c.classList.remove('selected'));
          
          
          card.classList.add('selected');
          
          // store selected vehicle
          selectedVehicle = card.dataset.vehicleType;
          
          
          vehicleError.style.display = 'none';
          
          checkFormCompletion();
        });
        
       
        const button = card.querySelector('.btn-list-vehicle');
        button.addEventListener('click', function() {
          vehicleCards.forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          selectedVehicle = card.dataset.vehicleType;
          vehicleError.style.display = 'none';
          checkFormCompletion();
        });
      });
      
     
      ownershipRadios.forEach(radio => {
        radio.addEventListener('change', function() {
        
          ownershipError.style.display = 'none';
          
          
          checkFormCompletion();
        });
      });
      
      // check if form is complete
      function checkFormCompletion() {
        const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
        
        if (selectedVehicle && ownershipSelected) {
          continueBtn.disabled = false;
        } else {
          continueBtn.disabled = true;
        }
      }
      
      // handle continue button click
      continueBtn.addEventListener('click', function() {
        const ownershipSelected = document.querySelector('input[name="ownership"]:checked');
        let isValid = true;
        
        if (!selectedVehicle) {
          vehicleError.style.display = 'block';
          isValid = false;
        } else {
          vehicleError.style.display = 'none';
        }
        
      
        if (!ownershipSelected) {
          ownershipError.style.display = 'block';
          isValid = false;
        } else {
          ownershipError.style.display = 'none';
        }
        
       
        if (isValid) {
          /
          sessionStorage.setItem('vehicleType', selectedVehicle);
          sessionStorage.setItem('ownership', ownershipSelected.value);
          
          
          window.location.href = 'personalDetails';
        }
      });
      
      // load previously selected values 
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
      
    
      checkFormCompletion();
    });
  </script>
</body>
</html>