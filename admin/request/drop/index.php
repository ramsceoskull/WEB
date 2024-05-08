<?php
	include "../../php/connection.php";

	if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
		header('Location: ../../');
		exit;
	}
	else {
		$id = $_REQUEST['id'];
		$message = '';

		$sql = mysqli_query($connection, "SELECT id, nombre FROM producto WHERE id = $id");

		if($sql) {
			$numRows = mysqli_num_rows($sql);
			if($numRows > 0) {
				$query = mysqli_query($connection, "DELETE FROM producto WHERE id = $id");

				if($query) {
					header('Location: ../../');
					exit;
				}
				else
					$message = 'ERROR AL ELIMINAR';
			} else {
				header('Location: ../../');
				exit;
			}
		} else
			$message = 'Error al ejecutar consulta: '.mysqli_error($connection);
	}

?>

<?php if(!empty($message)) : ?>
	<p class="message"><?= $message ?></p>
<?php endif; ?>
