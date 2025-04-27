<?php
require 'vendor/autoload.php';
require_once('database/chatuser.php');
$error='';
session_start();
if(isset($_GET['code'])){
    
    $user_object=new Chatuser();
    $user_object->setuserverifycode($_GET['code']);
    if($user_object->is_valid_email_code()){
        $user_object->setuserstatus('Enable');
        if($user_object->enable_user_account()){
            $_SESSION['success_message']='your email is verified, now login';
            header('location:index.php');

    }
    else{
        $error= 'something went wrong';

    }
}
    else{
        $error= 'something went wrong';

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
    
</body>
</html>