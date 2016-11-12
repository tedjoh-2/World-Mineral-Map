<?php
	$myUsername;
	$myPassword;

	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		

		if($username == $myUsername && $password==$myPassword){
			
			if(isset($_POST['remember'])){
				$remeber = $_POST['remember'];
				setcookie('username', $username, time()+60*60*24*7);
			}
			session_start();
			$_SESSION['username'] = $username;
			header("location: myAccount.php");
		}
		else{
			echo "Username or password is wrong."
		}

	}
	else{
		header("location: login.php");
	}
?>