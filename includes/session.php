<?php

class Session {
    
    public $user_id;
    public $name;
    public $username;
    public $email;
    // logged_in is made private so that it can't be changed out of class
    private $logged_in = false;
    public $message;
    
    function __construct(){
        session_start();
        $this->storeMsg();
        $this->check_login();
    }
    
    public function check_login(){
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->name = $_SESSION['name'];
            $this->username = $_SESSION['username'];
            $this->email = $_SESSION['email'];
            $this->logged_in = true;
		}
    }
    
    public function msg($message){
    		$this->message = $_SESSION['message'] = $message;
    }
    public function storeMsg(){
    	if(isset($_SESSION['message'])) {
    		$this->message = $_SESSION['message'];
    	}
    } 
    
    public function login($user){
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['name'] = $user->name;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
    }
    
    public function logout(){
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = false;
    }
    
    public function is_logged_in() {
        return $this->logged_in;
    }
    
}

$session = new Session();
