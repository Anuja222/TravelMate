<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$email = $isLoggedIn ? $_SESSION['user']['email'] : '';
$phone = $isLoggedIn ? $_SESSION['user']['phone'] : '';
$gender = $isLoggedIn ? $_SESSION['user']['gender'] : '';
$dateOfBirth = $isLoggedIn ? $_SESSION['user']['dateOfBirth'] : '';
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
                            placeholder="Tell us about yourself and your travel experiences...">Adventure seeker and photography enthusiast. Love exploring new cultures and hidden gems around the world. Always planning my next adventure!</textarea>
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
                            <option value="us" selected>United States</option>
                            <option value="ca">Canada</option>
                            <option value="uk">United Kingdom</option>
                            <option value="au">Australia</option>
                            <option value="de">Germany</option>
                            <option value="fr">France</option>
                            <option value="jp">Japan</option>
                            <option value="sg">Singapore</option>
                            <option value="lk">Sri Lanka</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="New York" placeholder="Enter your city">
                    </div>
                </div>
                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <select id="timezone" name="timezone">
                        <option value="America/New_York" selected>Eastern Time (UTC-5)</option>
                        <option value="America/Chicago">Central Time (UTC-6)</option>
                        <option value="America/Denver">Mountain Time (UTC-7)</option>
                        <option value="America/Los_Angeles">Pacific Time (UTC-8)</option>
                        <option value="Europe/London">GMT (UTC+0)</option>
                        <option value="Asia/Tokyo">Japan Time (UTC+9)</option>
                        <option value="Asia/Colombo">Sri Lanka Time (UTC+5:30)</option>
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
                            <option value="adventure" selected>Adventure</option>
                            <option value="relaxation">Relaxation</option>
                            <option value="cultural">Cultural</option>
                            <option value="luxury">Luxury</option>
                            <option value="budget">Budget</option>
                            <option value="family">Family</option>
                            <option value="solo">Solo Travel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="budget">Preferred Budget Range</label>
                        <select id="budget" name="budget">
                            <option value="">Select budget range</option>
                            <option value="low">$500 - $1,500</option>
                            <option value="medium" selected>$1,500 - $5,000</option>
                            <option value="high">$5,000 - $15,000</option>
                            <option value="luxury">$15,000+</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="interests">Travel Interests (comma-separated)</label>
                    <input type="text" id="interests" name="interests"
                        value="Photography, Hiking, Local Cuisine, History, Wildlife"
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
                <button type="submit" class="save-btn" onclick="saveProfile()">Save Changes</button>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script src="assets/js/profilesetting.js"></script>
</body>

</html>