
// Get the current year
const currentYear = new Date().getFullYear();

// Set the minimum year to 1990 and maximum to the current year
const minYear = 1990;
document.getElementById('dob').setAttribute('min', `${minYear}-01-01`);
document.getElementById('dob').setAttribute('max', `${currentYear}-12-31`);

