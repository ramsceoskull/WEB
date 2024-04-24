<?php
	include "./connection.php";
	session_start();

	if(!isset($_SESSION['loggedin'])) {
		header('Location: ../../login/');
		exit;
	}

	if(!isset($_POST['email'], $_POST['password'])) {
		header('Location: ../../login/');
		// si no hay datos en los campos, te redirecciona
	}

	if($stmt = $connection->prepare('SELECT id, pass FROM usuario WHERE correo = ?')) {
		$stmt->bind_param('s', $_POST['email']);
		$stmt->execute();
	}
	// evitar inyeccion sql

	$stmt->store_result();
	if($stmt->num_rows > 0) {
		$stmt->bind_result($id, $password);
		$stmt->fetch();
		// se confirma que la cuenta existe, ahora se valida la contraseña

		if($_POST['password'] === $password) {
			// la conexion seria exitosa en caso de entrar a este if
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['correo'] = $_POST['email'];
			$_SESSION['id'] = $id;
			header('Location: ../../catalogo/');
		}
	} else {
		// en caso de entrar al else, es debido a un usuario incorrecto
		header('Location: ../../login/');
	}

	$stmt->close();


?>
