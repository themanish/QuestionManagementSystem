<?php
   require_once '../includes/user.php';
   require_once '../includes/session.php';

   if(isset($_GET['message'])){$message = $_GET['message'];}
   
   if(isset($session)){
       if($session->is_logged_in()){
            if($session->user_id == 1){
                 header('Location: admin/index.php');
            } else {
                 header('Location: teacher/index.php');
            }
       }
   }
   if(isset($_GET['logout'])) { 
       $session->logout();
       if(isset($_GET['message'])){
           header("Location: index.php?message={$_GET['message']}");
       }
       else {
           header("Location: index.php");
       }
       
   }
   
   if(isset($_POST['submit'])){
       $found_user = User::authenticate($_POST['username'], sha1($_POST['password']));
       
       if($found_user){
            if($found_user->status == 1){
                 // Here session class's login method is assigned
                 $session->login($found_user);
                 if($session->user_id == 1){
                     header('Location: admin/index.php');
                 } else {
                     header('Location: teacher/index.php');
                 }
            } else {
                $message = "Account Disabled! Contact Administrator";
            }
       } else {
           $message = "Username or Password Incorrect!";
       }
   }
   
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Question Management System</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery-2.0.2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    
    //open popup
    $("#pop").click(function(){
        $("#modal").fadeIn(500);
        $("body").addClass("dialogIsOpen");
        positionPopup();
    });

    //close popup
    $("#close").click(function(){
        $("#modal").fadeOut(500);
        $("body").removeClass("dialogIsOpen");
    });
    });

    // Display the popup in center
    function positionPopup(){
        if(!$("#modal").is(':visible')){ return; }
        $("#modal").css({
            left: ($(window).width() - $('#modal').width()) / 2,
            top: ($(window).width() - $('#modal').width()) / 7,
            position:'absolute'
        });
    }
    </script>    
</head>

<body id="login">

<div class="container" id="login-page">
    <div class="row" id="login-header"></div>
	<div class="row" id="login-row">
		<div class="span4">
			<div id="login-logo"></div>
			<div id="login-box">
            <?php if(isset($_GET['recoverPassword'])){?>
                <form action="../includes/recoverPassword.php" method="POST">
                <?php if(isset($_SESSION['msg'])){ ?>
                <p>
                    <div class="alert-error-box"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
                </p>
                <?php } else {?>
                <p>
                    <label class="ver-label">New Password</label>
                    <input type="password" name="password" required>
                </p>
                <p>
                    <input type="submit" name="changePassword" class="btn" value="Change Password">
                </p>
                </form>
            <?php } ?>
            <?php } else { ?>
                <form action="" method="post">
                    <?php if(isset($message)){ ?>
                    <p>
                        <div class="alert-error-box"><?php echo $message; ?></div>
                    </p>
                    <?php } ?>
                    <p>
                        <label for="username" class="ver-label">Username</label>
                        <input type="text" name="username" required>
                        <label for="password" class="ver-label">Password</label>
                        <input type="password" name="password" required>
                    </p>
                    <p>
                        <input type="submit" name="submit" class="btn full-btn" value="Login">
                    </p>
                    <p align="center">
                        <a href="#" id="pop">Forgot Password?</a>
                    </p>
               </form>
            <?php } ?>
			</div>
		</div>
        
	</div>
    <div class="row" id="login-footer">
        <p>Copyright &copy; 2013 : <a href="http://smude.edu.in" target='_blank'>SMU</a> Mini Project</p>
        <p>Designed & Developed By: <a href="http://manishrestha.com.np" target='_blank'>Manish Shrestha</a> (Univ. Roll: 1208009629)</p>
    </div>
</div>
<div id="modal" style="display:none">
    <form action="../includes/recoverPassword.php" method="get" class="center">
         <h2 class="center">Recovery Email</h2>
         <input type="email" name="email" placeholder="Your Email Here..." required>
         <input type="submit" value="Request Password" class="btn"/>
         <input type="button" value="Cancel" id="close" class="btn">
    </form>
</div>
 
</body>
</html>
