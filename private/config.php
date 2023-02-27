<?php

define('EMAIL_USERNAME', 'riverwebdevtest@gmail.com');
define('EMAIL_PASSWORD', 'yomzajaizgxantuj');

$conn = mysqli_connect("localhost", "root", "mysql", "cyberhack_db");

if (!$conn) {
    echo "Connection Failed";
}

?>