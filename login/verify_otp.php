<?php
include '../../bws_ui/db_connection/db_connection.php';

// Start the session
session_start();

// Check if the username is set in the session
if (!isset($_GET['username'])) {
    header("Location: ../registration.php"); // Redirect if no username is set
    exit();
}

$username = $_GET['username'];

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = implode('', $_POST['otp']); // Combine the 6 fields into one OTP string

    // Fetch the stored OTP from the database
    $stmt = $conn->prepare("SELECT otp FROM tbl_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_otp);
    $stmt->fetch();
    $stmt->close();

    // Verify the OTP
    if ($entered_otp == $stored_otp) {
        // OTP is correct
        echo "<script>
                alert('OTP verified successfully! Registration complete.');
                window.location = '../../bws_ui/index.php';
              </script>";
    } else {
        // OTP is incorrect
        echo "<script>
                alert('Incorrect OTP. Please try again.');
                window.location = 'verify_otp.php?username=$username';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verify OTP</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
    <link rel='stylesheet' href='../../bws_ui//login/login_style/otp_style.css'>
   
</head>

<body>
    <div class="otp-container">
        <h2>Verify OTP</h2>
        <form method="POST" action="">
            <div class="form-group d-flex justify-content-center">
                <!-- Create 6 separate inputs for OTP -->
                <input type="text" class="form-control otp-input" id="otp1" name="otp[]" maxlength="1" required oninput="moveToNext(this, 'otp2')">
                <input type="text" class="form-control otp-input" id="otp2" name="otp[]" maxlength="1" required oninput="moveToNext(this, 'otp3')">
                <input type="text" class="form-control otp-input" id="otp3" name="otp[]" maxlength="1" required oninput="moveToNext(this, 'otp4')">
                <input type="text" class="form-control otp-input" id="otp4" name="otp[]" maxlength="1" required oninput="moveToNext(this, 'otp5')">
                <input type="text" class="form-control otp-input" id="otp5" name="otp[]" maxlength="1" required oninput="moveToNext(this, 'otp6')">
                <input type="text" class="form-control otp-input" id="otp6" name="otp[]" maxlength="1" required oninput="moveToNext(this, '')">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Verify</button>
        </form>
    </div>

    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js'></script>

    <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length == current.maxLength && nextFieldID !== "") {
                document.getElementById(nextFieldID).focus();
            }
        }
    </script>

</body>

</html>
