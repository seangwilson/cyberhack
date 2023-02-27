<?php

// start session
session_start();

// if the user has an active session redirect to welcome.php and end the script execution
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

// import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// load Composer's autoloader
require '../vendor/autoload.php';

// include header and config files
include '../private/header.php';
include '../private/config.php';


// initialize $msg variable to an empty string
$msg = "";

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $code = mysqli_real_escape_string($conn, password_hash(rand(), PASSWORD_DEFAULT)); // generate a random verification code

    // if the email exists in the users table, update the code with the new verification code
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users SET code = ? WHERE email = ?");
        $stmt->bind_param("ss", $code, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div style='display: none;'>";
            // create a new instance of PHPMailer passing `true` to enable exceptions
            $mail = new PHPMailer(true);

            try {
                // server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // enable verbose debug output
                $mail->isSMTP();                                            // send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       // set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // enable SMTP authentication
                $mail->Username   = EMAIL_USERNAME;            // SMTP username
                $mail->Password   = EMAIL_PASSWORD;                      // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // enable implicit TLS encryption
                $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                // recipients
                $mail->setFrom(EMAIL_USERNAME);
                $mail->addAddress($email);

                // content
                $mail->isHTML(true);                                        // set email format to HTML
                $mail->Subject = 'C4V CyberHack Password Reset';
                $mail->Body    = 'Here is the verification link <b><a href="http://localhost/cyberhack/public/change-password.php?reset='.$code.'">http://localhost/cyberhack/public/change-password.php?reset='.$code.'</a></b>';

                // send email and display success message
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                // if the email could not be sent, display error message
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";        
            $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
        }
    } else {
        // if the email does not exist in the users table, display error message
        $msg = "<div class='alert alert-danger'>" . htmlspecialchars($email, ENT_QUOTES) . " - This email address is not found.</div>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C4V CyberHack - Forgot Password</title>
    <meta name="description" content="Forgot Password Form">
    <meta name="keywords" content="C4V CyberHack, Forgot Password, Form">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- form section start -->
    <section class="form">
  <div class="container">
    <div class="form-content">
      <div class="form-image">
        <img src="../images/image3.jpg" alt="A caucasian man with short wavy hair and a beard is sitting with an open laptop looking confused. His right hand is gripping his chin. There is a wall of windows behind him and he is wearing a light brown suede jacket.">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Forgot Password</h2>
          <p>Fill in your email address and we'll send you a link to reset your password.</p>
        </div>
        <form action="" method="post">
          <div class="form-group">
            <label for="email">Enter Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <button name="submit" class="btn" type="submit">Send Reset Link</button>
          <div class="form-footer">
          <p>Back to! <a href="index.php">Login</a>.</p>
        </div>
        </form>

      </div>
    </div>
  </div>
</section>
</body>

</html>
