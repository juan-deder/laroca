<?php
include("php/data.php");
include("php/logging.php");
include("php/crud.php");
if(isset($_POST['table']) && $_POST['table'] == 'servi'){
	if($_POST['action'] == 'update'){
		if($_POST['dates'] == 'si'){
			if($conn->query("select * from dates$_POST[id]") == null)
				$conn->query("create table dates$_POST[id] (id int primary key auto_increment, dateFrom date, days int, nights int, price varchar(20), hotel varchar(20) default 'No aplica')");
		}else{
			if($conn->query("select * from dates$_POST[id]") != null) $conn->query("drop table dates$_POST[id]");
		}
	}
	if($_POST['action'] == 'insert'){
    $id = $conn->query("select * from servi where name = '$_POST[name]' and cate = $_POST[cate] and descri = '$_POST[descri]' and dates = '$_POST[dates]'")->fetch_assoc();
		$conn->query("create table images$id[id] (id int primary key auto_increment, image longblob)");
		$conn->query("create table includes$id[id] (id int primary key auto_increment, included varchar(1000))");
		if($_POST['dates'] == 'si') $conn->query("create table dates$id[id] (id int primary key auto_increment, dateFrom date, days int, nights int, price varchar(20), hotel varchar(20) default 'No aplica')");
	}else if(isset($_POST['eli'])){
		$conn->query("drop table images$_POST[eli]");
		$conn->query("drop table dates$_POST[eli]");
		$conn->query("drop table includes$_POST[eli]");
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://use.fontawesome.com/a7d3dc65fd.js"></script>
<title>LA ROCA | Servicios</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body onload='checkSize()' onscroll="setServiNav()" onresize='checkSize()'>
	<?php include("pageBodyParts/header.php"); ?>
	<nav>
		<div class="navBut"><a href="index.php">Inicio</a></div>
		<div class="serviA active navBut"><a href='#' onclick='dropServi()'>Servicios</a>
			<div class="serviNavCont">
				<?php
					$result = $conn->query("select * from categ");
					while($row = $result->fetch_assoc()){
						echo "<div class='serviNavCate' onclick='popServi(\"list=$row[id]\")'>$row[name]<ul class='serviNavUl'>";
						$result2 = $conn->query("select id, name from servi where cate = $row[id]");
						while($row2 = $result2->fetch_assoc()){
							echo "<li class='serviNavLiChild serviNavLi' onclick='popServi(\"id=$row2[id]\")'><i class='fa fa-caret-right'></i> $row2[name]</li>";
						}
						echo "</ul></div>";
					}
					$result = $conn->query("select id, name from servi where cate is null");
					while($row = $result->fetch_assoc()){
						echo "<div class='serviNavCate serviNavLi' onclick='popServi(\"id=$row[id]\")'>$row[name]</div>";
					}
				?>
			</div>
		</div>
	</nav>
	<section>
		<div id="serviSpace">
			<div class="serviNavUp" id="serviNav">
			<h1 class="articleHeading" onclick="window.location.href='servi.php'"><i><i class="fa fa-tags"></i> Servicios</i></h1>
			 	<?php
			 		$result = $conn->query("select * from categ");
			 		while($row = $result->fetch_assoc()){
			 			echo "<div class='serviNavCateg' onclick='popServi(\"list=$row[id]\")'>$row[name]<ul class='serviNavUl'>";
			 			$result2 = $conn->query("select id, name from servi where cate = $row[id]");
			 			while($row2 = $result2->fetch_assoc()){
			 				echo "<li onclick='popServi(\"id=$row2[id]\")'>$row2[name]</li>";
			 			}
			 			echo "</ul></div>";
			 		}
			 		$result = $conn->query("select id, name from servi where cate is null");
					while($row = $result->fetch_assoc()){
						echo "<div class='serviNavCateg'><li onclick='popServi(\"id=$row[id]\")'>$row[name]</li></div>";
					}
			 	?>
			</div>
			<div id="serviCont">
				<?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){ ?>
				<div class="managementForm">
					<fieldset>
						<legend>Categorías</legend>
						<form method="post">
							<label for="name">Nombre: </label>
							<input type="text" name="name" <?php if(isset($_POST['edi']) && $_POST['table'] == 'categ') echo "value='$ediInfo[1]'" ?> required><br>
							<input type="hidden" name="table" value="categ">
							<input type="hidden" name="action" value="<?php if(isset($_POST['edi']) && $_POST['table'] == 'categ') echo 'update'; else echo 'insert'; ?>">
							<?php if(isset($_POST['edi']) && $_POST['table'] == 'categ') echo "<input type='hidden' name='id' value='$_POST[edi]'>"; ?>
							<input type="submit" <?php if(isset($_POST['edi']) && $_POST['table'] == 'categ') echo "value='Actualizar'"; else echo "value='Insertar'"; ?>>
						</form>
						<form method="post">
							<table border="1" class="adminTable">
								<tr>
									<th>Nombre</th>
									<th>Eliminar</th>
									<th>Editar</th>
								</tr>
								<?php
									$selectPromosResult = $conn->query("select * from categ");
									while($row = $selectPromosResult->fetch_assoc()){
										echo "<tr><td>$row[name]</td>
										<td><button name='eli' value=$row[id]>Eliminar</button></td>
										<td><button name='edi' value=$row[id]>Editar</button></td></tr>";
									}
								?>
							</table>
							<input type="hidden" name="table" value="categ">
							<input type="hidden" name="action" value="delOrUp">
						</form>
					</fieldset>
				</div>
				<div class="managementForm">
					<fieldset>
						<legend>Servicios</legend>
						<form method="post" id="promForm" enctype="multipart/form-data">
							<label for="name">Nombre: </label>
							<input type="text" name="name" <?php if(isset($_POST['edi']) && $_POST['table'] == 'servi') echo "value='$ediInfo[1]'" ?> required><br>
							<label for="cate">Categoría: </label>
							<select name="cate">
								<option value="">Ninguna</option>
								<?php
									$result = $conn->query("select * from categ");
									while($row = $result->fetch_assoc()){
										echo "<option value='$row[id]'";
										if(isset($_POST['edi']) && $_POST['table'] == 'servi' && $ediInfo[2] == $row['id']) echo 'selected';
										echo ">$row[name]</option>";
									}
								?>
							</select><br>
							<label for="descri">Descripción: </label>
							<textarea type="text" name="descri" maxlength="10000">
								<?php if(isset($_POST['edi']) && $_POST['table'] == 'servi') echo "$ediInfo[3]" ?>
							</textarea><br>
							<label for="dates">Tabla con fechas y precios:</label>
							<input type="radio" name="dates" value="si" <?php if(isset($_POST['edi']) && $_POST['table'] == 'servi' && $ediInfo[4] == 'si' || !isset($ediInfo)) echo 'checked' ?>> Sí
							<input type="radio" name="dates" value="no" <?php if(isset($_POST['edi']) && $_POST['table'] == 'servi' && $ediInfo[4] == 'no' || !isset($ediInfo)) echo 'checked' ?>> No<br>
							<input type="hidden" name="table" value="servi">
							<input type="hidden" name="action" value="<?php if(isset($_POST['edi']) && $_POST['table'] == 'servi') echo 'update'; else echo 'insert'; ?>">
							<?php if(isset($_POST['edi']) && $_POST['table'] == 'servi') echo "<input type='hidden' name='id' value='$_POST[edi]'>"; ?>
							<input type="submit" <?php if(isset($_POST['edi']) && $_POST['table'] == 'servi') echo "value='Actualizar'"; else echo "value='Insertar'"; ?>>
						</form>
						<form method="post">
							<input type="hidden" name="table" value="servi">
							<input type="hidden" name="action" value="delOrUp">
							<table border="1" class="adminTable">
								<tr>
									<th>Nombre</th>
									<th>Categoría</th>
									<th>Descripción</th>
									<th>Tabla de fechas</th>
									<th>Eliminar</th>
									<th>Editar</th>
								</tr>
								<?php
									$selectPromosResult = $conn->query("select * from servi");
									while($row = $selectPromosResult->fetch_assoc()){
										$row2 = $conn->query("select name from categ where id = '$row[cate]'")->fetch_assoc();
										echo "<tr><td>$row[name]</td><td>$row2[name]</td><td id='longDescri'><div>$row[descri]</div></td><td>$row[dates]</td><td><button name='eli' value=$row[id]>Eliminar</button></td><td><button name='edi' value=$row[id]>Editar</button></td></tr>";
									}
								?>
							</table>
						</form>
					</fieldset>
				</div>
				<div class="managementForm">
					<fieldset>
						<legend>Servicio</legend>
						Selecciona un servicio para editar su contenido:
						<select onchange="popServi('id='+this.value+'&admin=1')">
							<option value=""></option>
							<?php
								$result = $conn->query("select id, name from servi");
								while($row = $result->fetch_assoc()){
									echo "<option value='$row[id]'";
									if(isset($_POST['table']) && strpos($_POST['table'], $row['id']) !== false) echo 'selected';
									echo ">$row[name]</option>";
								}
							?>
						</select>
						<div id="selectedService"></div>
					</fieldset>
				</div>
				<?php } ?>
				<div id="services" class="<?php if(isset($_GET['id'])) echo 'onServi' ?>">
					<?php
						if(isset($_GET['id'])){
							include("services.php");
						}
					?>
				</div>
			</div>
		</div>
	</section>
	<footer>
		<div id="footerTopDiv">
			La roca turismo y eventos <i class="fa fa-registered"></i>&nbsp&nbsp&nbspNúmero de contacto &nbsp&nbsp<i class="fa fa-mobile"></i> Celular: 310 469 2182 &nbsp&nbsp<i class="fa fa-phone"></i> Teléfono: 304 16 07
		</div>
	</footer>
	<script>
		function popServi(vars){
			var id2 = vars + '';
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					if(id2.search('admin') > -1) document.getElementById('selectedService').innerHTML = xmlhttp.responseText;
					else{
						document.getElementById('services').innerHTML = xmlhttp.responseText;
						document.getElementById('services').setAttribute("class", 'onServi');
					}
				}
			};
			xmlhttp.open('GET', 'services.php?' + vars, true);
			xmlhttp.send();
		}
	</script>
	<script src='js/scrolling.js'></script>
	<?php
		if(isset($_POST['table'])){
			if(!strpos($_POST['table'], 'images') || !strpos($_POST['table'], 'dates')){
				if(strpos($_POST['table'], 'images') !== false) $table = 'images';
				else if(strpos($_POST['table'], 'dates') !== false) $table = 'dates';
				else if(strpos($_POST['table'], 'includes') !== false) $table = 'includes';
				if(isset($table)){
					$id = substr($_POST['table'], strlen($table), strlen($_POST['table'])-strlen($table));
					if(isset($_POST['edi'])) echo "<script> popServi('id=$id&admin=1&edi=$_POST[edi]&table=$_POST[table]')</script>";
					else echo "<script>popServi('id=$id&admin=1')</script>";
				}
			}
		}
		if(!isset($_GET['id'])){
			if(isset($_GET['lid']))	echo "<script>popServi('list=$_GET[lid]')</script>";
			else echo "<script>popServi('list=1&all=1')</script>";
		}
	?>
</body>
<script src='js/timeSlider.js'></script>
<script src='js/userSystem.js'></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</html>
