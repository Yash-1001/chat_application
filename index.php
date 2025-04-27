<?php
session_start();
$error='';


if (isset($_GET['logout'])) {
    session_destroy();  // Destroy session if logout=true is set
    header("location: index.php");
    exit();
}

if(isset($_SESSION['user_data'])) {
    header('location: chatroom.php'); 
    exit();
}

if(isset($_POST['login'])){
    require_once('database/chatuser.php');
    $user_object = new Chatuser;
    $user_object->setuseremail($_POST['user_email']);
    $user_data = $user_object-> getuserdatabyemail();
    if (is_array($user_data) && count($user_data) > 0) {
        if ($user_data['user_status'] == 'Enable') {
            if ($user_data['user_password'] == $_POST['user_password']) {
                $user_object->setuserid($user_data['user_id']);
                $user_object->setuserloginstatus('Login');
                if($user_object->update_user_login_data()){
                    $_SESSION['user_data'][$user_data['user_id']] = [
                        'id' => $user_data['user_id'],
                        'name' => $user_data['user_name'],
                        'profile' => $user_data['user_profile'],
                    ];

                    header('location:chatroom.php');
                }
            } else {
                $error = 'wrong password';
            }
        } else {
            $error = 'verify your email';
        }
    } else {
        $error = 'wrong email';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="container">

        <h1 class="text-center">PHP Chat application</h1>
        <div class="row justify-content-md-center mt-5">
            <div class="col-md-4">
                <?php
                if(isset($_SESSION['success_message'])){
                    echo'
                    <div class="alert alert-success">'.$_SESSION['success_message'].'</div>';
                    unset($_SESSION['success_message']);
                }
                if($error != ''){
                    echo '
                    <div class="alert-alert-danger">
                    '.$error.'</div>';
                  
                }

                ?>
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="post" id="login-form">
                            <div class="form-group">
                                <label>Enter your email</label>
                                <input type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required/>
                            </div>
                            <div class="form-group">
                                <label>Enter your password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control" required/>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="login" id="login" class="btn btn-primary" value="Login" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>

<script>

    $(document).ready(function(){
        $('#login_form').parsley();

    });
</script>