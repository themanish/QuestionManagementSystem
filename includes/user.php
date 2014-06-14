<?php
require_once 'database.php';

class User {
    
    public $user_id;
    public $name;
    public $username;
    public $password;

    public static function authenticate($username=NULL, $password=NULL) {
        global $db;
        $object_array = array();
        
        $username = $db->escape_value($username);
        $password = $db->escape_value($password);

        $sql = "SELECT * FROM `users` ";
        $sql .= "WHERE `username` = '{$username}' AND `password` = '{$password}'";
        $result = $db->query($sql);
        
        while($row = $db->fetch_array($result)){
            // Here the array $row of [field] -> [value] is parsed through instantiate method
            // This way the array is now converted to an object
            // Now this is a fully object oriented concept
            $object_array[] = self::instantiate($row);
        } 
        
        // Here array_shift function gives the first record of that object_array
        return !empty($object_array) ? array_shift($object_array) : false;
    }
    
    private static function instantiate($record){
        // object variable is re-assigned with the class 
        // To return the data of user through $object
        $object = new self;
        $object->user_id 	= $record['user_id'];
        $object->name       = $record['name'];
        $object->username	= $record['username'];
        $object->password 	= $record['password'];
        $object->email      = $record['email'];
        $object->status     = $record['status'];
        return $object;
    }
    
}