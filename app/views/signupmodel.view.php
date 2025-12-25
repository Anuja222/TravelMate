<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Sri Lanka Tourism</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/signupmodel.css">
</head>

<body>
    <!-- <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="https://via.placeholder.com/50x50/1abc5b/ffffff?text=SL" alt="Logo" class="logo">
                <span style="font-size: 1.2em; font-weight: 600;">Sri Lanka Tourism</span>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#destinations">Destinations</a></li>
                <li><a href="#accommodation">Stay</a></li>
                <li><a href="#transport">Travel</a></li>
                <li><a href="#experiences">Experiences</a></li>
            </ul>
            <div class="nav-actions">
                <button class="btn login" onclick="window.location.href='#login'">Login</button>
                <button class="btn signup active">Sign Up</button>
            </div>
        </nav>
    </header> -->

    <main class="signup-container">
        <div id="userTypeSelection" class="user-type-selection">
            <div class="signup-header">
                <h1>Create Your Account</h1>
                <p>Choose your account type to get started with Sri Lanka's premier tourism platform</p>
            </div>

            <div class="user-type-cards">
                <div class="user-type-card" data-type="traveller" onclick="selectUserType('traveller')">
                    <div class="card-icon">🧳</div>
                    <h3>Traveller</h3>
                    <p>Discover and book amazing experiences across Sri Lanka</p>
                    <ul class="card-features">
                        <li>Book accommodations & transport</li>
                        <li>Discover local experiences</li>
                        <li>Create personalized itineraries</li>
                        <li>Access exclusive travel deals</li>
                        <li>24/7 customer support</li>
                    </ul>
                </div>

                <div class="user-type-card" data-type="accommodation" onclick="selectUserType('accommodation')">
                    <div class="card-icon">🏨</div>
                    <h3>Accommodation Provider</h3>
                    <p>List your property and connect with travelers worldwide</p>
                    <ul class="card-features">
                        <li>List hotels, villas, or homestays</li>
                        <li>Manage bookings & availability</li>
                        <li>Analytics & reporting tools</li>
                        <li>Marketing support</li>
                        <li>Secure payment processing</li>
                    </ul>
                </div>

                <div class="user-type-card" data-type="transport" onclick="selectUserType('transport')">
                    <div class="card-icon">🚗</div>
                    <h3>Transport Provider</h3>
                    <p>Offer your transportation services to tourists</p>
                    <ul class="card-features">
                        <li>List cars, vans, or tour buses</li>
                        <li>Flexible scheduling system</li>
                        <li>Route planning tools</li>
                        <li>Driver management</li>
                        <li>Real-time booking alerts</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        let selectedUserType = null;

        function selectUserType(type) {
            selectedUserType = type;

            // Store the selected role in localStorage
            localStorage.setItem('selectedUserRole', type);

            // Remove selected class from all cards
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            document.querySelector(`[data-type="${type}"]`).classList.add('selected');

            // Show the form after a brief delay for visual feedback
            setTimeout(() => {
                // Verify role is stored before redirect
                const storedRole = localStorage.getItem('selectedUserRole');
                console.log('Stored role before redirect:', storedRole); // Add this for debugging
                window.location.href = 'signup';
            }, 300);
        }

        function showSignupForm(type) {
            // Hide user type selection
            document.getElementById('userTypeSelection').style.display = 'none';

            // Hide all forms
            document.querySelectorAll('.signup-form-container').forEach(form => {
                form.classList.remove('active');
            });

            // Show the appropriate form
            const formId = type === 'traveller' ? 'travellerForm' :
                type === 'accommodation' ? 'accommodationForm' :
                    'transportForm';

            document.getElementById(formId).classList.add('active');
        }

        function goBack() {
            // Hide all forms
            document.querySelectorAll('.signup-form-container').forEach(form => {
                form.classList.remove('active');
            });

            // Show user type selection
            document.getElementById('userTypeSelection').style.display = 'block';

            // Clear form selection
            selectedUserType = null;
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.classList.remove('selected');
            });
        }

        // Handle form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Basic validation
                const passwords = this.querySelectorAll('input[type="password"]');
                if (passwords.length === 2 && passwords[0].value !== passwords[1].value) {
                    alert('Passwords do not match!');
                    return;
                }

                // Here you would typically send the form data to your server
                alert(`Account creation for ${selectedUserType} submitted successfully!`);

                // Reset form (in real implementation, redirect to login or dashboard)
                this.reset();
                goBack();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Check if role exists in localStorage
            const storedRole = localStorage.getItem('selectedUserRole');
            console.log('Stored role on page load:', storedRole);

            if (storedRole) {
                // If role exists, highlight the corresponding card
                const card = document.querySelector(`[data-type="${storedRole}"]`);
                if (card) {
                    card.classList.add('selected');
                }
            }
        });

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>

</html>