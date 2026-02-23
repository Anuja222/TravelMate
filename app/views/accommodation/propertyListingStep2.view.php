<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    header('Location: /TravelMate/public/index.php?url=Login');
    exit;
}

// Save step 1 data if coming from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['submit_property'])) {
    $_SESSION['listing_step1'] = [
        'property_type' => $_POST['property_type'] ?? '',
        'title' => $_POST['title'] ?? '',
        'location' => $_POST['location'] ?? '',
        'address' => $_POST['address'] ?? '',
        'description' => $_POST['description'] ?? ''
    ];
}

// Check if step 1 data exists
if (!isset($_SESSION['listing_step1'])) {
    header('Location: /TravelMate/public/index.php?url=Accomodation_provider/propertyListingStart');
    exit;
}

$step1Data = $_SESSION['listing_step1'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Property - Step 2 - TravelMate</title>
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
            background: linear-gradient(90deg, #1abc5b 0%, #16a085 100%);
            margin: 0 20px;
            box-shadow: 0 2px 8px rgba(26, 188, 91, 0.2);
        }
        
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin: 40px 0 20px 0;
            padding-bottom: 12px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #1abc5b 0%, #16a085 50%, transparent 100%);
            border-image-slice: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.3px;
        }
        
        .section-title i {
            color: #1abc5b;
            font-size: 24px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .form-row-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
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
        
        select.form-control {
            cursor: pointer;
            background-color: #fafafa !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231abc5b' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 16px center !important;
            background-size: 12px !important;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 40px;
        }
        
        select.form-control:hover {
            background-color: #fff !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2316a085' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 16px center !important;
        }
        
        select.form-control:focus {
            background-color: #fff !important;
        }
        
        input[type="number"].form-control,
        input[type="time"].form-control {
            font-variant-numeric: tabular-nums;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .radio-group, .checkbox-group {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }
        
        .radio-option, .checkbox-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafafa;
            font-weight: 500;
        }
        
        .radio-option:hover, .checkbox-option:hover {
            border-color: #1abc5b;
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 188, 91, 0.15);
        }
        
        .radio-option input:checked, .checkbox-option input:checked {
            accent-color: #1abc5b;
        }
        
        .radio-option:has(input:checked), .checkbox-option:has(input:checked) {
            border-color: #1abc5b;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            box-shadow: 0 4px 12px rgba(26, 188, 91, 0.2);
        }
        
        .radio-option input, .checkbox-option input {
            width: 20px;
            height: 20px;
            accent-color: #1abc5b;
            cursor: pointer;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 16px;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
            font-weight: 500;
        }
        
        .amenity-item:hover {
            border-color: #1abc5b;
            background: #f0fdf4;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 16px rgba(26, 188, 91, 0.15);
        }
        
        .amenity-item:has(input:checked) {
            border-color: #1abc5b;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            box-shadow: 0 4px 12px rgba(26, 188, 91, 0.2);
        }
        
        .amenity-item i {
            font-size: 20px;
            color: #1abc5b;
            width: 24px;
            text-align: center;
        }
        
        .amenity-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #1abc5b;
            cursor: pointer;
        }
        
        .file-upload-area {
            border: 3px dashed #d0d0d0;
            border-radius: 16px;
            padding: 50px 40px;
            text-align: center;
            background: linear-gradient(135deg, #fafafa 0%, #f0f0f0 100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .file-upload-area::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(26, 188, 91, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        
        .file-upload-area:hover::before {
            opacity: 1;
        }
        
        .file-upload-area:hover {
            border-color: #1abc5b;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(26, 188, 91, 0.2);
        }
        
        .file-upload-icon {
            font-size: 64px;
            color: #1abc5b;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .file-upload-area:hover .file-upload-icon {
            transform: scale(1.15) translateY(-5px);
        }
        
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 20px;
        }
        
        .preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid #e8e8e8;
            transition: all 0.3s ease;
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .preview-item:hover {
            border-color: #1abc5b;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .preview-item .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(231, 76, 60, 0.4);
            z-index: 10;
        }
        
        .preview-item .remove-btn:hover {
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
            transform: scale(1.15) rotate(90deg);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.6);
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
        
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
            box-shadow: 0 10px 30px rgba(26, 188, 91, 0.4);
            transform: translateY(-3px);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
        }
        
        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
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
            font-size: 15px;
            margin-top: 16px;
            padding: 16px 20px;
            background: linear-gradient(135deg, #fee 0%, #fdd 100%);
            border-radius: 10px;
            display: none;
            border-left: 4px solid #e74c3c;
            animation: slideDown 0.3s ease;
        }
        
        .success-message {
            color: #27ae60;
            font-size: 15px;
            margin-top: 16px;
            padding: 16px 20px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 10px;
            display: none;
            border-left: 4px solid #27ae60;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .form-row,
            .form-row-2,
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            
            .preview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-container {
                padding: 24px;
            }
        }
        
        /* Success Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .success-modal {
            background: #fff;
            border-radius: 20px;
            padding: 50px 60px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            animation: slideUpScale 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        @keyframes slideUpScale {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .success-modal-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: scaleIn 0.5s ease 0.2s both;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .success-modal-icon::before {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            border: 3px solid #1abc5b;
            border-radius: 50%;
            animation: ripple 1.5s ease-out infinite;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }
        
        .success-modal-icon i {
            font-size: 50px;
            color: #fff;
            animation: checkmark 0.6s ease 0.4s both;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(-45deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(0deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }
        
        .success-modal h2 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 15px 0;
            animation: fadeInUp 0.5s ease 0.5s both;
        }
        
        .success-modal p {
            font-size: 16px;
            color: #666;
            margin: 0 0 30px 0;
            line-height: 1.6;
            animation: fadeInUp 0.5s ease 0.6s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-modal-btn {
            background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
            color: #fff;
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(26, 188, 91, 0.3);
            animation: fadeInUp 0.5s ease 0.7s both;
        }
        
        .success-modal-btn:hover {
            background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
            box-shadow: 0 8px 25px rgba(26, 188, 91, 0.4);
            transform: translateY(-2px);
        }
        
        .redirect-timer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
            animation: fadeInUp 0.5s ease 0.8s both;
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
        <a href="/TravelMate/public/index.php?url=Accomodation_provider/propertyListingStep1" class="breadcrumb-link">
          <i class="fas fa-plus-circle"></i> Step 1 — Basic Details
        </a>
        <i class="fas fa-chevron-right breadcrumb-sep"></i>
        <span class="breadcrumb-current">
          <i class="fas fa-list-check"></i> Step 2 — Features & Details
        </span>
      </div>
    </nav>

    <main class="listing-main">
        <div class="form-container">
            <div class="step-indicator">
                <div class="step">
                    <div class="step-number inactive">1</div>
                    <span>Basic Details</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-number">2</div>
                    <span>Property Features</span>
                </div>
            </div>

            <h1 style="text-align: center; margin-bottom: 12px; font-size: 32px; font-weight: 700; color: #2c3e50; letter-spacing: -0.5px;">
                <i class="fas fa-sparkles" style="color: #1abc5b; margin-right: 12px;"></i>Property Features & Details
            </h1>
            <p style="text-align: center; color: #666; margin-bottom: 40px; font-size: 16px; font-weight: 400;">Complete your listing with amenities, pricing, and photos.</p>

            <div id="errorMessage" class="error-message"></div>
            <div id="successMessage" class="success-message"></div>

            <form method="POST" action="/TravelMate/public/index.php?url=Accomodation_provider/saveProperty" enctype="multipart/form-data" id="step2Form">
                
                <!-- Room Details -->
                <div class="section-title"><i class="fas fa-bed"></i>Room Details</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="rooms">Number of Rooms <span class="required">*</span></label>
                        <input type="number" id="rooms" name="rooms" class="form-control" min="1" max="100" value="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bathrooms">Number of Bathrooms <span class="required">*</span></label>
                        <input type="number" id="bathrooms" name="bathrooms" class="form-control" min="1" max="50" value="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_guests">Maximum Guests <span class="required">*</span></label>
                        <input type="number" id="max_guests" name="max_guests" class="form-control" min="1" max="50" value="2" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Children Allowed?</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="children_yes" name="children_allowed" value="1" checked>
                            <label for="children_yes" style="margin: 0; font-weight: normal;">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="children_no" name="children_allowed" value="0">
                            <label for="children_no" style="margin: 0; font-weight: normal;">No</label>
                        </div>
                    </div>
                </div>

                <!-- Check-in/Check-out Times -->
                <div class="section-title"><i class="fas fa-clock"></i>Check-in & Check-out</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="check_in_start">Check-in Start Time</label>
                        <input type="time" id="check_in_start" name="check_in_start" class="form-control" value="14:00">
                    </div>
                    
                    <div class="form-group">
                        <label for="check_in_end">Check-in End Time</label>
                        <input type="time" id="check_in_end" name="check_in_end" class="form-control" value="22:00">
                    </div>
                    
                    <div class="form-group">
                        <label for="check_out_time">Check-out Time</label>
                        <input type="time" id="check_out_time" name="check_out_time" class="form-control" value="11:00">
                    </div>
                </div>

                <!-- House Rules -->
                <div class="section-title"><i class="fas fa-shield-alt"></i>House Rules</div>
                <div class="checkbox-group">
                    <div class="checkbox-option">
                        <input type="checkbox" id="smoking" name="smoking" value="1">
                        <label for="smoking" style="margin: 0; font-weight: normal;">Smoking Allowed</label>
                    </div>
                    
                    <div class="checkbox-option">
                        <input type="checkbox" id="parties" name="parties" value="1">
                        <label for="parties" style="margin: 0; font-weight: normal;">Events/Parties Allowed</label>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 16px;">
                    <label>Pets Policy</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="pets_yes" name="pets" value="yes">
                            <label for="pets_yes" style="margin: 0; font-weight: normal;">Allowed</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="pets_no" name="pets" value="no" checked>
                            <label for="pets_no" style="margin: 0; font-weight: normal;">Not Allowed</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="pets_assistance" name="pets" value="assistance_only">
                            <label for="pets_assistance" style="margin: 0; font-weight: normal;">Assistance Animals Only</label>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="section-title"><i class="fas fa-address-book"></i>Contact Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name">Contact Name</label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Your name" value="<?= htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone">Contact Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control" placeholder="+94 77 123 4567" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
                    </div>
                </div>

                <!-- Amenities -->
                <div class="section-title"><i class="fas fa-star"></i>Amenities & Features</div>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <input type="checkbox" id="wifi" name="amenities[]" value="WiFi">
                        <i class="fas fa-wifi"></i>
                        <label for="wifi" style="margin: 0; font-weight: normal;">WiFi</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="ac" name="amenities[]" value="Air Conditioning">
                        <i class="fas fa-snowflake"></i>
                        <label for="ac" style="margin: 0; font-weight: normal;">Air Conditioning</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="tv" name="amenities[]" value="TV">
                        <i class="fas fa-tv"></i>
                        <label for="tv" style="margin: 0; font-weight: normal;">TV</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="kitchen" name="amenities[]" value="Kitchen">
                        <i class="fas fa-utensils"></i>
                        <label for="kitchen" style="margin: 0; font-weight: normal;">Kitchen</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="pool" name="amenities[]" value="Swimming Pool">
                        <i class="fas fa-swimmer"></i>
                        <label for="pool" style="margin: 0; font-weight: normal;">Swimming Pool</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="gym" name="amenities[]" value="Gym">
                        <i class="fas fa-dumbbell"></i>
                        <label for="gym" style="margin: 0; font-weight: normal;">Gym</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="laundry" name="amenities[]" value="Laundry">
                        <i class="fas fa-soap"></i>
                        <label for="laundry" style="margin: 0; font-weight: normal;">Laundry</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="beach" name="amenities[]" value="Beach Access">
                        <i class="fas fa-umbrella-beach"></i>
                        <label for="beach" style="margin: 0; font-weight: normal;">Beach Access</label>
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" id="balcony" name="amenities[]" value="Balcony">
                        <i class="fas fa-tree"></i>
                        <label for="balcony" style="margin: 0; font-weight: normal;">Balcony</label>
                    </div>
                </div>

                <!-- Services -->
                <div class="section-title"><i class="fas fa-concierge-bell"></i>Services</div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Breakfast</label>
                        <select name="breakfast" class="form-control">
                            <option value="no">Not Included</option>
                            <option value="included">Included</option>
                            <option value="available">Available (Extra Charge)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Parking</label>
                        <select name="parking" class="form-control">
                            <option value="no">Not Available</option>
                            <option value="free">Free Parking</option>
                            <option value="paid">Paid Parking</option>
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="section-title"><i class="fas fa-dollar-sign"></i>Pricing</div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="price_per_night">Price Per Night (LKR) <span class="required">*</span></label>
                        <input type="number" id="price_per_night" name="price_per_night" class="form-control" min="0" step="0.01" placeholder="e.g., 15000" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price_per_guest">Price Per Additional Guest (LKR)</label>
                        <input type="number" id="price_per_guest" name="price_per_guest" class="form-control" min="0" step="0.01" placeholder="e.g., 2000">
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="section-title"><i class="fas fa-camera"></i>Property Photos</div>
                <div class="form-group">
                    <div class="file-upload-area" onclick="document.getElementById('images').click()">
                        <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <h3 style="margin: 0 0 10px 0; font-weight: 600; color: #2c3e50; font-size: 20px;">Upload Property Photos</h3>
                        <p style="color: #666; margin: 0 0 20px 0; font-size: 15px;">Add at least 5 photos to get more bookings</p>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple style="display: none;">
                        <button type="button" class="btn btn-primary" style="pointer-events: none; position: relative; z-index: 1;">
                            <i class="fas fa-images" style="margin-right: 8px;"></i>Choose Photos
                        </button>
                    </div>
                    <div id="previewGrid" class="preview-grid"></div>
                </div>

                <!-- Buttons -->
                <div class="btn-container">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Back
                    </button>
                    <button type="submit" name="submit_property" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Submit Property Listing
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="success-modal">
            <div class="success-modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Property Listed Successfully!</h2>
            <p>Your property has been successfully added to our platform. Guests can now discover and book your amazing property.</p>
            <button class="success-modal-btn" onclick="redirectToDashboard()">View My Properties</button>
            <div class="redirect-timer">Redirecting to dashboard in <span id="countdown">3</span> seconds...</div>
        </div>
    </div>

    <script>
        // Prevent button click from propagating when clicking upload area
        document.querySelector('.file-upload-area button').addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Image preview
        const imageInput = document.getElementById('images');
        const previewGrid = document.getElementById('previewGrid');
        let selectedFiles = [];

        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.push(file);
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-btn" onclick="removeImage(${selectedFiles.length - 1})">×</button>
                        `;
                        previewGrid.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updatePreviewGrid();
        }

        function updatePreviewGrid() {
            previewGrid.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
                    `;
                    previewGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }

        // Form submission
        document.getElementById('step2Form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const errorMsg = document.getElementById('errorMessage');
            const successMsg = document.getElementById('successMessage');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Submitting...';
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';
            
            // Create FormData from form
            const formData = new FormData(this);
            
            // Add selected images to FormData
            // Remove any existing images[] entries first
            formData.delete('images[]');
            
            selectedFiles.forEach((file, index) => {
                formData.append('images[]', file, file.name);
            });
            
            console.log('Form data being sent:');
            for (let pair of formData.entries()) {
                if (pair[1] instanceof File) {
                    console.log(pair[0], ':', pair[1].name, '(', pair[1].size, 'bytes)');
                } else {
                    console.log(pair[0], ':', pair[1]);
                }
            }
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    showSuccessModal();
                } else {
                    errorMsg.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>' + data.errors.join(', ');
                    errorMsg.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>Submit Property Listing';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>An error occurred. Please try again.';
                errorMsg.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>Submit Property Listing';
            });
        });
        
        // Success Modal Functions
        let countdownInterval;
        
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Start countdown
            let seconds = 3;
            const countdownElement = document.getElementById('countdown');
            
            countdownInterval = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    redirectToDashboard();
                }
            }, 1000);
        }
        
        function redirectToDashboard() {
            clearInterval(countdownInterval);
            window.location.href = '/TravelMate/public/index.php?url=Accomodation_provider/newerDashboard';
        }
        
        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('successModal');
            if (e.target === modal) {
                redirectToDashboard();
            }
        });
    </script>
</body>
</html>
