<?php 
function generateSecretKey($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

$secret_key = generateSecretKey();
echo $secret_key; // Outputs the generated secret key
?>