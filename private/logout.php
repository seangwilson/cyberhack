<?php
// Start the session to access session variables
session_start();

// Unset all of the session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the index.php page
header("Location: ../public/index.php");