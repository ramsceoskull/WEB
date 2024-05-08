<?php
	require "../admin/php/connection.php";
	session_start();

	if(isset($_SESSION['id'])) {
		$id = $_SESSION['id'];
		$message = '';

		if(!isset($_SESSION['carrito']))
			$_SESSION['carrito'] = array();

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
				$message = 'Error al ingresar la contraseña';
			} else
				$message = 'No se encontro el id del usuario :(';
		} else
			$message = 'Error al ejecutar consulta: '.mysqli_error($connection);
	}

	$_SESSION['carrito'][] = array(
		'nombre' => $_POST['nombre'],
		'descripcion' => $_POST['descripcion'],
		'existencia' => $_POST['cantidad'],
		'precio' => $_POST['precio'],
		'foto' => $_POST['foto']
	);


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
	<link rel="stylesheet" href="./styleL.css?ts=<?=time()?>" media="(min-width: 768px)">
	<title>Catalogo | Detalles con Corazon</title>
</head>
<body>
	<?php if(isset($_SESSION['email'])) : ?>
		<?php
		echo var_dump($_SESSION['carrito']);
		$sql = mysqli_query($connection, "SELECT * FROM producto");
	?>
	<label for="" id="top"></label>
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
						<p class="email">jackiedoes@gmail.com</p>
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
					<li><a href="/" class="email">jackiedoes@gmail.com</a></li>
					<li><i class="fa-solid fa-arrow-right-from-bracket"></i><a href="../admin/php/logout.php" class="signOut">Cerrar sesion</a></li>
				</ul>
			</section>
		</nav>
  </aside>

	<section class="mainLayout">
		<h2 class="title">CATALOGO</h2>

		<section class="cardsContainer">
			<p class="message">Bienvenido <?= $_SESSION['email'] ?></p>
			<?php while($row = mysqli_fetch_array($sql)) :?>
				<article class="cardProduct">
					<figure class="productImage" style="background-image: url('<?php echo $row['fotoURL'] ?>');"></figure>
					<h4 class="productName"><?php echo $row['nombre'] ?></h4>
					<p class="productDescription"><?php echo $row['descripcion'] ?></p>
					<div class="infoDetail">
						<p class="existencias">Existencias: <span><?php echo $row['existencia'] ?></span></p>
						<p class="price"><span>$</span> <?php echo $row['precio'] ?></p>
					</div>
					<form action="./index.php" method="post" class="split">
						<input type="hidden" name="foto" value="<?php echo $row['fotoURL']; ?>">
						<input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
						<input type="hidden" name="descripcion" value="<?php echo $row['descripcion']; ?>">
						<input type="number" name="cantidad" required>
						<input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
						<input type="submit" value="Agregar" name="btnAgregar">
					</form>
				</article>
			<?php endwhile; ?>
		</section>
	</section>

	<footer class="footer-container">
		<a href="#top" class="linkToTop">
			<i class="fa-solid fa-caret-up"></i>
			<br>Regresar al Inicio
		</a>
		<p class="creatorName">Ramsés Alejandro López Anceno</p>
		<section class="payment-methods">
			<i class="fa-brands fa-cc-visa"></i>
			<i class="fa-brands fa-cc-paypal"></i>
			<i class="fa-solid fa-gift"></i>
			<i class="fa-brands fa-cc-amex"></i>
			<i class="fa-brands fa-cc-mastercard"></i>
		</section>
		<section class="socialMedia">
			<i class="fa-brands fa-facebook"></i>
			<i class="fa-brands fa-telegram"></i>
			<i class="fa-brands fa-spotify"></i>
			<i class="fa-brands fa-github"></i>
		</section>
		<section class="bottom-info">
			<figure class="logoRound">
				<img src="../assets/logoWhite.png" alt="Logo Emblema">
				<div class="copyrightYear">
					<i class="fa-regular fa-copyright"></i>
					<p>2024</p>
				</div>
			</figure>
			<div class="specialThanks">
				<p class="admin">Hecho con amor | <a href="mailto:a22310355@ceti.mx">admin@gmail.com</a></p>
				<p class="copyrightDetail">Todos los derechos reservados</p>
			</div>
		</section>
	</footer>
	<?php else : ?>
		<p>Por favor inicia sesion para acceder al catalogo</p>
		<a href="../login/">Iniiciar</a>

	<?php endif; ?>
	<script src="../script.js"></script>
</body>
</html>
