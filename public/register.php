<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Start the session
    session_start();
    // If the user is logged in redirect to the welcome page
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: welcome.php");
        die();
    }

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    include '../private/header.php';
    require_once '../private/config.php';
    $msg = "";

    if (isset($_POST['submit'])) {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $role = mysqli_real_escape_string($conn, $_POST['role']);
      $password = mysqli_real_escape_string($conn, md5($_POST['password']));
      $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
      $code = mysqli_real_escape_string($conn, md5(rand()));
  
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
          $msg = "<div class='alert alert-danger'>{$email} - This email address already exists.</div>";
      } else {
          if ($password === $confirm_password) {
              // prepare and bind the SQL statement to prevent SQL injection
              $stmt = $conn->prepare("INSERT INTO users (name, email, role, availability, password, code) VALUES (?, ?, ?, 'no', ?, ?)");
              $stmt->bind_param("sssss", $name, $email, $role, $password, $code);
              $result = $stmt->execute();
              $stmt->close();
  
              if ($result) {
                  echo "<div style='display: none;'>";
                  //Create an instance; passing `true` enables exceptions
                  $mail = new PHPMailer(true);
  
                  try {
                      //Server settings
                      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
                      $mail->isSMTP();                                           //Send using SMTP
                      $mail->Host       = 'smtp.gmail.com';                      //Set the SMTP server to send through
                      $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
                      $mail->Username   = EMAIL_USERNAME;           //SMTP username
                      $mail->Password   = EMAIL_PASSWORD;                    //SMTP password
                      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                      $mail->Port       = 465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
                      //Recipients
                      $mail->setFrom(EMAIL_USERNAME);
                      $mail->addAddress($email);
  
                      //Content
                      $mail->isHTML(true);                                       //Set email format to HTML
                      $mail->Subject = 'C4V CyberHack Registration';
                      $mail->Body    = 'Click the link to verify your email. <b><a href="http://localhost/cyberhack/public/?verification='.$code.'">http://localhost/cyberhack/public/?verification='.$code.'</a></b>';
  
                      $mail->send();
                      echo 'Message has been sent';
                  } catch (Exception $e) {
                      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  }
                  echo "</div>";
                  $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
              } else {
                  $msg = "<div class='alert alert-danger'>Something went wrong.</div>";
              }
          } else {
              $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
          }
      }
  }
     
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C4V CyberHack - Register</title>
  <meta name="description" content="C4V CyberHack website with registration and login functionality.">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- form section start -->
<section class="form">
  <div class="container">
    <div class="form-content">
      <div class="form-image">
        <img src="../images/image2.jpg" alt="A close up of a MacBook keyboard. The keys are black with white letters and the base of the MacBook is space grey. The image is zoomed in on the shift key">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Register Now</h2>
          <p>Join CyberHack today and start exploring the limitless possibilities of the cybersecurity world</p>
          <?php echo $msg; ?>
        </div>
        <form action="" method="post">
          <div class="form-group">
            <label for="name">Enter Your Name</label>
            <input type="text" id="name" class="form-control" name="name" value="<?php if (isset($_POST['submit'])) { echo $name; } ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Enter Your Email</label>
            <input type="email" id="email" class="form-control" name="email" value="<?php if (isset($_POST['submit'])) { echo $email; } ?>" required>
          </div>
          <div class="form-group">
            <label for="role">Select Your Role</label>
            <select id="role" class="form-control" name="role" required>
              <option selected disabled></option>
              <option value="ngo">NGO</option>
              <option value="user">Cyber Professional</option>
            </select>
          </div>
          <div class="form-group">
            <label for="password">Enter Your Password</label>
            <input type="password" id="password" class="form-control" name="password" required>
          </div>
          <div class="form-group">
            <label for="confirm-password">Enter Your Confirm Password</label>
            <input type="password" id="confirm-password" class="form-control" name="confirm-password" required>
          </div>
          <button name="submit" class="btn" type="submit">Register</button>
        </form>
        <div class="form-footer">
          <p>Have an account? <a href="index.php">Login</a>.</p>
        </div>
      </div>
    </div>
  </div>
</section>

</body>

</html>