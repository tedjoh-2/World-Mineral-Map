<?php

$conn = mysqli_connect('localhost','root','','WMM');

if(!$conn){
	die("Could not connect");
}
return $conn;
