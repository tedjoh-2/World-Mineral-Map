
<?php
	if(isset($_COOKIE['username']) && isset($_COOKIE['username'])){
		$username = $_COOKIE['username'];
		$password = $_COOKIE['password'];
	}
	





?>



<html lang="en">
<head>
<meta charset="utf-8" \>
<title> Mineral world map </title>
<meta name="viewport" content="width=device-width, (initial-scale=1.0")>

<link rel="stylesheet" type="text/css" href="css.css"> 

</head>

<body>


<table cellpadding="5" cellspacing="10" align="center">
	<center><h3>Login</h3></center>
	<form method="post" action="validate.php">
		<tr><th>Username</th><td><input type="text" id="username" name="Username"></td></tr>
		<tr><th>Password</th><td><input type="Password" id="password" name="Password"></td></tr>
		<tr><td colspan="2" align="right"><input type="submit" name="Login"></td></tr>
		<tr><td colspan="2" align="center"><input type="checkbox" name="remember"></td></tr>


</body>
</html>