document.addEventListener('DOMContentLoaded', function() {
    const bookingLink = document.querySelector('a[href="../bws_ui/booking/booking.php"]');
    
    bookingLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        
        // Disable the link to prevent multiple clicks
        bookingLink.style.pointerEvents = 'none';
        
        // Show SweetAlert with a loading spinner
        Swal.fire({
            title: 'Loading Booking...',
            text: 'Please wait while we load the booking page.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to the booking page after a short delay
        setTimeout(() => {
            window.location.href = '../booking.php'; // Redirect to the booking page
        }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
    });
});
