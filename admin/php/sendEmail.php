<?php
	include "./connection.php";
	session_start();


	if(!isset($_SESSION['id'])){
		$msg = "No se ha iniciado sesión";
		header("refresh:1; url=../../login/");
		echo '<div>'.$msg.'</div>';
		echo '<p>Serás redirigido al log in en 5 segundos.</p>';
    exit;
	}

	$id = $_SESSION['id'];
	$sql = mysqli_query($connection,"SELECT * FROM usuario WHERE id = '$id'");
	$user = mysqli_fetch_array($sql);

    require('../lib/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm', 'A4');
    $x = 10;
    $y = 10;

    $pdf->AddPage();
    $pdf->SetXY($x, $y);
    $pdf->Image('../../assets/logoBlack.png',165,10,40,0,'PNG','index.php');
    $pdf->SetFont('Arial','B',16);
    $pdf->SetFillColor(255,196,102);
    $pdf->SetDrawColor(255,255,255);

    // green: rgb(131,164,102);
    // black: rgb(0, 0, 0);
    $pdf->SetTextColor(191,131,141);

    /* $pdf->SetXY(25,$y+5);
    $pdf->SetFontSize(35);
    $pdf->Cell(150,10,'RECIBO',0,0,'L',0); */
    $pdf->SetXY(25,$y+25);
    $pdf->SetFontSize(9);
    $pdf->SetTextColor(0,0,0);

    // Datos genéricos
    $pdf->Cell(60,0,'DE: DETALLES CON CORAZON');
    $fecha = Date("d-m-Y");
    $pdf->Cell(0,0,'FECHA DE EXPEDICION: '.$fecha.'');
    $pdf->SetXY(25, 38);
    $pdf->Cell(60,5,'TELEFONO: 3322556155');
    $pdf->Cell(60,5,'CORREO: ramses.social0@gmail.com');
    //Fin datos genéricos

    //Datos del comprador
    $pdf->SetXY(25,50);
    $pdf->Cell(60,10,'PARA:');
    $pdf->Cell(60,10,'EMAIL:');
    $pdf->SetXY(25,53);
    $pdf->SetTextColor(191,131,141);
    $pdf->Cell(60,15, $user['nombres'].' '.$user['apellidoP'].' '.$user['apellidoM']);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(60,15, $user['email']);
    $pdf->SetXY(25,56);

    // Datos de la compra
    $y = 40;
    // $pdf->SetDrawColor(0,0,0):
    $pdf->SetDrawColor(191,131,141);
    $pdf->SetFillColor(191,131,141);

    $pdf->SetXY(25,85);
    $pdf->Cell(40,10,'Nombre',1,0,'c', true);
    $pdf->Cell(20,10,'Precio',1,0,'c', true);
    $pdf->Cell(20,10,'Cantidad',1,0,'c', true);
    $pdf->Cell(25,10,'Subtotal',1,1,'c', true);

    $total = 0;

    // Verificar si el carrito está definido
    if (isset($_SESSION['carrito'])) {
        $pdf->SetXY(25, 95);
        foreach ($_SESSION['carrito'] as $key => $value) {
            $pdf->SetX(25);
            $pdf->Cell(40, 5, $value['nombre'], 1, 0, "L");
            $pdf->Cell(20, 5, "$" . $value['precio'], 1, 0, "L");
            $pdf->Cell(20, 5, $value['cantidad'], 1, 0, "L");

            $subtotal = $value['cantidad'] * $value['precio'];

            $pdf->Cell(20, 5, "$" . $subtotal, 1, 1, "L");

            $total += $subtotal;
        }

        $pdf->SetXY(25, 170);
        $pdf->Cell(30, 5, "TOTAL:", 0, "L");
        $pdf->SetXY(50, 170);
        $pdf->Cell(30, 5, " $ " . $total, "", 0, "L");

    } else {
        $pdf->SetXY(25, 95);
        $pdf->Cell(0, 10, "El carrito se encuentra vacio en estos momentos", 0, 1);
    }

    //footer
    $pdf->SetXY($x+10,200);
    $pdf->Cell(0,5,'GRACIAS POR SU COMPRA',0,0,'C');
    $pdf->SetXY($x+10,205);
    // $pdf->Image('../img/products/' .$rprod['imagen'],70,215,80,0,'PNG','');

    // title of pdf
    $title = 'COMPRA ' .$user['email'] . " ". Date("F_j_Y");

    //Output the document
    // $pdf->Output('I', $title . ".pdf");
    $pdf->Output('F', $title . ".pdf");

    // Configuración email
    $to = $user['email'];
    // $from = 'no-reply@tenko.com]';
    $from = "ramses.social0@gmail.com";
    $subject = $title;
    $msg = 'Se adjuntan los detalles de su compra en nuestro sitio web. DETALLES CON CORAZON...';

    // archivos adjuntos
    $file = $title . ".pdf";
    $attachment = chunk_split(base64_encode(file_get_contents($file)));
    $boundary = md5(date('r', time()));

    // encabezado
    $headers = "From: RAMSCEO <$from>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Return-path: $to\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // cuerpo del correo
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "\r\n" . chunk_split(base64_encode($msg)) . "\r\n";

    // adjuntar archivos
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: application/pdf; name=\"$file\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$file\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "\r\n" . $attachment . "\r\n";
    $body .= "--$boundary--";

    // enviar correo
    if (mail($to, $subject, $body, $headers)) {
        header("Location: ../../email/sent.html");
        $_SESSION['carrito'] = [];
        //echo "<script>window.close();</script>";
    } else {
        echo "Email sending failed.";
    }
?>
