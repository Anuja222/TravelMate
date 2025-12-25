
// Edit Availability Counter Logic
const decrementBtn = document.getElementById('decrement');
const incrementBtn = document.getElementById('increment');
const bedroomCount = document.getElementById('bedroomCount');
const maxBedrooms = 5;
let currentValue = 2;

if (decrementBtn && incrementBtn && bedroomCount) {
	decrementBtn.addEventListener('click', () => {
		if (currentValue > 1) {
			currentValue--;
			bedroomCount.textContent = currentValue;
		}
	});

	incrementBtn.addEventListener('click', () => {
		if (currentValue < maxBedrooms) {
			currentValue++;
			bedroomCount.textContent = currentValue;
		}
	});
}

const updateBtn = document.querySelector('.update-btn');
if (updateBtn) {
	updateBtn.addEventListener('click', (e) => {
		e.preventDefault();
		// You can add your update logic here (e.g., AJAX or form submission)
		alert('Availability updated to ' + currentValue + ' bedrooms.');
	});
}
