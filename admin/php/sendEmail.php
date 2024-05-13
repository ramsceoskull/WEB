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
	$user = mysqli_query($connection,"SELECT * FROM usuario WHERE id = '$id'");
	$userProfile = mysqli_fetch_array($user);

    $_SESSION['email'] = $userProfile['email'];


    require('../lib/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm', 'A4');
    $x = 10;
    $y = 10;

    $pdf->AddPage();
    $pdf->SetXY($x, $y);
    $pdf->Image('../../assets/logo2.png',150,20,33,0,'PNG','index.php');
    $pdf->SetFont('Courier','B',16);
    $pdf->SetFillColor(255,196,102);
    $pdf->SetDrawColor(255,255,255);

    // green: rgb(131,164,102);
    // black: rgb(0, 0, 0);
    $pdf->SetTextColor(131,164,102);

    $pdf->SetXY(25,$y+5);
    $pdf->SetFontSize(35);
    $pdf->Cell(150,10,'RECIBO',0,0,'L',0);
    $pdf->SetXY(25,$y+25);
    $pdf->SetFontSize(9);
    $pdf->SetTextColor(0,0,0);

    // Datos genéricos
    $pdf->Cell(60,0,'DE: GRUBI');
    $fecha = Date("d-m-Y");
    $pdf->Cell(0,0,'FECHA DE EXPEDICION: '.$fecha.'');
    $pdf->SetXY(25,38);
    $pdf->Cell(60,5,'TELEFONO: 3322556155');
    $pdf->Cell(60,5,'CORREO: a22310355@ceti.mx');
    //Fin datos genéricos

    //Datos del comprador
    $pdf->SetXY(25,50);
    $pdf->Cell(60,10,'PARA:');
    $pdf->Cell(60,10,'EMAIL:');
    $pdf->SetXY(25,53);
    $pdf->SetTextColor(131,164,102);
    $pdf->Cell(60,15, $userProfile['nombres'].' '.$userProfile['apellidoP']);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(60,15, $_SESSION['email']);
    $pdf->SetXY(25,56);

    // Datos de la compra
    $y = 40;
    // $pdf->SetDrawColor(0,0,0):
    $pdf->SetDrawColor(131,164,102);
    $pdf->SetFillColor(131,164,102);

    $pdf->SetXY(25,85);
    $pdf->Cell(40,10,'Modelo de maceta',1,0,'c', true);
    $pdf->Cell(20,10,'Precio ',1,0,'c', true);
    $pdf->Cell(20,10,'Cantidad',1,0,'c', true);
    $pdf->Cell(20,10,'IVA',1,0,'c', true);
    $pdf->Cell(25,10,'Subtotal',1,1,'c', true);

    $total = 0;

    // Verificar si el carrito está definido
    if (isset($_SESSION['carrito'])) {
        $pdf->SetXY(25, 95);
        foreach ($_SESSION['carrito'] as $btn_sku => $maceta) {
            $pdf->SetX(25);
            $pdf->Cell(40, 5, $maceta['nombre'], 1, 0, "L");
            $pdf->Cell(20, 5, "$" . $maceta['precio'], 1, 0, "L");
            $pdf->Cell(20, 5, $maceta['cantidad'], 1, 0, "L");

            /* $iva = $maceta['subtotal'] * 0.16;

            $pdf->Cell(20, 5, "$" . $iva, 1, 0, "L");
            $pdf->Cell(25, 5, "$" . $maceta['subtotal'], 1, 1, "L");

            $total += $maceta['subtotal'] + $iva; */
        }

        /* $IVA = $total * 0.16;
        $sub = $total + $IVA;

        $pdf->SetXY(25, 170);
        $pdf->Cell(30, 5, "TOTAL:", 0, "L");
        $pdf->SetXY(50, 170);
        $pdf->Cell(30, 5, " $ " . $total, "", 0, "L");

        $pdf->SetXY(25, 175);
        $pdf->Cell(30, 5, "IVA:", 0, "L");
        $pdf->SetXY(50, 175);
        $pdf->Cell(30, 5, " $ " . $IVA, "", 0, "L");

        $pdf->SetXY(25, 180);
        $pdf->Cell(30, 5, "SUBTOTAL:", 0, "L");
        $pdf->SetXY(50, 180);
        $pdf->Cell(30, 5, " $ " . $sub, "", 0, "L"); */
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
    $title = 'COMPRA ' .$userProfile['nombres'] . " ". Date("F_j_Y");

    //Output the document
    // $pdf->Output('I', $title . ".pdf");
    $pdf->Output('F', $title . ".pdf");

    // Configuración email
    $to = $_SESSION['email'];
    // $from = 'no-reply@tenko.com]';
    $from = "a22310355@ceti.mx";
    $subject = $title;
    $msg = 'Se adjuntan los detalles de su compra en Detalles con Corazón';

    // archivos adjuntos
    $file = $title . ".pdf";
    $attachment = chunk_split(base64_encode(file_get_contents($file)));
    $boundary = md5(date('r', time()));

    // encabezado
    $headers = "From: PATY <$from>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Return-path: $to\r\n";
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
        header("Location: ../index.php");
        $_SESSION['carrito'] = [];
        //echo "<script>window.close();</script>";
    } else {
        echo "Email sending failed.";
    }
?>
