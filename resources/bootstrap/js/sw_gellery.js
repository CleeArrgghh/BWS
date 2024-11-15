document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('bookingLink').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we prepare your booking.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the booking page after a short delay
        setTimeout(() => {
            window.location.href = 'booking.php'; // Redirect to the booking page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});
