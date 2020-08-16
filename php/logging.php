<?php session_start();
if(isset($_POST['logIn'])){
	$good = true;
	$errorMessages = array("", "");
	if(empty($_POST['username'])){
		$good = false;
		$errorMessages[0] = "placeholder='Nombre de usuario requerido'";
	}else{
		$row = $conn->query("select username, password, isNotAdmin from users where username = '$_POST[username]'")->fetch_assoc();
		if($row['username'] != $_POST['username']){
		 	$errorMessages[0] = "placeholder='El usuario ingresado no existe'";
		 	$good = false;
	 	}
	}
	if(empty($_POST['password'])){
		$errorMessages[1] = "placeholder='Contraseña requerida'";
	}else{
		if($good){
			if($_POST['password'] != $row['password']) $errorMessages[1] = "placeholder='La contraseña está incorrecta'";
			else{ 
				$_SESSION['username'] = $row['username'];
				if(!$row['isNotAdmin']) $_SESSION['isAdmin'] = true;
			}
		}
	}
}
if(isset($_POST['logOut'])) session_unset();