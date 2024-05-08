<?php
	$server = "localhost";
	$database = "reposteria";
	$user = "root";
	$password = "";

	$connection = mysqli_connect($server, $user, $password, $database);
	if (!$connection)
		die("Falla en la conexion".mysqli_connect_error());

// echo "Conexion exitosa";
