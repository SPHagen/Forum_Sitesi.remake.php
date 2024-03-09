<?php
	
	$host 		= "localhost";
	$dbname 	= "forum_sitesi_remake";
	$charset 	= "utf8";
	$root 		= "root";
	$password 	= "";

	try{
		$db = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset;", $root, $password);
	}catch(PDOExeption $error){
		echo $error->getMessage();
	}
?>
