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
// restrict access to the page to USER role
access('USER');
$message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the availability value from the form
    $availability = $_POST["availability"];
  
    // Update the availability column in the row with the matching email address
    $email = $_SESSION["SESSION_EMAIL"];
    $sql = "UPDATE users SET availability='$availability' WHERE email='$email'";
  
    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success'>Availability updated successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error updating availability: " . mysqli_error($conn) . "</div>";
    }
  
    // Close the database connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C4V CyberHack - USer Profile</title>
  <meta name="description" content="C4V CyberHack website with registration and login functionality.">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <section class="form">
  <form action="" method="post">
    <div class="container">
      <div class="form-content">
        <div class="form-image">
          <img src="../images/image2.jpg" alt="">
        </div>
        <div class="form-box">
          <div class="form-header">
            <h2>Available for Work?</h2>
            <p>Let companies in need of your skills know you're ready for work!</p>
            <div class='form-group'>
              <?php echo $message; ?>
                  <label for="availability">Availability:</label>
                  <select id="availability" class='form-control' name="availability">
                      <option selected disabled></option>
                      <option value="yes">Available</option>
                      <option value="no">Unavailable</option>
                  </select>
                  <input class='btn' type="submit" value="Update Availability">
              </form>
              </div>
              <div class="form-footer">
                <p>All done for the day?<a href="../private/logout.php"> Log out</a></p>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
