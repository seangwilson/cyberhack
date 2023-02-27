<?php

define('EMAIL_USERNAME', 'YOUR_EMAIL_ADDRESS');
define('EMAIL_PASSWORD', 'YOUR_EMAIL_PASSWORD');

$conn = mysqli_connect("localhost", "root", "mysql", "cyberhack_db");

if (!$conn) {
    echo "Connection Failed";
}

?>