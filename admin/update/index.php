<?php
	include "../php/connection.php";
	session_start();

	// Validar si el metodo post contiene informacion
	if(!empty($_POST)) {
		$message = '';

		if(empty($_POST['url']) || empty($_POST['name']) || empty($_POST['description']) || empty($_POST['existencias']) || empty($_POST['precio']))
			$message = 'SE DEJARON CAMPOS VACIOS.';
		else {
			$id = $_POST['id'];
			$url = $_POST['url'];
			$name = $_POST['name'];
			$description = $_POST['description'];
			$amount = $_POST['existencias'];
			$price = $_POST['precio'];

			$query = mysqli_query($connection, "SELECT * FROM producto WHERE (nombre = '$name' AND id != $id) OR (fotoURL = '$url' AND id != $id)");

			if($query) {
				$result = mysqli_num_rows($query);
				if($result > 0)
					$message = 'El nombre del postre o la foto ya existe';
				else {
					$update = mysqli_query($connection, "UPDATE producto SET nombre = '$name', descripcion = '$description', existencia = '$amount', precio = '$price', fotoURL = '$url' WHERE id = $id");

					if($update)
						$message = 'Producto actualizado';
					else
						$message = 'Error al actualizar producto';
				}
			} else
				$message = 'Error al ejecutar consulta: '.mysqli_error($connection);
		}
	}

	if(empty($_GET['id'])) {
		header('Location: ../../');
		exit;
	}

	$idProduct = $_GET['id'];

	$sql = mysqli_query($connection, "SELECT fotoURL, nombre, descripcion, existencia, precio FROM producto WHERE id = $idProduct");

	if($sql) {
		$numRows = mysqli_num_rows($sql);
		if($numRows > 0) {
			while($row = mysqli_fetch_array($sql)) {
				$fotoURL = $row['fotoURL'];
				$nombre = $row['nombre'];
				$descripcion = $row['descripcion'];
				$existencia = $row['existencia'];
				$precio = $row['precio'];
			}
		} else {
			header('Location: ../../');
			exit;
		}
	} else
		$message = 'Error al ejecutar consulta: '.mysqli_error($connection);

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
	<link rel="icon" href="./icons/home-white.png">
	<link rel="stylesheet" href="../../globales.css?ts=<?=time()?>">
	<link rel="stylesheet" href="./style.css?ts=<?=time()?>">
	<title>Update Product | Admin</title>
</head>
<body>
	<header class="mobile-header tablet-header desktop-header">
		<nav>
			<section class="navbar-left">
				<label for="btn-menu">
					<i class="fa-solid fa-bars"></i>
				</label>
				<figure class="brand">
					<a href="../" class="redirect-home">
						<img src="../../assets/logo2.png" alt="Logo" class="logoImage">
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
					<li><a href="../php/logout.php" class="fa-solid fa-arrow-right-from-bracket signOut">Cerrar sesion</a></li>
				</ul>
			</section>
		</nav>
  </aside>

	<form action="./index.php" method="post" class="form">
		<h1 class="title">MODIFICAR PRODUCTO</h1>

		<?php if(!empty($message)) : ?>
			<p class="message"><?= $message ?></p>
		<?php endif; ?>

		<input type="hidden" name="id" id="id" value="<?php echo $idProduct; ?>">
		<label for="url" class="label">Foto del producto (url):</label>
		<input type="url" id="url" name="url" placeholder="https://mandolina.co/wp-content/uploads/2022/08/Panaderia-y-reposteria-dulce-01.png" class="input input-url" required value="<?php echo $fotoURL; ?>">

		<label for="name" class="label">Nombre del postre:</label>
		<input type="text" id="name" name="name" placeholder="Ej. Flan napolitano" class="input input-name" required value="<?php echo $nombre; ?>">

		<label for="description" class="label">Descripción:</label>
		<input type="text" id="description" name="description" placeholder="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam exercitationem neque amet iusto deleniti ab." class="input input-description" required value="<?php echo $descripcion; ?>">

		<section class="split-half">
			<div>
				<label for="existencias" class="label">Existencias:</label>
				<input type="number" id="existencias" name="existencias" placeholder="20" class="input input-existencias" required value="<?php echo $existencia; ?>">
			</div>

			<div>
				<label for="precio" class="label">Precio:</label>
				<input type="number" id="precio" name="precio" placeholder="159" class="input input-price" required value="<?php echo $precio; ?>">
			</div>
		</section>

		<input type="submit" value="Actualizar producto" class="primary-button signup-button">
	</form>
</body>
</html>
