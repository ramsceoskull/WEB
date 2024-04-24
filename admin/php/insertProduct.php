<?php
	include "./connection.php";

	$url = $_POST['url'];
	$name = $_POST['nombre'];
	$description = $_POST['description'];
	$existencias = $_POST['existencias'];
	$price = $_POST['precio'];

	$sql = mysqli_query($connection, "INSERT INTO Producto(nombre, descripcion, existencia, precio, fotoURL) VALUES ('$name', '$description', '$existencias', '$price', '$url')");

	if($sql) {
		header('Location: ../insert/product.html');
		exit;
	}
	else
		echo " -> Error al registrar producto";
