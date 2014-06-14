<?php

require_once 'config.php';

class MySQL_db {

	private $connection;
    public $last_query;
    public $tbl_name;
    public $data;
    public $condition;
    public $echo_query;
    public $last_insert_id;
        
	// Variable for escape_value function
	private $real_escape_string_exists;
	private $magic_quotes_active;

	function MySQL_db() {
		$this->connect_select_db();

		// Checks for the PHP version >= v4.3.0 i.e. with mysql_real_escape_string function
		$this->real_escape_string_exists = function_exists('mysql_real_escape_string');
		// Checks if magic_quotes_gpc functionality is enabled or not
		$this->magic_quotes_active = get_magic_quotes_gpc();
	
	}

	public function connect_select_db() {
		$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
		if(!$this->connection){
			die("<b>Database connection failed: </b>".mysql_error());
		}else{
			$db_select = mysql_select_db(DB_NAME, $this->connection);
			if(!$db_select){
				die("<b>Database selection failed: </b>".mysql_error());
			}
		}
	}
	
	public function close_connection() {
		if(isset($this->connection)){
			mysql_close($this->connection);
			unset($this->connection);
		}
	}

	public function escape_value($value) {
		if($this->real_escape_string_exists){ // i.e. PHP version >= v4.3.0
			/* 
			Here if version is greater & also magic_quotes is enabled,
			THEN, first we need to remove slashes (/) i.e. added by magic_quotes,
			THEN ONLY, use escape_string function to remove unexpected threats.
			*/
			if($this->magic_quotes_active) {
				$value = stripslashes($value);
			}
			$value = mysql_real_escape_string($value);
		} else { // ie. PHP version < v4.3.0
			/*
			Here if version is not upgraded,
			THERE is NO escape_string function,
			AND ALSO, there might be magic_quotes DISABLED
			SO, we need to use addslashes function to avoid SQL Injection
			*/
			if(!$this->magic_quotes_active) {
				$value = addslashes($value);
			}
		}
		return $value;
	}
	
	public function escape_value_array($array){
    	foreach($array as $key => $value){
    	    $value = $this->escape_value($value);
    	    $array[$key] = $value;
    	}
    	return $array;
	}
        
    // MYSQL CRUD methods are defined here...
    
    public function query($sql){
        
        $this->last_query = $sql;
        $result = mysql_query($this->last_query);
        
        $this->last_insert_id = mysql_insert_id();
        
        if(!$result){
            echo "<b>Query Error : </b> ". mysql_error()."<br>";
            echo "<b>Last Query : </b>". $this->last_query;
            exit;
        }
        
        return $result;
    }
    
    public function insert(){
        $sql = "INSERT INTO `$this->tbl_name` SET ";
        foreach($this->data as $k=>$v){
            $array[$k]= "`$k`='$v'";
        }
        if(count($array > 0)){
            // Here implode is used to convert array into string
            $sql .= implode(", ",$array);
        } else {
            echo "<b>Wrong Query : </b>".die();
        }
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function update(){
        $sql = "UPDATE `$this->tbl_name` SET ";
        foreach($this->data as $k=>$v){
            $array1[$k] = "`$k`='$v'";
        }
        if(count($array1 > 0)){
        $sql .= implode(", ", $array1);
        } else {
            echo "<b>Wrong Query : </b>".die();
        }
        foreach($this->condition as $ke => $va){
            $array2[$ke] = "`$ke`='$va'";
        }
        if(count($array2 > 0)){
        $sql .= " WHERE ".implode(" AND ",$array2);
        } else {
            echo "<b>Wrong Query : </b>".die();
        }
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function delete(){
        $sql = "DELETE FROM `$this->tbl_name` ";
        foreach($this->condition as $ke => $va){
            $array[$ke] = "`$ke`='$va'";
        }
        if(count($array > 0)){
        $sql.= " WHERE ".implode(" AND ", $array);
        } else {
            echo "<b>Wrong Query : </b>".die();
        }
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function selectAll(){
        $sql = "SELECT * FROM `$this->tbl_name`";
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function selectById($field, $value){
        $sql = "SELECT * FROM `$this->tbl_name` WHERE `$field` = '$value'";
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function fetch_array($result_set){
        return mysql_fetch_array($result_set);
    }
    
    public function fetch_assoc($result_set){
        return mysql_fetch_assoc($result_set);
    }
    
    public function fetch_row($result_set){
        return mysql_fetch_row($result_set);
    }
    
    public function query_by_tbl_name($tbl_name){
        $sql = "SELECT * FROM `$tbl_name`";
        $result_set = $this->query($sql);
        return $result_set;
    }
    
    public function num_rows($result_set){
        return mysql_num_rows($result_set);
    }
    
    // Non-Generalized Functions
    public function getSubjectsByUserId($user_id){
        $sql = "SELECT `id`, `sub_name`, `sub_code` FROM `subjects` WHERE id IN (SELECT `sub_id` FROM `sub_teacher` WHERE `user_id` = '{$user_id}')";
        return $this->query($sql);
    }

    public function getFacSemIdByFacIdnSem($fac_id, $sem){
        $sql = "SELECT `id` FROM `fac_sem` WHERE `fac_id` = $fac_id AND `semester` = $sem";
        return $this->query($sql);
    }
    
    public function getFacNameByFacSemId($fac_sem_id){
        $sql = "SELECT `name` FROM `faculties` WHERE `id` = (SELECT `fac_id` FROM `fac_sem` WHERE `id` = $fac_sem_id )";
        return $this->query($sql);
    }
    
    public function getFacultyNameBySubId($sub_id){
        $sql = "SELECT name FROM faculties WHERE id = (SELECT fac_id FROM fac_sem WHERE id = (SELECT fac_sem_id FROM subjects WHERE id = $sub_id))";
        return $this->query($sql);
    }
    
    public function getSemesterBySubId($sub_id){
        $sql = "SELECT semester FROM fac_sem WHERE id = (SELECT fac_sem_id FROM subjects WHERE id = $sub_id)";
        return $this->query($sql);
    }
        
}

// Object of MySQL_db class
$db = new MySQL_db();