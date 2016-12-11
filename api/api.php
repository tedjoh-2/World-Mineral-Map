<?php
    
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		
		CREATE TABLE IF NOT EXISTS `users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `user_email` varchar(50) NOT NULL,
		  `user_password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		private $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "WMM";
		
		public $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		public function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db){
				mysql_select_db(self::DB,$this->db);
			}
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			
		
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0){
				$this->$func();
			}else{
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
			}
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		//$_SESSION['id'];
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			//if($this->get_request_method() != "POST"){
			//	$this->response('',406);
			//}
			$username =$_REQUEST['var1'];
			$password =$_POST['password'];
			// Input validations
			echo "$username";
			echo "$password";
			//if(isset($username)
			if(!empty($username) && !empty($password)){
				//$sql = mysql_query("SELECT id, username  FROM users WHERE username = '$username' AND password = '$password' LIMIT 1", $this->db);
				$sql = mysql_query("SELECT * FROM `logins` WHERE username = '$username' AND password = '$password'", $this->db);
				//$result = mysql_query($sql, $this);
				if($row = mysql_num_rows($sql) == 1){
					session_start();
					$result = mysql_fetch_array($sql,MYSQL_ASSOC);
					//echo json_encode("welcome " . $result['id']);
					$_SESSION['id'] = $result['id'];
					// If success everythig is good send header as "OK" and user details
					$this->response($this->json("Welcome " . $_SESSION['id'] . "."), 200);
				}
				$this->response('', 204);	// If no records "No Content" status

			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		
		
		private function users(){
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function deleteUser(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				mysql_query("DELETE FROM users WHERE user_id = $id");
				$success = array('status' => "Success", "msg" => "Successfully one record deleted.");
				$this->response($this->json($success),200);
			}else{
				$this->response('',204);	// If no records "No Content" status
			}
		}
		
		private function getUser(){
			if($this->get_request_method() != "GET"){
				$this->response('', 204);
			}
			$id = (int)$this->_REQUEST['id'];
		}
		
		private	function getAll(){
			$sql = "SELECT `longitude`, `latitude` FROM `maps` WHERE '1'";
			$res = mysql_query($sql);
			$types = array();
			while($row[] = mysql_fetch_assoc($res));
			array_pop($row);
			echo json_encode($row);
		}

		private function getMap(){ // man kommer alltid logga in först, för att få session och id.
			$map = $_REQUEST['mapname'];
			if(isset($map) && isset($_SESSION['id'])){ //SESSION ID BEHÖVS HÄR EGENTLIGEN.
				$sql = "SELECT `id`, `name`, `longitude`, `latitude` FROM `personalmaps` WHERE name = '$map'";
				$result = mysql_query($sql);
				$types = array();
				while($row[] = mysql_fetch_assoc($result));
				array_pop($row);
				echo json_encode($row);
			}else{
				echo json_encode("Wrong input");
			}
		}
/*
		private function insertMap(){
			$map = $_REQUEST['mapname'];
			$x = $_REQUEST['x'];
			$y = $_REQUEST['y'];
			if(isset($map) && isset($x) && isset($y) && isset($_SESSION['id'])){

				$ifirst = "INSERT INTO `maps`(`longitude`,`latitude`) VALUES ('$x','$y')";
				$isecond = "INSERT INTO `personalmaps`(`id`,`name`,`longitude`,`latitude`) VALUES ('$_SESSION['id']', '$map','$x','$y')"; 
				if(isset($ifirst) && isset($isecond)){

					$sql = "SELECT * FROM `personalmaps` WHERE id = '$_SESSION['id']' AND name = '$map'";
					$result = mysql_query($sql);
					$types = array();

					while($row[] = mysql_fetch_assoc($result));
					//array_pop();
					echo json_encode($row);
				}
			}else{
				echo json_encode("Something is wrong with login or the specified map");
			}
		}
*/
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
		
		private function logout(){
			session_destroy();
			return json_encode("session destroyed");
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>
