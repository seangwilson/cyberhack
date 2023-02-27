<?php
    // Start session to enable session variables
    session_start();

    // If session email is set, redirect to welcome page
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: welcome.php");
        die();
    }

    // Include header and database config files
    include '../private/header.php';
    require_once '../private/config.php';
    
    // Initialize an empty message variable
    $msg = "";

    // If there is a verification code in the URL
    if (isset($_GET['verification'])) {
        // Check if the verification code exists in the database
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
            // If verification code exists, update the user's code to empty to verify their account
            $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
            
            // If update query is successful, set success message
            if ($query) {
                $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
            }
        } else {
            // If verification code does not exist in database, redirect to index page
            header("Location: index.php");
        }
    }

    // If login form is submitted
    if (isset($_POST['submit'])) {
      // Escape email and password fields to prevent SQL injection
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = mysqli_real_escape_string($conn, md5($_POST['password']));

      // Query the database to get the user with the submitted email and password
      $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
      $result = mysqli_query($conn, $sql);

      // If there is exactly one matching user, log them in and redirect to welcome page
      if (mysqli_num_rows($result) === 1) {
          $row = mysqli_fetch_assoc($result);

          // Check if user's account has been verified
          if (empty($row['code'])) {
              // Set session variables and redirect to welcome page
              $_SESSION['SESSION_EMAIL'] = $email;
              $_SESSION['SESSION_ROLE'] = $row['role'];
              header("Location: welcome.php");
          } else {
              // If user's account has not been verified, show message to verify account
              $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
          }
      } else {
          // If email or password do not match, show error message
          $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
      }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>C4V CyberHack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="C4V CyberHack - Your ultimate destination for cybersecurity">
  <meta name="keywords" content="cybersecurity, hacking, CTF">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- form section start -->
<section class="form">
  <div class="container">
    <div class="form-content">
      <div class="form-image">
        <img src="../images/image.jpg" alt="A view of a computer screen in a dark room with multiple boxes of code arranged in a grid">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Login Now</h2>
          <p>Welcome back to CyberHack - the ultimate platform for connecting Cyber Professionals with your Business.</p>
        </div>
        <?php echo $msg; ?>
        <form action="" method="post">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required>
          </div>
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remember-me">
            <label class="form-check-label" for="remember-me">Remember me</label>
          </div>
          <button name="submit" class="btn btn-primary" type="submit">Login</button>
          <p><a href="forgot-password.php" class="forgot-password-link">Forgot Password?</a></p>
        </form>
        <div class="form-footer">
          <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

</body>

</html>