<?php
	ini_set('mysql.connect_timeout',300);
	ini_set('default_socket_timeout',300);
?>
<html>
<head>
    <meta content = "text/html;charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">



    <link rel = "stylesheet" type = "text/css" href = "css.css">


</head>
<body>

<nav class="navbar navbar-default navbar-fnt navbar-backgrnd navbar-full" id="nav-main">
                <ul class="nav navbar-nav">
                        <li class="nav-item">
                                <a class="nav-link" href="index.html">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href="login.html"> Login</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href="register.html"> Register</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href="images.php"> Upload</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href="geomapCluster.html"> My map</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href=""> Mineral info</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href=""> About</a>
                        </li>
                        <li class="nav-item">
                                <a class="navbar-brand" href="logout.html"> Logout</a>
                        </li>
                    </ul>
  </nav>

	<center>
	<form class="form-inline" enctype="multipart/form-data" method="post">
		<br/>
		<input type="file" name="image" class="btn btn-success btn-lg">
		<br/><br/>
		<input type="submit" name="submit" value="Upload" class="btn btn-success btn-lg">
	</form>
	</center>
<?php
		if(isset($_POST['submit'])){
			if(getimagesize($_FILES['image']['tmp_name'])==false){
				echo "please choose an image";
			}else{
				$image = addslashes($_FILES['image']['tmp_name']);
				$name = addslashes($_FILES['image']['name']);
				$image = file_get_contents($image);
				$image = base64_encode($image);
				saveimage($name,$image);
			}
		}
		displayimage();
		function saveimage($name, $image){
			$conn=mysql_connect("localhost","root","");
			mysql_select_db("WMM",$conn);
			$sql = "INSERT INTO images (name,image) VALUES ('$name','$image')";
			$result = mysql_query($sql,$conn);
			if($result){
				echo "<br/> Image uploaded.";
			}else{
				echo "<br/> Image not uploaded.";
			}
		}
		function displayimage(){
			$conn = mysql_connect("localhost","root","");
			mysql_select_db("WMM",$conn);
			$sql = "SELECT * FROM images WHERE '1'";
			$result = mysql_query($sql,$conn);
			while($row = mysql_fetch_array($result)){
				echo '<img height="300" width="300" src="data:image;base64,'.$row[2].'">';
			}
			mysql_close($conn);
		}
?>

</body>

</html>

