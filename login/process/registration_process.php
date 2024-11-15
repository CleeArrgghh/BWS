<?php
include '../../db_connection/db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to display a SweetAlert message and redirect
function showAlert($title, $message, $icon, $redirectUrl)
{
    echo "<script>
            Swal.fire({
                title: '$title',
                text: '$message',
                icon: '$icon',
                confirmButtonText: 'Okay'
            }).then(function() {
                window.location = '$redirectUrl';
            });
          </script>";
}

// Function to validate password (min length 8, alphanumeric)
function validatePassword($password)
{
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}

// Function to validate email
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to send OTP via Semaphore API
function sendOtp($phone, $otp)
{
    $ch = curl_init();
    $parameters = array(
        'apikey' => 'ed49311fb8620f4e674df7cd10181e95', // Your API KEY
        'number' => $phone,
        'message' => 'Your OTP for registration is: ' . $otp . '. For your security, do not share this OTP with anyone.',
        'sendername' => 'Bws'
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/otp');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']); // Added email
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $middlename = $conn->real_escape_string($_POST['middlename']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $age = (int)$_POST['age'];
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $password = $conn->real_escape_string($_POST['password']);

    // Start the HTML output
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Registration</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    </head>
    <body>";

    // Validate password
    if (!validatePassword($password)) {
        showAlert('Error!', 'Password must be at least 8 characters long and contain both letters and numbers.', 'error', '../registration.php');
    } elseif (!validateEmail($email)) { // Validate email
        showAlert('Error!', 'Invalid email address.', 'error', '../registration.php');
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or user already exists
        $checkSql = "SELECT * FROM tbl_users WHERE username = '$username' OR (firstname = '$firstname' AND lastname = '$lastname') OR email = '$email'";
        $result = $conn->query($checkSql);

        if ($result->num_rows > 0) {
            showAlert('Error!', 'A user with this username, name, or email already exists. Please choose different details.', 'error', '../registration.php');
        } else {
            // Generate a random OTP
            $otp = rand(100000, 999999); // 6-digit OTP

            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO tbl_users (username, email, firstname, middlename, lastname, phone, address, age, dob, gender, password, otp) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("SQL error: " . $conn->error); // Display SQL error
            }
            $stmt->bind_param("ssssissssssi", $username, $email, $firstname, $middlename, $lastname, $phone, $address, $age, $dob, $gender, $hashed_password, $otp);

            if ($stmt->execute()) {
                sendOtp($phone, $otp); // Send OTP

                echo "<script>
                        Swal.fire({
                            title: 'Please wait...',
                            text: 'Sending OTP...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading(); // Show loader
                            }
                        });

                        setTimeout(function() {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Registration successful! An OTP has been sent to your phone.',
                                    icon: 'success',
                                    confirmButtonText: 'Great!'
                                }).then(function() {
                                    window.location = '../../login/verify_otp.php?username=$username'; // Redirect to OTP verification
                                });
                            }, 2000); // 2-second delay before showing success message

                      </script>";
            } else {
                showAlert('Error!', 'Registration failed: ' . $stmt->error, 'error', '../../index.php');
            }
            $stmt->close(); // Close statement
        }
    }

    // Close HTML tags
    echo "</body></html>";

    // Close the connection
    $conn->close();
}
