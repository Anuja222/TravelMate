<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    header('Location: /TravelMate/public/index.php?url=Login');
    exit;
}

// Get saved data if returning from step 2
$savedData = $_SESSION['listing_step1'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Property - Step 1 - TravelMate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyListingStart.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .form-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 50px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
        }
        
        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
            transition: all 0.3s ease;
        }
        
        .step-number.inactive {
            background: #e8e8e8;
            color: #999;
            box-shadow: none;
        }
        
        .step-line {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #e8e8e8 0%, #e8e8e8 100%);
            margin: 0 20px;
            position: relative;
            overflow: hidden;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 15px;
            letter-spacing: 0.3px;
        }
        
        .form-group label .required {
            color: #e74c3c;
            font-weight: 700;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }
        
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #fafafa;
        }
        
        .form-control:hover {
            border-color: #d0d0d0;
            background: #fff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1abc5b;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(26, 188, 91, 0.1);
            transform: translateY(-1px);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .property-type-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 12px;
        }
        
        .property-type-option {
            border: 3px solid #e8e8e8;
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: #fafafa;
        }
        
        .property-type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .property-type-option input[type="radio"]:checked + .type-content {
            border-color: #1abc5b;
        }
        
        .property-type-option:hover {
            border-color: #1abc5b;
            background: #fff;
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 30px rgba(26, 188, 91, 0.2);
        }
        
        .property-type-option.selected {
            border-color: #1abc5b;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            box-shadow: 0 8px 25px rgba(26, 188, 91, 0.2);
            transform: translateY(-4px);
        }
        
        .property-type-option.selected::before {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 3px 10px rgba(26, 188, 91, 0.3);
        }
        
        .type-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 188, 91, 0.2);
        }
        
        .property-type-option:hover .type-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(26, 188, 91, 0.3);
        }
        
        .type-icon img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        
        .type-name {
            font-weight: 600;
            color: #2c3e50;
            margin-top: 10px;
            font-size: 15px;
            letter-spacing: 0.3px;
        }
        
        .property-type-option.selected .type-name {
            color: #16a085;
        }
        
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 16px;
        }
        
        .btn {
            padding: 16px 38px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            color: #fff;
            box-shadow: 0 6px 20px rgba(26, 188, 91, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
            box-shadow: 0 10px 30px rgba(26, 188, 91, 0.4);
            transform: translateY(-3px);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #2c3e50;
            border: 2px solid #e8e8e8;
        }
        
        .btn-secondary:hover {
            background: #e8e8e8;
            border-color: #d0d0d0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 4px;
            display: none;
        }
        
        .form-group.has-error .form-control {
            border-color: #e74c3c;
        }
        
        .form-group.has-error .error-message {
            display: block;
        }
        
        @media (max-width: 768px) {
            .property-type-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-container {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-bar">
      <div class="breadcrumb-inner">
        <a href="/TravelMate/public/index.php?url=Accomodation_provider/newerDashboard" class="breadcrumb-link">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right breadcrumb-sep"></i>
        <span class="breadcrumb-current">
          <i class="fas fa-plus-circle"></i> List Property — Step 1
        </span>
      </div>
    </nav>

    <main class="listing-main">
        <div class="form-container">
            <div class="step-indicator">
                <div class="step">
                    <div class="step-number">1</div>
                    <span>Basic Details</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-number inactive">2</div>
                    <span>Property Features</span>
                </div>
            </div>

            <h1 style="text-align: center; margin-bottom: 12px; font-size: 32px; font-weight: 700; color: #2c3e50; letter-spacing: -0.5px;">
                <i class="fas fa-home" style="color: #1abc5b; margin-right: 12px;"></i>List Your Property
            </h1>
            <p style="text-align: center; color: #666; margin-bottom: 40px; font-size: 16px; font-weight: 400;">Let's start with the basics. Tell us about your property.</p>

            <form method="POST" action="/TravelMate/public/index.php?url=Accomodation_provider/propertyListingStep2" id="step1Form">
                
                <!-- Property Type -->
                <div class="form-group">
                    <label>Property Type <span class="required">*</span></label>
                    <div class="property-type-grid">
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'hotel' ? 'selected' : '' ?>" onclick="selectType(this, 'hotel')">
                            <input type="radio" name="property_type" value="hotel" <?= ($savedData['property_type'] ?? '') === 'hotel' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/hotel.png" alt="Hotel" />
                                </div>
                                <div class="type-name">Hotel</div>
                            </div>
                        </div>
                        
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'guest_house' ? 'selected' : '' ?>" onclick="selectType(this, 'guest_house')">
                            <input type="radio" name="property_type" value="guest_house" <?= ($savedData['property_type'] ?? '') === 'guest_house' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/guesthouse.png" alt="Guest House" />
                                </div>
                                <div class="type-name">Guest House</div>
                            </div>
                        </div>
                        
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'home_stay' ? 'selected' : '' ?>" onclick="selectType(this, 'home_stay')">
                            <input type="radio" name="property_type" value="home_stay" <?= ($savedData['property_type'] ?? '') === 'home_stay' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/homestay.png" alt="Home Stay" />
                                </div>
                                <div class="type-name">Home Stay</div>
                            </div>
                        </div>
                        
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'villa' ? 'selected' : '' ?>" onclick="selectType(this, 'villa')">
                            <input type="radio" name="property_type" value="villa" <?= ($savedData['property_type'] ?? '') === 'villa' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/villa.png" alt="Villa" />
                                </div>
                                <div class="type-name">Villa</div>
                            </div>
                        </div>
                        
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'apartment' ? 'selected' : '' ?>" onclick="selectType(this, 'apartment')">
                            <input type="radio" name="property_type" value="apartment" <?= ($savedData['property_type'] ?? '') === 'apartment' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/apartment.png" alt="Apartment" />
                                </div>
                                <div class="type-name">Apartment</div>
                            </div>
                        </div>
                        
                        <div class="property-type-option <?= ($savedData['property_type'] ?? '') === 'alternative' ? 'selected' : '' ?>" onclick="selectType(this, 'alternative')">
                            <input type="radio" name="property_type" value="alternative" <?= ($savedData['property_type'] ?? '') === 'alternative' ? 'checked' : '' ?> required>
                            <div class="type-content">
                                <div class="type-icon">
                                    <img src="/TravelMate/public/assets/images/alterplaces.png" alt="Alternative" />
                                </div>
                                <div class="type-name">Alternative</div>
                            </div>
                        </div>
                    </div>
                    <span class="error-message">Please select a property type</span>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Property Title <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        class="form-control" 
                        placeholder="e.g., Luxury Beach Villa in Bentota"
                        value="<?= htmlspecialchars($savedData['title'] ?? '') ?>"
                        required
                        maxlength="255"
                    >
                    <span class="error-message">Please enter a property title</span>
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label for="location">Location/City <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        class="form-control" 
                        placeholder="e.g., Bentota, Colombo, Galle"
                        value="<?= htmlspecialchars($savedData['location'] ?? '') ?>"
                        required
                        maxlength="255"
                    >
                    <span class="error-message">Please enter the location</span>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Full Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        class="form-control" 
                        placeholder="Enter complete address with street, landmarks, etc."
                    ><?= htmlspecialchars($savedData['address'] ?? '') ?></textarea>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Property Description <span class="required">*</span></label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        placeholder="Describe your property, its features, nearby attractions, and what makes it special..."
                        required
                    ><?= htmlspecialchars($savedData['description'] ?? '') ?></textarea>
                    <span class="error-message">Please enter a description</span>
                </div>

                <!-- Buttons -->
                <div class="btn-container">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/TravelMate/public/index.php?url=Accomodation_provider/newerDashboard'">Cancel</button>
                    <button type="submit" class="btn btn-primary">Next: Property Features →</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>
        function selectType(element, type) {
            // Remove selected class from all options
            document.querySelectorAll('.property-type-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }
        
        // Form validation
        document.getElementById('step1Form').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check all required fields
            const requiredFields = this.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                const formGroup = field.closest('.form-group');
                if (!field.value.trim()) {
                    formGroup.classList.add('has-error');
                    isValid = false;
                } else {
                    formGroup.classList.remove('has-error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
        
        // Remove error on input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.closest('.form-group').classList.remove('has-error');
            });
        });
    </script>
</body>
</html>
