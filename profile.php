<?php
session_start();
if (!isset($_SESSION["user_data"])) {
    header("location:index.php");
}
require('database/chatuser.php');
$user_object = new Chatuser;
$user_id = '';
foreach ($_SESSION['user_data'] as $key => $value) {
    $user_id = $value['id'];
}
$user_object->setuserid($user_id);

$user_data = $user_object->get_user_data_by_id();

$message ='';
if(isset($_POST['edit'])) {
    $user_profile = $_POST['hidden_user_profile'];
    if($_FILES['user_profile']['name']!=''){
        $user_profile = $user_object->upload_image($_FILES['user_profile']);
        $_SESSION['user_data'][$user_id]['profile']= $user_profile;
    }
    $user_object->setusername($_POST['user_name']);
    $user_object->setuseremail($_POST['user_email']);
    $user_object->setuserpassword($_POST['user_password']);
    $user_object->setuserprofile($user_profile);
    $user_object->setuserid($user_id);
     if ($user_object->update_data()) {
        $_SESSION['user_data'][$user_id]['name'] = $_POST['user_name'];
        $_SESSION['user_data'][$user_id]['email'] = $_POST['user_email'];
        $_SESSION['user_data'][$user_id]['password'] = $_POST['user_password'];
        $message = '<div class="alert alert-success">Profile Details Updated</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to update profile details</div>';
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
        <br>
        <br>
        <?php echo $message ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">Profile</div>
                    <div class="col-md-6 text-right">
                        <a href="chatroom.php" class="btn btn-warning btn-sm">Go to Chat</a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <form method="post" id="profile-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label >Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required value="<?php echo $user_data['user_name'];?>"/>

                    </div>
                    <div class="form-group">
                        <label >Email</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" required value="<?php echo $user_data['user_email'];?>"/>

                    </div>
                    <div class="form-group">
                        <label >Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-pattern="^[a-zA-Z]+$" required value="<?php echo $user_data['user_password'];?>"/>

                    </div>
                    <div class="form-group">
                        <label >Profile</label>
                        <input type="file" name="user_profile" id="user_profile" />
                        <br/>
                        <img src="<?php echo $user_data['user_profile'];?>" class="img-fluid img-thumbnail mt-3" width="100">
                        <input type="hidden" name="hidden_user_profile" value="<?php echo $user_data['user_profile'];?>">

                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="edit" class="btn btn-primary" value="edit"/>

                    </div>

                </form>
            </div>



        </div>

    </div>


</body>

</html>

<script>

$(document).ready(function(){

    $('#profile_form').parsley();

    $('#user_profile').change(function(){
        var extension = $('#user_profile').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Invalid Image File");
                $('#user_profile').val('');
                return false;
            }
        }
    });

});

</script>