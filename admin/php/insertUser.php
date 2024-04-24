<?php
	include "./connection.php";

	$name = $_POST['nombre'];
	$age = $_POST['age'];
	$apellidoP = $_POST['apellidoP'];
	$apellidoM = $_POST['apellidoM'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$sql = mysqli_query($connection, "INSERT INTO Usuario(nombres, edad, apellidoPaterno, apellidoMaterno, correo, pass) VALUES ('$name', '$age', '$apellidoP', '$apellidoM', '$email', '$password')");

	if($sql) {
		header('Location: ../../');
		exit;
	}
	else
		echo " -> Error al registrar usuario";
