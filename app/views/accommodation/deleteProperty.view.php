<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Delete Property</title>
	<link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/deleteProperty.css">
	<link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
	<link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
	<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
	<section class="delete-section">
		<h1>Delete Property</h1>
		<div class="delete-box">
			<p><strong>Are you sure you want to delete your <span class="property-name">ABC Villa?</span></strong></p>
				<div class="delete-actions">
					<button class="yes-btn" onclick="(function(){var el=document.querySelector('.property-name'); var prop=(el && el.textContent)||'this property'; if(confirm('The property &quot;'+prop+'&quot; will be deleted. OK to proceed?')){ window.location.href='/TravelMate/Accomodation_provider/dashboard'; }})();">YES</button>
					<a href="/TravelMate/Accomodation_provider/viewProperty"><button class="no-btn">NO</button></a>
				</div>
		</div>
	</section>
	<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
	<script src="/TravelMate/public/assets/js/Accomodation_provider/deleteProperty.js"></script>
</body>
</html>

