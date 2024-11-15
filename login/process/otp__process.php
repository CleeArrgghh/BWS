<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bernadette Wellness Spa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../bws_ui/login/login_style/otp_style.css">
</head>

<body>
    <div class="otp-container">
        <h2>Enter OTP</h2>
        <form id="otpForm" method="post" class="otp-form">
            <input type="text" maxlength="1" class="otp-input" />
            <input type="text" maxlength="1" class="otp-input" />
            <input type="text" maxlength="1" class="otp-input" />
            <input type="text" maxlength="1" class="otp-input" />
            <input type="text" maxlength="1" class="otp-input" />
            <input type="text" maxlength="1" class="otp-input" />
        </form>
        <button type="button" class="otp-submit" onclick="processOTP()">Verify OTP</button>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        // Auto-focus function for OTP inputs
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', (e) => {
                // Move to next input if a digit is entered
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                // Move to previous input if backspace is pressed
                if (e.key === 'Backspace' && index > 0 && e.target.value === '') {
                    inputs[index - 1].focus();
                }
            });
        });

        // Process OTP function
        function processOTP() {
            let otp = '';
            document.querySelectorAll('.otp-input').forEach(input => {
                otp += input.value;
            });

            // Example of static OTP validation (replace with actual OTP validation logic)
            const validOtp = '123456'; // You can change this to dynamically verify with your backend.

            if (otp === validOtp) {
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Verified!',
                    text: 'You have successfully verified the OTP.',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid OTP',
                    text: 'The OTP you entered is incorrect. Please try again.',
                    confirmButtonText: 'Try Again'
                });
            }
        }
    </script>
</body>

</html>
