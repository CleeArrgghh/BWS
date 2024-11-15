document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('homeLink');
    
    loginButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        
        // Disable the button to prevent multiple clicks
        loginButton.style.pointerEvents = 'none';
        
        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we prepare the Home Page.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the login page after a short delay
        setTimeout(() => {
            window.location.href = '../index.php'; // Redirect to login page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('galleryLink');
    
    loginButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        
        // Disable the button to prevent multiple clicks
        loginButton.style.pointerEvents = 'none';
        
        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we prepare the Gallery Page.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the login page after a short delay
        setTimeout(() => {
            window.location.href = '../gallery.php'; // Redirect to login page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});
