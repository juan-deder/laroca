<?php
include("php/data.php");
include("php/logging.php");
include("php/crud.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://use.fontawesome.com/a7d3dc65fd.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<title>LA ROCA | Inicio</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body onload='checkSize()' onresize='checkSize()'>
	<?php include("pageBodyParts/header.php"); ?>
	<nav>
		<div class="navBut active"><a href='#'>Inicio</a></div>
		<div class="navBut serviA"><a href="servi.php" onclick='dropServi()'>Servicios</a>
			<div class="serviNavCont">
				<?php
					$result = $conn->query("select * from categ");
					while($row = $result->fetch_assoc()){
						echo "<div  onclick=window.location.href='servi.php?lid=$row[id]' class='serviNavCate'>$row[name]<ul class='serviNavUl'>";
						$result2 = $conn->query("select id, name from servi where cate = $row[id]");
						while($row2 = $result2->fetch_assoc()){
							echo "<a href='servi.php?id=$row2[id]'><li class='serviNavLiChild serviNavLi'><i class='fa fa-caret-right'></i> $row2[name]</li></a>";
						}
						echo "</ul></div>";
					}
					$result = $conn->query("select id, name from servi where cate is null");
					while($row = $result->fetch_assoc()){
						echo "<a href='servi.php?id=$row[id]'><div class='serviNavCate serviNavLi'>$row[name]</div></a>";
					}
				?>
			</div>
		</div>
	</nav>
	<section>
		<div id='serviSpace'>
			<div class="serviNavUp notServiNav" id="serviNav">
				<h1 class="articleHeading" onclick="window.location.href='servi.php'"><i><i class="fa fa-tags"></i> Servicios</i></h1>
			 	<?php
			 		$result = $conn->query("select * from categ");
			 		while($row = $result->fetch_assoc()){
			 			echo "<div class='serviNavCateg'><span onclick=window.location.href='servi.php?lid=$row[id]'>$row[name]</span><ul class='serviNavUl'>";
			 			$result2 = $conn->query("select id, name from servi where cate = $row[id]");
			 			while($row2 = $result2->fetch_assoc()){
			 				echo "<li onclick=window.location.href='servi.php?id=$row2[id]'>$row2[name]</li>";
			 			}
			 			echo "</ul></div>";
			 		}
			 		$result = $conn->query("select id, name from servi where cate is null");
					while($row = $result->fetch_assoc()){
						echo "<div class='serviNavCateg'><li onclick=window.location.href='servi.php?id=$row[id]'>$row[name]</li></div>";
					}
			 	?>
			</div>
		</div>
		<h1 class="articleHeading"><i>¿A donde quieres iR?</i></h1>
		<?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){ ?>
		<div class="managementForm">
			<fieldset>
				<legend>Imagenes de la galaría</legend>
				<form method="post" enctype="multipart/form-data">
					<label for="image">Imagen: </label>
					<input type="file" name="image" required><br>
					<input type="hidden" name="table" value="images">
					<input type="hidden" name="action" value="insert">
					<input type="submit" value="Añadir">
				</form>
				<form method="post">
					<input type="hidden" name="table" value="images">
					<input type="hidden" name="action" value="delOrUp">
					<table border="1" class="adminTable">
						<tr>
							<th>Imagen</th>
							<th>Eliminar</th>
						</tr>
						<?php
							$selectImagesResult = $conn->query("select * from images");
							while($row = $selectImagesResult->fetch_assoc()){
								echo "<tr><td><img src='data:image;base64,$row[image]' width='50'></td><td><button name='eli' value='$row[id]'>Eliminar</button></td></tr>";
							}
						?>
					</table>
				</form>
			</fieldset>
		</div>
		<div class="managementForm">
			<fieldset>
				<legend>Promociones o productos Hot</legend>
				<form method="post" id="promForm" enctype="multipart/form-data">
					<label for="name">Título: </label>
					<input type="text" name="title" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[1]'" ?> required><br>
					<label for="sub">Subtítulo: </label>
					<input type="text" name="subtitle" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[2]'" ?>><br>
					<label for="dateFrom">Fecha: </label>
					<input type="date" name="dateFrom" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[3]'" ?>><br>
					<label for="dateTo">Días: </label>
					<input type="number" name="days" min="0" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[4]'" ?>><br>
					<label for="time">Noches: </label>
					<input type="number" name="nights" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[5]'" ?>><br>
					<label for="price">Precio: </label>
					<input type="text" name="price" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[6]'" ?>><br>
					<label for="finalPrice">Precio final: </label>
					<input type="text" name="finalPrice" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[7]'" ?>><br>
					<label for="promo">Promoción: </label>
					<input type="text" name="promo" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[8]'" ?>><br>
					<label for="priority">Prioridad: </label>
					<select name='priority'>
						<option value='Baja' <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') if($ediInfo[9] == 'Baja') echo 'selected';?>>Baja</option>
						<option value='Media' <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') if($ediInfo[9] == 'Media') echo 'selected';?>>Media</option>
						<option value='Alta' <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') if($ediInfo[9] == 'Alta') echo 'selected';?>>Alta</option>
					</select><br>
					<label for="aeroline">Aerolínea: </label>
					<input type="text" name="aeroline" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[10]'" ?>><br>
					<label for="hotel">Hotel: </label>
					<input type="text" name="hotel" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[11]'" ?>><br>
					<label for="placeFrom">Lugar de origen: </label>
					<input type="text" name="placeFrom" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[12]'" ?>><br>
					<label for="placeTo">Lugar de destino: </label>
					<input type="text" name="placeTo" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[13]'" ?>><br>
					<label for="link">Link/URL: </label>
					<input type="text" name="link" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='$ediInfo[14]'"; else echo 'value=#'?>><br>
					<label for="file">Imagen: </label>
					<input type="file" name="image" required><br>
					<input type="hidden" name="table" value="promTable">
					<input type="hidden" name="action" value="<?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo 'update'; else echo 'insert'; ?>">
					<?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "<input type='hidden' name='id' value='$_POST[edi]'>"; ?>
					<input type="submit" <?php if(isset($_POST['edi']) && $_POST['table'] == 'promTable') echo "value='Actualizar'"; else echo "value='Insertar'"; ?>>
				</form>
				<form method="post">
					<input type="hidden" name="table" value="promTable">
					<input type="hidden" name="action" value="delOrUp">
					<table border="1" class="adminTable">
						<tr>
							<th>Título</th>
							<th>Subtítulo</th>
							<th>Fecha</th>
							<th>Días</th>
							<th>Noches</th>
							<th>Precio</th>
							<th>Precio final</th>
							<th>Promoción</th>
							<th>Prioridad</th>
							<th>Aerolínea</th>
							<th>Hotel</th>
							<th>Lugar de origen</th>
							<th>Lugar de destino</th>
							<th>Link/URL</th>
							<th>Imagen</th>
							<th>Eliminar</th>
							<th>Editar</th>
						</tr>
						<?php
							$selectPromosResult = $conn->query("select * from promTable");
							while($row = $selectPromosResult->fetch_assoc()){
								echo "<tr><td>$row[title]</td>
											<td>$row[subtitle]</td>
											<td>$row[dateFrom]</td>
											<td>$row[days]</td>
											<td>$row[nights]</td>
											<td>$row[price]</td>
											<td>$row[finalPrice]</td>
											<td>$row[promo]</td>
											<td>$row[priority]</td>
											<td>$row[aeroline]</td>
											<td>$row[hotel]</td>
											<td>$row[placeFrom]</td>
											<td>$row[placeTo]</td>
											<td>$row[link]</td>
											<td><img width=50 src='data:image;base64,$row[image]'></td>
											<td><button name='eli' value=$row[id]>Eliminar</button></td>
											<td><button name='edi' value=$row[id]>Editar</button></td></tr>";
							}
						?>
					</table>
				</form>
			</fieldset>
		</div>
		<?php } ?>

        <div id="carouselExampleIndicators" class="carousel slide my-3" data-ride="carousel" data-interval="4000">
            <ol class="carousel-indicators">
                <?php
                    $selectImagesResult = $conn->query("select * from images");
                    $active = 0;
                    $rows = $selectImagesResult->fetch_all(MYSQLI_ASSOC);
                    foreach ($rows as $row) :
                ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="<?= $active ?>" class="<?= $active++ ? '' : 'active'; ?>"></li>
                <?php endforeach; ?>
            </ol>
            <div class="carousel-inner">
                <?php
                     $active = 0;
                     foreach ($rows as $row) :
                ?>
                <div class="carousel-item <?= $active++ ? '' : 'active'; ?>">
                    <div style="width:100%;height:400px;background:url('data:image;base64,<?php echo $row['image']; ?>') center no-repeat;background-size:cover;">
                        <div class="carousel-caption d-none d-md-block pt-1" style="background:rgba(0, 0, 0, .75);border-radius:10px;">
                            <b>Busca y compra con los mejores precios disponibles en hoteles, vuelos o paquetes</b>
                            <p>Podrás visualizar una lista ordenada por precio de menor a mayor</p>
                            <button class="btn btn-primary btn-lg mb-2"><i class="fa fa-search"></i> Buscar Ahora</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>


		<h1 class="articleHeading"><i>Promociones y especialeS</i></h1>
		<article class='cataArt' id="promSpace">
			<div id="promPro" class="row">
				<?php
					$selProm = "select * from promTable";
					$sPResult = $conn->query($selProm);
					while($row = $sPResult->fetch_assoc()){
					  	echo"<div class='promArt col-3 col-m-6' onclick=window.location.href='$row[link]'>
									<div class='promImgCont' style='background-image:url(data:image;base64,$row[image]'>
										<div class='promTableHeading'><h3><i>$row[title]</i></h3>
											<i>$row[subtitle]</i>
										</div><div class='promDescri'>";
										if($row['dateFrom'] != "0000-00-00" && !empty($row['dateFrom'])){
											echo "<i class='fa fa-calendar'></i> $row[dateFrom] ";
											if(!empty($row['days'])){
												$dateTo = $conn->query("select date_add(dateFrom, interval $row[days] day) asdf from promTable where id = $row[id]")->fetch_assoc();
												echo "<i class='fa fa-long-arrow-right'></i> <i class='fa fa-calendar'></i> $dateTo[asdf]";
											}
										}
										if(!empty($row['days'])) echo "<br><i class='fa fa-sun-o'></i> $row[days] Días";
										if(!empty($row['nights'])) echo " <i class='fa fa-minus'></i> <i class='fa fa-moon-o'></i> $row[nights] Noches";
										if(!empty($row['price'])){
											if(!empty($row['finalPrice']))
												echo "<br><span style='text-decoration:line-through'>&nbsp<i class='fa fa-dollar'></i> $row[price]</span>
														<i class='fa fa-long-arrow-right'></i> <i class='fa fa-dollar'></i> $row[finalPrice]";
											else echo "<br>&nbsp<i class='fa fa-dollar'></i> $row[price]";
										}
										if(!empty($row['aeroline'])) echo "<br><i class='fa fa-plane'></i> $row[aeroline]";
										if(!empty($row['hotel'])) echo "<br><i class='fa fa-building'></i> $row[hotel]";
										if(!empty($row['placeFrom'])) echo "<br><i class='fa fa-home'></i> $row[placeFrom]";
										if(!empty($row['placeTo'])) echo " <i class='fa fa-long-arrow-right'></i> <i class='fa fa-image'></i> $row[placeTo]";
									echo "</div>";
									if(!empty($row['promo'])){
										if($row['promo'] == 'Alta') echo "<div class=promOff style=background:red>-$row[promo]</div>";
										else if($row['promo'] == 'Media') echo "<div class=promOff style=background:yellow>-$row[promo]</div>";
										else if($row['promo'] == 'Baja') echo "<div class=promOff style=background:green>-$row[promo]</div>";
									}
								echo "</div>
							 </div>";
					}
				?>
			</div>
		</article>
	</section>
	<footer>
		<div id="footerTopDiv">
			La roca turismo y eventos <i class="fa fa-registered"></i>&nbsp&nbsp&nbspNúmero de contacto &nbsp&nbsp<i class="fa fa-mobile"></i> Celular: 310 469 2182 &nbsp&nbsp<i class="fa fa-phone"></i> Teléfono: 304 16 07
		</div>
	</footer>
	<script src='js/timeSlider.js'></script>
	<script src='js/scrolling.js'></script>
	<script src='js/userSystem.js'></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
