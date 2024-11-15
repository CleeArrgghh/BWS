document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('loginButton');
    
    loginButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        
        // Disable the button to prevent multiple clicks
        loginButton.style.pointerEvents = 'none';
        
        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Logging In...',
            text: 'Please wait while we prepare the Login Page.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the login page after a short delay
        setTimeout(() => {
            window.location.href = '../bws_ui/login/log_in.php'; // Redirect to login page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});
