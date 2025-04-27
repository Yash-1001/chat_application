<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

session_start(); // Start session

$error = '';
$success_message = '';

if (isset($_POST["register"])) {
    require_once('database/chatuser.php');
    $user_object = new chatuser();
    
    // Validate Name (Only letters and spaces)
    if (!preg_match("/^[a-zA-Z\s]+$/", $_POST['user_name'])) {
        $error = 'Invalid name format. Only letters and spaces are allowed.';
    }

    // Validate Password (6-12 characters)
    if (strlen($_POST['user_password']) < 6 || strlen($_POST['user_password']) > 12) {
        $error = 'Password must be between 6 and 12 characters.';
    }

    if (empty($error)) {
        $user_object->setusername($_POST['user_name']);
        $user_object->setuseremail($_POST['user_email']);
        $user_object->setuserpassword($_POST['user_password']);
        $user_object->setuserprofile($user_object->make_avatar(strtoupper($_POST['user_name'][0])));
        $user_object->setuserstatus('Disabled');
        $user_object->setusercreatedon(date('Y-m-d H:i:s'));
        $user_object->setuserverifycode(md5(uniqid()));

        // Check if email is already registered
        $user_data = $user_object->getuserdatabyemail();
        if (is_array($user_data) && count($user_data) > 0) {
            $error = 'Email already registered. Please login instead.';
        } else {
            if ($user_object->save_data()) {
                // Send Verification Email
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ydwivedi007@gmail.com';
                    $mail->Password = '//write the passkey accordingly'; // ⚠️ Don't expose passwords in code!
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom('ydwivedi007@gmail.com', 'YASH');
                    $mail->addAddress($user_object->getUserEmail());
                    $mail->isHTML(true);
                    $mail->Subject = 'Registration Verification for Chat Application';
                    $mail->Body = '<p>Click below to verify your email:</p>
                        <p><a href="http://localhost/chat_application/verify.php?code=' . $user_object->getuserverifycode() . '">Verify Now</a></p>';

                        $mail->send();
                    $success_message = 'Verification email sent to ' . $user_object->getuseremail() . '. Please verify your email before logging in.';
                } catch (Exception $e) {
                    $error = 'Error sending verification email: ' . $mail->ErrorInfo;
                }
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <title>Register | Chat Application</title>
</head>

<body>
    <div class="container">

        <h1 class="text-center">PHP Chat application</h1>
        <?php
        if ($error != '') {
            echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
  <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
  '.$error.'
</div>';
        }
        if($success_message!='') {
            echo '
            <div class="alert alert-success">'.$success_message.' </div>';
            exit();
        }
        
        ?>
        <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
                <form method="post" id="register-form">
                    <div class="form-group">
                        <label>Enter your name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-patten="/^[a-zA-Z\s]+$/" required />
                    </div>
                    <div class="form-group">
                        <label>Enter your email</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Enter your password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" required />
                    </div>
                    <br>
                    <div class="form-group text-center">
                        <input type="submit" name="register" class="btn btn-success" value="Register" />
                    </div>
                    <br>
                    

                    <div class="form-group text-center">
                    <a href="index.php?logout=true" class="btn btn-primary">Login</a>
                    </div>
                   

                </form>
            </div>
        </div>
    </div>




</body>

</html>

<script>
    $(document).ready(function() {
        $('#register-form').parsley();
    });
</script>
