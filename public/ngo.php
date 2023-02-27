<?php
// Start the session to check if the user is logged in
session_start();
// Redirect to the login page if the user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

// include the config, header and access file.
require_once '../private/config.php';
include '../private/header.php';
include '../private/access.php';
// restrict access to the page to NGO role
access('NGO');

?>

<!DOCTYPE html>
<html>
<head>
	<title>C4V CyberHack - NGO</title>
    <!--/Style-CSS -->
    <link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
    <!--//Style-CSS -->
</head>
<body>
<section class="form">
  <div class="container">
    <div class="form-content">
      <div class="form-image">
        <img src="../images/image5.jpg" alt="Close up look at a PC screen displaying lines of white code on a black background. We can see a the blurred side of a persons face and ear in the foreground">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Available Cyber Professionals</h2>
          <?php include '../private/usernames.php' ?>
        </div>
        <div class="form-footer">
          <p>All done for the day?<a href="../private/logout.php"> Log out</a></p>
        </div>
    </div>
  </div>
</section>
</body>
</html>