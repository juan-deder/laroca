<?php
include("php/data.php");
if (isset($_GET['admin'])) {
    $row = $conn->query("select name, dates from servi where id = $_GET[id]")->fetch_assoc();
    if (isset($_GET['edi'])) {
        $ediInfo = $conn->query("select * from $_GET[table] where id = $_GET[edi]")->fetch_assoc();
        $ediInfo = array_values($ediInfo);
    }
    echo "<h2>Imagenes</h2><form method='post' enctype='multipart/form-data'>
				<label for='image'>Imagen: </label>
				<input type='file' name='image' required><br>
				<input type='hidden' name='table' value='images$_GET[id]'>
				<input type='hidden' name='action' value='insert'>
				<input type='submit' value='Añadir'>
			</form>
			<form method='post'>
				<input type='hidden' name='table' value='images$_GET[id]'>
				<input type='hidden' name='action' value='delOrUp'>
				<table border='1' class='adminTable'>
					<tr>
						<th>Imagen</th>
						<th>Eliminar</th>
					</tr>";
    $selectImagesResult = $conn->query("select * from images$_GET[id]");
    while ($row2 = $selectImagesResult->fetch_assoc()) {
        echo "<tr><td><img src='data:image;base64,$row2[image]' width='50'></td><td><button name='eli' value='$row2[id]'>Eliminar</button></td></tr>";
    }
    echo "</table>
			</form>"; ?>
    <h2>Incluye</h2>
    <form method='post'>
        <label for='dateFrom'>Incluye: </label>
        <input type='text'
               name='included' <?php if (isset($_GET['edi']) && $_GET['table'] == "includes$_GET[id]") echo "value='$ediInfo[1]'" ?>
               required><br>
        <input type='hidden' name='table' value='<?php echo "includes$_GET[id]" ?>'>
        <input type="hidden" name="action"
               value="<?php if (isset($_GET['edi']) && $_GET['table'] == "includes$_GET[id]") echo 'update'; else echo 'insert'; ?>"><br>
        <?php if (isset($_GET['edi']) && $_GET['table'] == "includes$_GET[id]") echo "<input type='hidden' name='id' value='$_GET[edi]'>"; ?>
        <input type="submit" <?php if (isset($_GET['edi']) && $_GET['table'] == "includes$_GET[id]") echo "value='Actualizar'"; else echo "value='Insertar'"; ?>>
    </form>
    <form method='post'>
    <input type='hidden' name='table' value='<?php echo "includes$_GET[id]" ?>'>
    <input type='hidden' name='action' value='delOrUp'>
    <table border='1' class='adminTable'>
    <tr>
        <th>Incluye</th>
        <th>Editar</th>
        <th>Eliminar</th>
    </tr>
    <?php $selectImagesResult = $conn->query("select * from includes$_GET[id]");
    while ($row2 = $selectImagesResult->fetch_assoc()) {
        echo "<tr><td>$row2[included]</td><td><button name='edi' value='$row2[id]'>Editar</button></td><td><button name='eli' value='$row2[id]'>Eliminar</button></td></tr>";
    }
    echo "</table>
		</form>";
    if ($row['dates'] == 'si') {
        ?>
        <h2>Fechas de salida</h2>
        <form method='post'>
            <label for='dateFrom'>Fecha de inicio: </label>
            <input type='date'
                   name='dateFrom' <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='$ediInfo[1]'" ?>
                   required><br>
            <label for='days'>Días - </label>
            <label for='nights'>Noches: </label><br>
            <input type='number' name='days'
                   min='1' <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='$ediInfo[2]'" ?>
                   required>
            <input type='number' name='nights'
                   min='0' <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='$ediInfo[3]'" ?>
                   required><br>
            <label for='price'>Precio: </label>
            <input type='text'
                   name='price' <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='$ediInfo[4]'" ?>
                   required><br>
            <label for='hotel'>Hotel: </label>
            <input type='text'
                   name='hotel' <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='$ediInfo[5]'"; else echo 'value="No aplica"'; ?>><br>
            <input type='hidden' name='table' value='<?php echo "dates$_GET[id]" ?>'>
            <input type="hidden" name="action"
                   value="<?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo 'update'; else echo 'insert'; ?>"><br>
            <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "<input type='hidden' name='id' value='$_GET[edi]'>"; ?>
            <input type="submit" <?php if (isset($_GET['edi']) && $_GET['table'] == "dates$_GET[id]") echo "value='Actualizar'"; else echo "value='Insertar'"; ?>>
        </form>
        <form method='post'>
        <input type='hidden' name='table' value='<?php echo "dates$_GET[id]" ?>'>
        <input type='hidden' name='action' value='delOrUp'>
        <table border='1' class='adminTable'>
            <tr>
                <th>Fecha de incio</th>
                <th>Días</th>
                <th>Noches</th>
                <th>Precio</th>
                <th>Hotel</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        <?php $selectImagesResult = $conn->query("select * from dates$_GET[id]");
        while ($row = $selectImagesResult->fetch_assoc()) {
            echo "<tr><td>$row[dateFrom]</td>
								<td>$row[days]</td>
								<td>$row[nights]</td>
								<td>$row[price]</td>
								<td>$row[hotel]</td>
								<td><button name='edi' value='$row[id]'>Editar</button></td>
								<td><button name='eli' value='$row[id]'>Eliminar</button></td></tr>";
        }
        echo "</table>
		</form>";
    }
} else if (isset($_GET['list'])) {
    if (isset($_GET['all'])) $result = $conn->query("select * from categ");
    else $result = $conn->query("select * from categ where id = $_GET[list]");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='serviAndCate'><h1 class='serviCate' onclick='popServi(\"list=$row[id]\")'>$row[name]</h1>";
        $result2 = $conn->query("select * from servi where cate = $row[id]");
        while ($row2 = $result2->fetch_assoc()) {
            $image = $conn->query("select * from images$row2[id]")->fetch_assoc();
            echo "<div class='serviOption' onclick='popServi(\"id=$row2[id]\")'>
					<h1 class='serviHeading'><i class='fa fa-dot-circle-o'></i> $row2[name]</h1>
					<div class='serviDescri'><p>$row2[descri]</p></div>
					<div class='serviImage' style='background-image:url(data:image;base64,$image[image]);background-size:cover'></div>
					<div class='verMas'><i class='fa fa-plus-square'></i> Ver más ></div>
				</div>";
        }
        echo '</div>';
    }
} else {
    $row = $conn->query("select * from servi where id = $_GET[id]")->fetch_assoc();
    echo "<div class='serviSelected'>
			<h1 class='serviCate'><i class='fa fa-tag'></i> $row[name]</h1>
			<div class='serviSelimages'>";

    ?>
 <div id="carouselExampleIndicators" class="carousel slide my-3" data-ride="carousel" data-interval="4000">
    <ol class="carousel-indicators">
        <?php
            $selectImagesResult = $conn->query("select * from images$row[id]");
            //$selectImagesResult = $conn->query("select * from images");
            $active = 0;
            $rows = $selectImagesResult->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $row2) :
        ?>
        <li data-target="#carouselExampleIndicators" data-slide-to="<?= $active ?>" class="<?= $active++ ? '' : 'active'; ?>"></li>
        <?php endforeach; ?>
    </ol>
    <div class="carousel-inner">
        <?php
             $active = 0;
             foreach ($rows as $row2) :
        ?>
        <div class="carousel-item <?= $active++ ? '' : 'active'; ?>">
            <div style="width:100%;height:400px;background:url('data:image;base64,<?php echo $row2['image']; ?>') center no-repeat;background-size:cover;">
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
    <?php

    echo "<div class='serviSelDescri'>$row[descri]</div>
		</div>
		<div class='serviSelIncludes'>
			<h1 class='serviSelHeading'><i class='fa fa-cubes'></i> Incluye</h1>";
    $includes = $conn->query("select * from includes$row[id]");
    while ($row2 = $includes->fetch_assoc()) {
        echo "<li><i class='fa fa-cube'></i> $row2[included]</li>";
    }
    echo "</div>";

    if ($conn->query("select * from dates$_GET[id]")) {
        echo "<div class='serviSelDates'>
						<h1 class='serviSelHeading'><i class='fa fa-calendar'></i> Fechas</h1>
						<table class='serviSelTable'>
							<tr>
								<th>Fecha de salida</th>
								<th>Fecha de llegada</th>
								<th>Días</th>
								<th>Noches</th>
								<th>Precio</th>
								<th>Hotel</th>
							</tr>";
        $dates = $conn->query("select * from dates$_GET[id]");
        while ($row2 = $dates->fetch_assoc()) {
            $dateTo = $conn->query("select date_add(dateFrom, interval $row2[days] day) asdf from dates$_GET[id] where id = $row2[id]")->fetch_assoc();
            echo "<tr>
					<td>$row2[dateFrom]</td>
					<td>$dateTo[asdf]</td>
					<td>$row2[days]</td>
					<td>$row2[nights]</td>
					<td>$row2[price]</td>
					<td>$row2[hotel]</td>
				</tr>";
        }
        echo "</table>
			</div>";
    }
}