<?php
	require "./php/connection.php";
	session_start();

	if(isset($_SESSION['id'])) {
		$id = $_SESSION['id'];
		$message = '';

		$sql2 = mysqli_query($connection, "SELECT id, nombres, apellidoP, apellidoM, email, rol FROM usuario WHERE id = '$id'");

		if($sql2) {
			$numRows = mysqli_num_rows($sql2);
			if($numRows > 0) {
				while($row = mysqli_fetch_array($sql2)) {
					$_SESSION['nombre'] = $row['nombres'];
					$_SESSION['apellidoP'] = $row['apellidoP'];
					$_SESSION['apellidoM'] = $row['apellidoM'];
					$_SESSION['email'] = $row['email'];
				}
			} else
				$message = 'No se encontro el id del usuario :(';
		} else
			$message = 'Error al ejecutar consulta: '.mysqli_error($connection);
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="../globales.css?ts=<?=time()?>">
	<link rel="stylesheet" href="./style.css?ts=<?=time()?>">
	<title>View Products | Admin</title>
</head>
<body>
	<?php
		$sql = mysqli_query($connection, "SELECT * FROM producto");

		if(isset($_SESSION['email'])) :
	?>

	<header class="mobile-header tablet-header desktop-header">
		<nav>
			<section class="navbar-left">
				<label for="btn-menu">
					<i class="fa-solid fa-bars"></i>
				</label>
				<figure class="brand">
					<a href="../home" class="redirect-home">
						<img src="../assets/logo2.png" alt="Logo" class="logoImage">
						<p class="logoName">Detalles con Corazón</p>
					</a>
				</figure>
				<ul class="listNavigation">
					<li class="listLinks"><a href="#" class="linkItem">item 1</a></li>
					<li class="listLinks"><a href="#" class="linkItem">item 2</a></li>
					<li class="listLinks"><a href="#" class="linkItem">item 3</a></li>
					<li class="listLinks"><a href="#" class="linkItem">item 4</a></li>
					<li class="listLinks"><a href="#" class="linkItem">item 5</a></li>
				</ul>
			</section>
			<section class="navbar-right">
				<ul class="listProfileInfo">
					<li class="profileEmail">
						<p class="email"><?php echo $_SESSION['email'] ?></p>
						<i class="fa-solid fa-angle-down"></i>
					</li>
					<li class="profileShopping">
						<img src="/assets/white/bolsa-de-compra.png" alt="Carrito de compras">
						<span>0</span>
					</li>
				</ul>
			</section>
		</nav>
	</header>

	<input type="checkbox" id="btn-menu">
  <aside class="mobile-menu">
		<nav class="menu__content">
			<label for="btn-menu"><i class="fa-solid fa-circle-xmark"></i></label>
			<section class="topSection">
				<ul class="listNavigation">
					<li><h3>PAGINAS</h3></li>
					<li><a href="./admin/">ADMIN</a></li>
					<li><a href="./login/">Iniciar sesion</a></li>
					<li><a href="./signup/">Crear cuenta</a></li>
					<li><a href="./catalogo/">Catalogo</a></li>
					<li><a href="./product/">Producto</a></li>
					<li><a href="./email/sent.html">Recuperar contraseña</a></li>
				</ul>
			</section>
			<section class="bottomSection">
				<ul class="userAccount">
					<li><a href="/" class="email"><?php echo $_SESSION['email'] ?></a></li>
					<li><a href="../admin/php/logout.php" class="fa-solid fa-arrow-right-from-bracket signOut">Cerrar sesion</a></li>
				</ul>
			</section>
		</nav>
  </aside>

	<section class="productContainer">
		<h2 class="title">Ver productos</h2>
		<a href="./insert/product.php" class="linkToAddProduct">Agregar producto</a>
		<form action="./index.php" method="post" class="buscador">
			<input type="text" name="buscador" id="buscador" placeholder="Nombre o Descripcion">
			<input type="submit" value="Buscar">
		</form>
		<?php
			if(!empty($_POST['buscador'])) {
				$aKeyword = explode(" ", $_POST['buscador']);
				$query ="SELECT * FROM producto WHERE nombre like '%" . $aKeyword[0] . "%' OR descripcion like '%" . $aKeyword[0] . "%'";

				for($i = 1; $i < count($aKeyword); $i++)
					if(!empty($aKeyword[$i])) {
						$query .= " OR nombre like '%" . $aKeyword[$i] . "%'";
						$query .= " OR descripcion like '%" . $aKeyword[$i] . "%'";
					}

				$result = mysqli_query($connection, $query);
				echo "<p class='keyWord'>Has buscado la palabra clave:<b> ". $_POST['buscador']."</b></p>";

				if(mysqli_num_rows($result) > 0) {
					$row_count = 0;
					echo "<p class='resultados'>Resultados encontrados: </p>";
					echo "<table class='productsTable'>";
					while($row = mysqli_fetch_array($result)) {
						$row_count++;
						echo "<tr><td>".$row_count." </td><td>". $row['nombre'] . "</td><td>". $row['descripcion'] . "</td></tr>";
					}
					echo "</table>";
				} else
					echo "<p class='sinResultados'>Resultados encontrados: <b>Ninguno</b></p>";
			}
		?>

		<div class="table-container">
			<table class="productsTable">
				<thead class="encabezadoTable">
					<tr>
						<th class="id">ID</th>
						<th class="url">IMAGEN</th>
						<th class="nombre">NOMBRE</th>
						<th class="descripcion">DESCRIPCION</th>
						<th class="existencias">EXISTENCIAS</th>
						<th class="precio">PRECIO</th>
						<th class="operaciones">PETICION</th>
					</tr>
				</thead>
				<tbody class="cuerpoTable">
					<?php while($row = mysqli_fetch_array($sql)) : ?>
						<tr class="filaInfoProduct">
							<td class="idData"><p><?php echo $row['id'] ?></p></td>
							<td class="urlData"><figure style="background-image: url('<?php echo $row['fotoURL'] ?>');"></figure></td>
							<td class="nombreData"><p><?php echo $row['nombre'] ?></p></td>
							<td class="descripcionData"><p><?php echo $row['descripcion'] ?></p></td>
							<td class="existenciasData"><p><?php echo $row['existencia'] ?></p></td>
							<td class="precioData"><p>$ <?php echo $row['precio'] ?></p></td>
							<td class="queryButton">
								<a href="./update/?producto=editar&id=<?php echo $row['id'] ?>" class="operation fa-solid fa-pen-nib"><span>Modificar</span></a>
								<?php if($row['id'] != 1) : ?>
									<a href="./drop/?producto=eliminar&id=<?php echo $row['id'] ?>" onclick="return confirm('Estas seguro de que desear eliminar el postre <?php echo $row['nombre'] ?>?')" class="operation fa-regular fa-trash-can"><span>Eliminar</span></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</section>
	<?php endif; ?>
</body>
</html>
