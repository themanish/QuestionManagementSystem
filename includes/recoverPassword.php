<?php

require_once 'database.php';
require_once 'functions.php';
require_once 'session.php';

if(isset($_GET['recoveryCode'])){
    
	//echo intval($_GET['recoveryCode']);exit;
    $db->tbl_name = 'users';
    $result = $db->fetch_row($db->selectById('recoveryCode', intval($_GET['recoveryCode'])));
    
    if(!$result){
    	$_SESSION['msg'] = 'Invalid Attempt!';
    	redirect('../public/index.php?recoverPassword=1');
    } else {
    	$_SESSION['recoveryCode'] = intval($_GET['recoveryCode']);
    	$rc = intval($_GET['recoveryCode']);
    	redirect('../public/index.php?recoverPassword=1&recoveryCode=$rc');
    }
    
    // Now generate a form to insert new password
    
}

if(isset($_GET['email'])){

	$email = $_GET['email'];
	
	$db->tbl_name = 'users';
	$result = $db->fetch_row($db->selectById('email',$email));
	if(!$result){
	    $message = 'The email you typed is not recognized. Please Try Again!';
	    redirect("../public/index.php?message=$message");
	} else {
	    
		$stamp = strtotime("now");
		$recoveryCode = $stamp - $_SERVER['REMOTE_ADDR'];
		
		$db->tbl_name = 'users';
	    $db->data = array('recoveryCode' => $recoveryCode);
	    $db->condition = array('email' => $email);
	    $db->update();
	    
	    $recoveryLink = "http://localhost/qms/includes/recoverPassword.php?recoveryCode=$recoveryCode";
	    
	    $to = $email;
	    $subject = "QMS Password Recovery";
	    $message = "Recover Your Password: $recoveryLink";
	    
	    if(!mail($to, $subject, $message)){
	    	$message = "Error occured: SMTP Server Failure. Please Try Later!";
	    	redirect("../public/index.php?message=$message");
	    } else {
	    	$message = "<span class='green-text'>Recovery Link sent to your mailbox. Please Confirm it. Thank you!</span>";
	    	redirect("../public/index.php?message=$message");
	    }
	    
	}
}

if(isset($_POST['changePassword'])){
	$password = sha1($_POST['password']);
	$db->tbl_name = 'users';
	$db->data = array('password' => $password);
	$db->condition = array('recoveryCode' => $_SESSION['recoveryCode']);
	$db->update();
	redirect('../public/index.php?message=Login using New Password'); 
}

