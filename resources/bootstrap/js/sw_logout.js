document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logoutLink');

    logoutLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to log out?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, log me out!',
            cancelButtonText: 'No, stay here!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable the button to prevent multiple clicks
                logoutLink.style.pointerEvents = 'none';
                
                // Show SweetAlert loading spinner
                Swal.fire({
                    title: 'Logging Out...',
                    text: 'Please wait while we log you out.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Redirect to logout.php after a short delay
                setTimeout(() => {
                    window.location.href = '../bws_ui/logout.php'; // Redirect to logout.php
                }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
            }
        });
    });
});