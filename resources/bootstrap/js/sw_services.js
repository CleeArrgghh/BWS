document.addEventListener('DOMContentLoaded', function() {
    const servicesButton = document.getElementById('servicesLink'); // Make sure this ID matches your services link

    servicesButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Disable the button to prevent multiple clicks
        servicesButton.style.pointerEvents = 'none';

        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Loading Services...',
            text: 'Please wait while we prepare the Services Page.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the services page after a short delay
        setTimeout(() => {
            window.location.href = 'services.php'; // Redirect to the services page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});
