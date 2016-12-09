<?php
	echo "some fucking shit";
	$sql = "SELECT * FROM logins WHERE 1";
	$result = mysqli_query($_dbHandle, $sql);//wrong, needs database connection.
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			echo "id: " . $row['id'] . " - Name : " . $row['username'] . " - password : " . $row['password'] . "<br>";
		}
	}else{
		echo "nada to return!";
	}
?>

