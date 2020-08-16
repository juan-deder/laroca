<?php
include("php/data.php");
session_start();
$fieldsName = [0=>"", 1=>""];
if($_POST){
	$good = true;
	$fields = [0=>htmlspecialchars($_POST['username']), 1=>htmlspecialchars($_POST['email']), 2=>htmlspecialChars($_POST['password'])];
	$sel = "SELECT username FROM users WHERE username='$fields[0]'";
	$selQuery = $conn->query($sel);
	$row = $selQuery->fetch_assoc();
	if(!empty($fields[0]) && $row['username'] == $fields[0]){
		$fieldsName[0] = $fieldsName[0]."<span id=username>Nombre no disponible</span>";
		$good = false;
	}
	$sel2 = "SELECT email FROM users WHERE email='$fields[1]'";
	$selQuery2 = $conn->query($sel2);
	$row2 = $selQuery2->fetch_assoc();
	if(!empty($fields[1]) && $row2['email'] == $fields[1]){
		$fieldsName[1] = $fieldsName[1]."<span id=email>Email no disponible</span>";
		$good = false;
	}
	if($good){
		$insert = "INSERT INTO users(username, password, email) VALUES ('$fields[0]', '$fields[2]', '$fields[1]')";
		$insertQuery = $conn->query($insert);
	}
}
?>
