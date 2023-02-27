<?php

$msg = "";

include '../private/header.php';
include '../private/config.php';

if (isset($_GET['reset'])) {
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['reset']}'")) > 0) {
        if (isset($_POST['submit'])) {
            $password = mysqli_real_escape_string($conn, md5($_POST['password']));
            $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));

            if ($password === $confirm_password) {
                $query = mysqli_query($conn, "UPDATE users SET password='{$password}', code='' WHERE code='{$_GET['reset']}'");

                if ($query) {
                    header("Location: index.php");
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Reset Link does not match.</div>";
    }
} else {
    header("Location: forgot-password.php");
}

?>

<!DOCTYPE html>
<html lang="zxx">

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
        <img src="../images/image3.jpg" alt="A caucasian man with short wavy hair and a beard is sitting with an open laptop looking confused. His right hand is gripping his chin. There is a wall of windows behind him and he is wearing a light brown suede jacket">
      </div>
      <div class="form-box">
        <div class="form-header">
          <h2>Change Password</h2>
          <p>Enter your new password below</p>
          <?php echo $msg; ?>
        </div>
        <form action="" method="post">
          <div class="form-group">
            <label for="password">Enter Your Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="confirm-password">Confirm Your Password</label>
            <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
          </div>
          <button name="submit" class="btn" type="submit">Change Password</button>
        </form>
        <div class="form-footer">
          <p>Back to <a href="index.php">Login</a>.</p>
        </div>
      </div>
    </div>
  </div>
</section>

</body>

</html>