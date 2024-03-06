<?php
	include "./connection.php";

	$name = $_POST['nombre'];
	$age = $_POST['age'];
	$apellidoP = $_POST['apellidoP'];
	$apellidoM = $_POST['apellidoM'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$sql = mysqli_query($connection, "INSERT INTO Usuario(id, nombres, edad, apellidoPaterno, apellidoMaterno, correo, pass) VALUES (0, '$name', '$age', '$apellidoP', '$apellidoM', '$email', '$password')");

	if($sql)
		echo " -> Usuario registrado";
	else
		echo " -> Error al registrar usuario";
