<?php
session_start();

if (isset($_POST['action']) && $_POST['action'] == 'leave') {
    require('database/chatuser.php');
    $user_object = new Chatuser;
    $user_object->setuserid($_POST['user_id']);
    $user_object->setuserloginstatus('Logout');
    if($user_object->update_user_login_data()){
        unset($_SESSION['user_data']);
        session_destroy();
        echo json_encode(['status'=> 1]);

    }
}
  


?>

