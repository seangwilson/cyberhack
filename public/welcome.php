<?php
// Start the session to check if the user is logged in
session_start();
// Redirect to the login page if the user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

// Include the header and config files
include '../private/header.php';
require_once '../private/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C4V CyberHack - Welcome</title>
  <meta name="description" content="C4V CyberHack website with registration and login functionality.">
  <link rel="stylesheet" href="../css/style.css">
</head>

<section class="form">
  <div class="container">
    <div class="form-content">
      <div class="form-image">
        <img src="../images/image.jpg" alt="A view of a computer screen in a dark room with multiple boxes of code arranged in a grid">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Welcome to CyberHack</h2>
          <p>Please select NGO to see a list of available Cyber Professionals or Profile to update your work availability.</p>
        </div>
        <div class='btn-grp'>
          <a href="ngo.php"><button name="ngo" class="btn" type="button">NGO</button></a>
          <a href="profile.php"><button name="profile" class="btn" type="button">Profile</button></a>
        </div>
        <div class="form-footer">
          <p>All done for the day?<a href="../private/logout.php"> Log out</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

  </body>
</html>