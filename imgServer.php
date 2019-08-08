<?php

	header('Content-type:text/html;charset=utf-8');
	
	$db_host = 'localhost';
	$db_database_name = 'medical_network_db';
	$db_username = 'root';
	$db_password = '';
	
	if(!isset($_POST['table']) || !isset( $_POST['id'] ) ){
		echo 'table or id not specified!';
		exit();
	}
		
	$table = $_POST['table'];
	$id = $_POST['id'];
	
	$encoded_string = $_POST['encoded_string'];
	$decoded_string = base64_decode($encoded_string);
	$image_name = $_POST['image_name'];
	
	
	$path = "images/$image_name";
	$file = fopen($path, 'wb');
	fwrite($file, $decoded_string);
	fclose($file);
	
	$con = mysqli_connect($db_host, $db_username, $db_password, $db_database_name);
	if(!(mysqli_query($con, "update $table set image = '$path' where id = $id ;"))){
		echo " --mysql error while updating image for $table , id = $id--";
		exit();
	}
	
	
	echo $path;
	
	
?>