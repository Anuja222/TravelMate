<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);

// Use data passed from controller if available, otherwise use session
$userData = isset($user) ? $user : [];

$firstName = $userData['first_name'] ?? ($isLoggedIn ? $_SESSION['user']['first_name'] : '');
$lastName = $userData['last_name'] ?? ($isLoggedIn ? $_SESSION['user']['last_name'] : '');
$email = $userData['email'] ?? ($isLoggedIn ? $_SESSION['user']['email'] : '');
$phone = $userData['phone'] ?? ($isLoggedIn ? ($_SESSION['user']['phone'] ?? '') : '');
$gender = $userData['gender'] ?? ($isLoggedIn ? ($_SESSION['user']['gender'] ?? '') : '');
$dateOfBirth = $userData['date_of_birth'] ?? ($isLoggedIn ? ($_SESSION['user']['dateOfBirth'] ?? '') : '');
$bio = $userData['bio'] ?? '';
$country = $userData['country'] ?? '';
$city = $userData['city'] ?? '';
$timezone = $userData['timezone'] ?? '';
$travelStyle = $userData['travel_style'] ?? '';
$budget = $userData['budget'] ?? '';
$interests = $userData['interests'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Profile Settings</title>
    <link rel="stylesheet" href="assets/css/Traveller/profilesetting.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>

<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="settings-header">
                <h1>Profile Settings</h1>
                <p>Manage your personal information and preferences</p>
            </div>

            <!-- Profile Photo Section -->
            <div class="settings-section">
                <h2 class="section-title">Profile Photo</h2>
                <div class="profile-photo-section">
                    <img src="assets/images/profile.jpg" class="current-photo" id="profilePhoto">
                    <div class="photo-actions">
                        <div class="photo-upload">
                            <input type="file" id="photoInput" accept="image/*" style="display:none"
                                onchange="previewPhoto(this)">
                            <button type="button" class="upload-btn"
                                onclick="document.getElementById('photoInput').click();">Upload Photo</button>
                        </div>
                        <button class="remove-btn" onclick="removePhoto()">Remove Photo</button>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="settings-section">
                <h2 class="section-title">Personal Information</h2>
                <form id="profileForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName"
                                value="<?php echo htmlspecialchars($firstName); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName"
                                value="<?php echo htmlspecialchars($lastName); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>"
                                placeholder="+94 77 123 4567">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth"
                                value="<?php echo htmlspecialchars($dateOfBirth); ?>">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female
                                </option>
                                <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio"
                            placeholder="Tell us about yourself and your travel experiences..."><?php echo htmlspecialchars($bio); ?></textarea>
                    </div>
                </form>
            </div>

            <!-- Location Information -->
            <div class="settings-section">
                <h2 class="section-title">Location Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country">
                            <option value="">Select country</option>
                            <option value="us" <?php echo ($country === 'us') ? 'selected' : ''; ?>>United States</option>
                            <option value="ca" <?php echo ($country === 'ca') ? 'selected' : ''; ?>>Canada</option>
                            <option value="uk" <?php echo ($country === 'uk') ? 'selected' : ''; ?>>United Kingdom</option>
                            <option value="au" <?php echo ($country === 'au') ? 'selected' : ''; ?>>Australia</option>
                            <option value="de" <?php echo ($country === 'de') ? 'selected' : ''; ?>>Germany</option>
                            <option value="fr" <?php echo ($country === 'fr') ? 'selected' : ''; ?>>France</option>
                            <option value="jp" <?php echo ($country === 'jp') ? 'selected' : ''; ?>>Japan</option>
                            <option value="sg" <?php echo ($country === 'sg') ? 'selected' : ''; ?>>Singapore</option>
                            <option value="lk" <?php echo ($country === 'lk') ? 'selected' : ''; ?>>Sri Lanka</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="Enter your city">
                    </div>
                </div>
                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <select id="timezone" name="timezone">
                        <option value="">Select timezone</option>
                        <option value="America/New_York" <?php echo ($timezone === 'America/New_York') ? 'selected' : ''; ?>>Eastern Time (UTC-5)</option>
                        <option value="America/Chicago" <?php echo ($timezone === 'America/Chicago') ? 'selected' : ''; ?>>Central Time (UTC-6)</option>
                        <option value="America/Denver" <?php echo ($timezone === 'America/Denver') ? 'selected' : ''; ?>>Mountain Time (UTC-7)</option>
                        <option value="America/Los_Angeles" <?php echo ($timezone === 'America/Los_Angeles') ? 'selected' : ''; ?>>Pacific Time (UTC-8)</option>
                        <option value="Europe/London" <?php echo ($timezone === 'Europe/London') ? 'selected' : ''; ?>>GMT (UTC+0)</option>
                        <option value="Asia/Tokyo" <?php echo ($timezone === 'Asia/Tokyo') ? 'selected' : ''; ?>>Japan Time (UTC+9)</option>
                        <option value="Asia/Colombo" <?php echo ($timezone === 'Asia/Colombo') ? 'selected' : ''; ?>>Sri Lanka Time (UTC+5:30)</option>
                        <!-- Add more timezones as needed -->
                    </select>
                </div>
            </div>

            <!-- Travel Preferences -->
            <div class="settings-section">
                <h2 class="section-title">Travel Preferences</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="travelStyle">Travel Style</label>
                        <select id="travelStyle" name="travelStyle">
                            <option value="">Select your style</option>
                            <option value="adventure" <?php echo ($travelStyle === 'adventure') ? 'selected' : ''; ?>>Adventure</option>
                            <option value="relaxation" <?php echo ($travelStyle === 'relaxation') ? 'selected' : ''; ?>>Relaxation</option>
                            <option value="cultural" <?php echo ($travelStyle === 'cultural') ? 'selected' : ''; ?>>Cultural</option>
                            <option value="luxury" <?php echo ($travelStyle === 'luxury') ? 'selected' : ''; ?>>Luxury</option>
                            <option value="budget" <?php echo ($travelStyle === 'budget') ? 'selected' : ''; ?>>Budget</option>
                            <option value="family" <?php echo ($travelStyle === 'family') ? 'selected' : ''; ?>>Family</option>
                            <option value="solo" <?php echo ($travelStyle === 'solo') ? 'selected' : ''; ?>>Solo Travel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="budget">Preferred Budget Range</label>
                        <select id="budget" name="budget">
                            <option value="">Select budget range</option>
                            <option value="low" <?php echo ($budget === 'low') ? 'selected' : ''; ?>>$500 - $1,500</option>
                            <option value="medium" <?php echo ($budget === 'medium') ? 'selected' : ''; ?>>$1,500 - $5,000</option>
                            <option value="high" <?php echo ($budget === 'high') ? 'selected' : ''; ?>>$5,000 - $15,000</option>
                            <option value="luxury" <?php echo ($budget === 'luxury') ? 'selected' : ''; ?>>$15,000+</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="interests">Travel Interests (comma-separated)</label>
                    <input type="text" id="interests" name="interests"
                        value="<?php echo htmlspecialchars($interests); ?>"
                        placeholder="e.g., Photography, Hiking, Food, Culture">
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="settings-section">
                <h2 class="section-title">Privacy Settings</h2>
                <div class="privacy-option">
                    <div class="privacy-info">
                        <h4>Profile Visibility</h4>
                        <p>Make your profile visible to other Travel Mate users</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSetting(this)"></div>
                </div>
                <div class="privacy-option">
                    <div class="privacy-info">
                        <h4>Show Travel History</h4>
                        <p>Display your past trips on your profile</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSetting(this)"></div>
                </div>
                <div class="privacy-option">
                    <div class="privacy-info">
                        <h4>Allow Friend Requests</h4>
                        <p>Let other users send you friend requests</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSetting(this)"></div>
                </div>
                <div class="privacy-option">
                    <div class="privacy-info">
                        <h4>Email Notifications</h4>
                        <p>Receive travel recommendations and updates via email</p>
                    </div>
                    <div class="toggle-switch" onclick="toggleSetting(this)"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="cancelChanges()">Cancel</button>
                <button type="button" class="save-btn" onclick="saveProfile(event)">Save Changes</button>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- Success Modal -->
    <div id="successModal" class="profile-success-modal">
        <div class="profile-success-content">
            <div class="success-icon">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="38" stroke="#10b981" stroke-width="4" fill="#ecfdf5"/>
                    <path d="M25 40L35 50L55 30" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Profile Updated Successfully!</h2>
            <p>Your profile information has been saved and updated.</p>
            <button class="btn-close-success" onclick="closeSuccessModal()">Done</button>
        </div>
    </div>

    <script src="assets/js/profilesetting.js"></script>
    
    <style>
        /* Success Modal Styles */
        .profile-success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .profile-success-modal.show {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .profile-success-content {
            background: white;
            border-radius: 20px;
            padding: 3em 2.5em;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .success-icon {
            margin-bottom: 1.5em;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .profile-success-content h2 {
            color: #10b981;
            font-size: 28px;
            margin: 0 0 0.5em 0;
            font-weight: 700;
        }

        .profile-success-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 2em 0;
            line-height: 1.6;
        }

        .btn-close-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.9em 3em;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-close-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
    </style>
</body>

</html>