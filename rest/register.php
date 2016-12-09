<?php
$db = include('connect.php');

$username = $_POST['username'];
$password = $_POST['password'];
	//echo "$username - $password";
if($db){
	$check = "SELECT `Username`, `Password` FROM `login` WHERE Username = '$username' AND Password = '$password'";
	$result = mysqli_query($db, $check);
	$row = mysqli_fetch_assoc($result);
	if($row == 0){
		$sql = "INSERT INTO `login`(`Username`, `Password`, `Token`) VALUES ('$username','$password','')";
		if(mysqli_query($db,$sql)){
//		echo "$username and $password after insertion";
		header("Location: http://130.240.170.61/login.html");
		}
	}else{
		header("Location: http://130.240.170.61/register.html");
	}
}
header("Location: http://130.240.170.61/index.html");
?>
