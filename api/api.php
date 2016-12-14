<?php
session_start();
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

	//	session_start(); //Session start, not neccessarily with an id, only after login.
	//	private $_SESSION['id']; //set in login.

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

		function removeMagicQuotes() {
			if ( get_magic_quotes_gpc() ) {
				$_GET    = stripSlashesDeep($_GET   );
				$_POST   = stripSlashesDeep($_POST  );
				$_COOKIE = stripSlashesDeep($_COOKIE);
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
			$username = $_REQUEST['var1'];
			$password = $_REQUEST['var2'];
			// Input validations
			//echo "$username";
			//echo "$password";
			//if(isset($username)
			if(!empty($username) && !empty($password)){
				//$sql = mysql_query("SELECT id, username  FROM users WHERE username = '$username' AND password = '$password' LIMIT 1", $this->db);
				$sql = mysql_query("SELECT * FROM `logins` WHERE username = '$username' AND password = '$password'", $this->db);

				if($row = mysql_num_rows($sql) == 1){
					$result = mysql_fetch_array($sql,MYSQL_ASSOC);
					//echo json_encode("welcome " . $result['id']);
					$_SESSION['id'] = $result['id'];
					$id = $_SESSION['id'];
					// If success everythig is good send header as "OK" and user details
					$this->response($this->json($result), 200);
				}
				$this->response("wrong input", 204);	// If no records "No Content" status

			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}

		private function register(){

			$username = $_REQUEST['var1'];
			$password = $_REQUEST['var2'];
			if(!empty($username) && !empty($password)){
				
				$sql = "SELECT * FROM 'logins' WHERE username = '$username'";
				$res = mysql_query($sql);
				//$this->response($this->json($username), 200);

				if($res && mysql_num_rows($res)>0){
					$this->response("Username allready taken", 204);
				}

				$sql = mysql_query("INSERT INTO `logins`(`username`,`password`) VALUES ('$username','$password')",$this->db);

				
				$sql2 = mysql_query("SELECT * FROM `logins` WHERE username = '$username' AND password = '$password'", $this->db);
				if($row = mysql_num_rows($sql2) == 1){
					$result = mysql_fetch_array($sql2,MYSQL_ASSOC);
					//echo json_encode("welcome " . $result['id']);
					$_SESSION['id'] = $result['id'];
					// If success everythig is good send header as "OK" and user details
					$this->response($this->json($result), 200);


				}
				

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
			$sql = "SELECT `latitude`,`longitude` FROM `maps` WHERE '1'";
			$res = mysql_query($sql);
			$result = array();
			if($row = mysql_num_rows($res) > 0){
				while($fetch = mysql_fetch_array($res)){
					//result_push("{lat: " . $fetch['latitude'] . ", lng: " . $fetch['longitude'] . "}");
					$result[] = $fetch['latitude'] . "," . $fetch['longitude'];
					//echo json_encode($result);
				}
				echo json_encode($result);
				exit;
			}
			echo json_encode("Wrong input");
		}

		private function getMap(){ // man kommer alltid logga in först, för att få session och id.

			$map = $_REQUEST['var1'];
			$id = $_SESSION['id'];
			$control = "SELECT * FROM `logins` WHERE id = '$id'";
			$query = mysql_query($control);
			$result = "";
			if(isset($map) && isset($query)){ //SESSION ID BEHÖVS HÄR EGENTLIGEN.
				$sql = "SELECT `id`, `name`, `longitude`, `latitude` FROM `personalmaps` WHERE name = '$map' AND id = '$id'";
				$res = mysql_query($sql);
				if($row = mysql_num_rows($res) > 0){
					while($fetch = mysql_fetch_array($res)){
						$result[] = $fetch['latitude'] . "," . $fetch['longitude'];
						//echo json_encode($result);
					}
					echo json_encode($result);
					exit;
				}else{
					$this->response("wrong result", 204); //return nothing, wrong input.
				}
			}else{
				$this->response("return nothing", 400); //return nothing.
			}
		}

		private function insertMap(){

			$map = $_REQUEST['var1'];
			$x = $_REQUEST['var2'];
			$y = $_REQUEST['var3'];
			$id = $_SESSION['id'];
			$control = "SELECT * FROM `logins` WHERE id = '$id'";
			$query = mysql_query($control);
			if(isset($map) && isset($x) && isset($y) && isset($query)){
				
				$ifirst = "INSERT INTO `maps`(`longitude`,`latitude`) VALUES ('$x','$y')";
				$sqlone = mysql_query($ifirst);
				
				$isecond = "INSERT INTO `personalmaps`(`id`,`name`,`longitude`,`latitude`) VALUES ('$id', '$map','$x','$y')"; 
				$sqltwo = mysql_query($isecond);
				
				if(isset($sqlone) && isset($sqltwo)){

					$sql = "SELECT * FROM `personalmaps` WHERE id = '$id' AND name = '$map'";
					$res = mysql_query($sql);
					while($fetch = mysql_fetch_assoc($res)){

						$result[] = $fetch['latitude'] . "," . $fetch['longitude'];
					}
					echo json_encode($result);
					exit;
				}else{
					$this->response($id, 204); //nothing
				}
			}else{
				$this->response("Wrong input", 400);
			}
		}

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
			echo json_encode("session destroyed");
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>
