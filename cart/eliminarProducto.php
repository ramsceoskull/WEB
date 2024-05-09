<?php
	session_start();

	// Verificar si se ha enviado el índice del producto a eliminar
	if (isset($_POST['producto'])) {
			$producto = $_POST['producto'];

			// Verificar si el índice del producto está dentro del rango de productos en el carrito
			if (isset($_SESSION['carrito'][$producto])) {
					// Eliminar el producto del carrito utilizando el índice proporcionado
					unset($_SESSION['carrito'][$producto]);
			}
	}

	// Redirigir de vuelta al carrito después de eliminar el producto
	header('Location: ./');
	exit;
?>
