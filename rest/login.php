<?php
$db = include('connect.php');

$username = $_POST['username'];
$password = $_POST['password'];

if(isset($_SESSION['id'])){
	header("Location: http://130.240.170.61/login.html");
}else if($db){
	$sql = "SELECT * FROM `login` WHERE Username = '$username' AND Password = '$password'";
	$query = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($query);
	if($row){
		session_start();
		$_SESSION['username'] = $row["Username"];
		$_SESSION['id'] = $row["id"];
		//echo $_SESSION['username'];
		//echo " - ";
		//echo $_SESSION['id'];
		header("Location: http://130.240.170.61/index.html");
	}
	else{
		echo "You didn't enter the correct details.\r\n";
		echo "$username - $password";
	}
}else{
	echo "Connection failed";
}
?>
