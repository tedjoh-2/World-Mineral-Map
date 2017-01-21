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
<script src="http://www.w3schools.com/lib/w3data.js"></script>

</head>
<body>
<div w3-include-html="overhead.html"></div>

<script>
w3IncludeHTML();
</script>
<div class="container" id = "form">
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
			echo "<table border = '1'>
			<tr>
			<th> id </th>
			<th> image </th>
			</tr>";
			while($row = mysql_fetch_array($result)){
				echo "<tr>";
				echo "<td>" . $row['id'] . "</td>";
				echo "<td>" . '<img height="300" width="300" src="data:image;base64,'.$row[2].'">'. "</td>";
			}
			echo "</table>";
			mysql_close($conn);
		}
?>
</div>
</body>
</html>

