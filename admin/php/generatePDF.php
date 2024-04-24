<?php
	/* session_start();
	if(!isset($_SESSION['usuario']))
		header("Location: ../../login/");
	else if($_SESSION['usuario'] == "ok")
		$nombreUsuario = $_SESSION["nombreUsuario"]; */

	ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Generar PDF</title>
</head>
<body>
	<h1>BIENVENIDO... <?php echo $_POST['email']; ?> !</h1>
</body>
</html>

<?php
	require_once '../lib/dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
	$dompdf = new DOMPDF();

	$dompdf -> loadHtml(ob_get_clean());
	// cargar todo el contenido html previo y guardarlo en memoria

	$options = $dompdf -> getOptions();
	$options -> set(array('isRemoteEnabled' => true));
	$dompdf -> setOptions($options);
	// permite jalar imagenes para poderlas mostrar en el pdf generado

	$dompdf -> setPaper('letter');
	// configuracion del papel

	$dompdf -> render();
	// todo lo que se indico/configuro al dompdf, ahora es hacerlo visible

	$dompdf -> stream("ejemplo.pdf", array("Attachment" => true));
	// true descarga el archivo, false lo muestra en el navegador
?>
