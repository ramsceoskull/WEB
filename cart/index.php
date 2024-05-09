<?php
	include "../admin/php/connection.php";
	session_start();

	// Verificar si la sesión del usuario está iniciada
	if (!isset($_SESSION['email'])) {
		// Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
		header('Location: ../login/');
		exit;
}

	// Verificar si hay productos en el carrito
	if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0)
		$total = 0; // Inicializar el total del carrito
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../assets/icons/catalogo.png">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="../globales.css?ts=<?=time()?>">
	<link rel="stylesheet" href="./style.css?ts=<?=time()?>">
	<!-- <link rel="stylesheet" href="./styleL.css?ts=<?=time()?>" media="(min-width: 768px)"> -->
	<title>Carrito | Detalles con Corazon</title>
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
					<a href="../catalogo/" class="redirect-home">
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
		<h2 class="title">Carrito de compras</h2>

		<div class="table-container">
			<table class="productsTable">
				<thead class="encabezadoTable">
					<tr>
						<th class="url">IMAGEN</th>
						<th class="nombre">NOMBRE</th>
						<th class="descripcion">DESCRIPCION</th>
						<th class="existencias">CANTIDAD</th>
						<th class="precio">PRECIO</th>
						<th class="subtotal">SUBTOTAL</th>
						<th class="eliminar">ELIMINAR</th>
					</tr>
				</thead>
				<tbody class="cuerpoTable">
					<?php foreach ($_SESSION['carrito'] as $key => $item) : ?>
						<tr class="filaInfoProduct">
							<td class="urlData"><figure style="background-image: url('<?php echo $item['foto'] ?>');"></figure></td>
							<td class="nombreData"><p><?php echo $item['nombre'] ?></p></td>
							<td class="descripcionData"><p><?php echo $item['descripcion'] ?></p></td>
							<td class="existenciasData"><p><?php echo $item['cantidad'] ?></p></td>
							<td class="precioData"><p>$ <?php echo $item['precio'] ?></p></td>
							<td class="subtotalData"><p><?php echo number_format($item['precio'] * $item['cantidad']) ?></p></td>
							<td class="queryButton">
								<form action="./eliminarProducto.php" method="post" class="">
									<input type="hidden" name="producto" value="<?php echo $key; ?>">
									<button type="submit" class="">
										<i class="fa-regular fa-trash-can"></i>Eliminar
									</button>
								</form>
							</td>
						</tr>
						<?php $total += $item['precio'] * $item['cantidad']; ?>
					<?php endforeach; ?>
					<tr>
						<td><b>Total :</b></td>
						<td><?php echo number_format($total); ?></td>
						<td class="pdf">
							<form action="../admin/php/generatePDF.php" method="post">
								<input type="submit" value="Enviar PDF">
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>


	<a href="../catalogo/">Volver a la lista de productos</a>
	<?php endif; ?>
</body>
</html>
