
    document.addEventListener('DOMContentLoaded', function() {
        const homeLink = document.getElementById('homeLink');
        
        homeLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            
            // Disable the link to prevent multiple clicks
            homeLink.style.pointerEvents = 'none';
            
            // Show SweetAlert with a loading spinner
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we prepare the Home Page.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Redirect to the gallery page after a short delay
            setTimeout(() => {
                window.location.href = 'index.php'; // Redirect to the gallery page
            }, 1500); // Adjust delay as needed (1500 ms = 1.5 seconds)
        });
    });

