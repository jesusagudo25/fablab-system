<?php

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    use Dompdf\Dompdf;
    use Dompdf\Options;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $invoice = new Invoice();
    $invoice->getLastID();

    $user = new User();
    $user->get($invoice->getUserId());

    $customer = new Customer();
    $customer->get($invoice->getCustomerId());

    if($customer->getEmail()){

        $detalles = [];

        $membership_invoices = new MembershipInvoices();
        empty($membership_invoices->get($invoice->getInvoiceId())) ? '' : array_push($detalles,($membership_invoices->get($invoice->getInvoiceId())));

        $invoice_events = new InvoicesEvents();
        empty($invoice_events->get($invoice->getInvoiceId())) ? '' : array_push($detalles,$invoice_events->get($invoice->getInvoiceId()));

        $invoice_use_machines = new InvoicesUseMachines();
        empty($invoice_use_machines->get($invoice->getInvoiceId())) ? '' : array_push($detalles,($invoice_use_machines->get($invoice->getInvoiceId())));

        $rental_invoices = new RentalInvoices();
        empty($rental_invoices->get($invoice->getInvoiceId())) ? '' : array_push($detalles,($rental_invoices->get($invoice->getInvoiceId())));


        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);
        $mail = new PHPMailer(true);

        $html = '<!DOCTYPE html> 
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="https://explorando.xyz/FABLAB/assets/img/fab.ico" type="image/x-icon">
	<title>Informe mensual de octrubre</title>

	<!-- Invoice styling -->
	<style>
		body {
			font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
			text-align: center;
			color: #777;
		}

		body h1 {
			font-weight: 300;
			margin-bottom: 0px;
			padding-bottom: 0px;
			color: #000;
		}

		body h3 {
			font-weight: 300;
			margin-top: 10px;
			margin-bottom: 20px;
			font-style: italic;
			color: #555;
		}

		body a {
			color: #06f;
		}

		.invoice-box {
			max-width: 800px;
			margin: auto;
			font-size: 16px;
			line-height: 24px;
			font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
			color: #555;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			padding: 5px;
			vertical-align: top;
		}

		.invoice-box table tr td:nth-child(2) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.top table td.title {
			font-size: 45px;
			line-height: 45px;
			color: #333;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.heading td {
			background: #eee;
			border-bottom: 1px solid rgb(141, 141, 141);
			font-weight: bold;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		.invoice-box table tr.total td:nth-child(2) {
			border-top: 2px solid #eee;
			font-weight: bold;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: center;
			}

			.invoice-box table tr.information table td {
				width: 100%;
				display: block;
				text-align: center;
			}
		}
	</style>
</head>

<body>
	<div class="invoice-box">
		<table>
			<tr class="top">
				<td colspan="3">
					<table>
						<tr>
							<td class="title">
								<img src="https://explorando.xyz/FABLAB/assets/img/fab.jpg" alt="Company logo" style="width: 100%; max-width: 300px;" />
							</td>

							<td>Factura: '.$invoice->getInvoice().'<br/>
							    Fecha: '.$invoice->getDate().'</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="information">
				<td colspan="3">
					<table>
						<tr>
							<td>
								Cliente: '.$customer->getName().' <br />
								Correo: '.$customer->getEmail().' <br />
								Dirección: '.$customer->getProvince().', '. $customer->getCity().', '.$customer->getTownship().' <br />
								Telefono: '.$customer->getTelephone().' <br />
							</td>

							<td>
                                                Vendedor: ' . $user->getName() . ' ' . $user->getLastname() . '<br />
                                                Correo: ' . $user->getEmail() . '
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="border: 1px solid rgb(141, 141, 141);">
			<tbody>
				<tr class="heading">
					<td style="text-align: center; " width="50%">Descripción</td>
					<td style="text-align: center;" width="15%">Precio</td>
					<td style="text-align: center;" width="10%">Cantidad</td>
					<td style="text-align: center;" width="15%">Total</td>
				</tr>';

        $subtotal = 0;
        $itbmstotal = 0;
        foreach ($detalles as $tabla => $entidad) {
            foreach ($entidad as $registro => $valor){
                $html .= '
                    <tr class="item">
                        <td style="text-align: center;">' . $valor['name'] . '</td>
                        <td style="text-align: center;">' . $valor['price'] . '</td>
                        <td style="text-align: center;"> 1 </td>
                        <td style="text-align: center;">' . $valor['price'] . '</td>
                    </tr>';
                $subtotal += $valor['price'];
                $itbmstotal += $valor['price'] * 0.07;
            }
        }
        $html .= '</tbody>
			<tfoot>
				<tr class="heading">
					<td colspan="3" style="text-align: right;">SUBTOT</td>
					<td style="text-align: center;">'.number_format($subtotal,2).'</td>
				</tr>
				<tr class="heading">
					<td colspan="3" style="text-align: right;">ITBMS 07.00%</td>
					<td style="text-align: center;">'.number_format($itbmstotal,2).'</td>
				</tr>
				<tr class="heading">
					<td colspan="3" style="text-align: right;">TOTAL</td>
					<td style="text-align: center;">'.number_format($subtotal+$itbmstotal,2).'</td>
				</tr>
			</tfoot>
</table>
		
		<div style="
		text-align: center; margin-top: 50px; font-size: 14px">
		<hr>
		<p><i>La factura se creó en una computadora y es válida sin la firma y el sello.</i></p>
		</div>
		</body>
		</html>';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A3', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents('./factura_'.$invoice->getInvoice().'.pdf', $output);

        //-----

        $mail->AddEmbeddedImage('../../assets/img/fab.png', 'logo_e');
        $mail->AddEmbeddedImage('../../assets/img/check.png', 'logo_c');
        $mail->AddAttachment('./factura_'.$invoice->getInvoice().'.pdf', 'factura_'.$invoice->getInvoice().'.pdf');

        $html = '<!DOCTYPE html> 
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="https://explorando.xyz/FABLAB/assets/img/fab.ico" type="image/x-icon">
	<title>Notificación</title>
	<style>
 	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	img { -ms-interpolation-mode: bicubic; }
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
	a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
 	</style>
 	</head>
	<body style="background-color: #f7f5fa; margin: 0 !important; padding: 0 !important;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td bgcolor="#426899" align="center">
					<table border="0" cellpadding="0" cellspacing="0" width="480" >
						<tr>
							<td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
								<img src="cid:logo_e" alt="Company logo" style="filter: brightness(0) invert(1); width: 100%; max-width: 300px;" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#426899" align="center" style="padding: 0px 10px 0px 10px;">
					<table border="0" cellpadding="0" cellspacing="0" width="480" >
						<tr>
							<td bgcolor="#ffffff" align="left" valign="top" style="padding: 30px 30px 20px 30px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; line-height: 48px;">
								<h1 style="font-size: 32px; font-weight: 400; margin: 0;">Gracias por la compra</h1>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
					<table border="0" cellpadding="0" cellspacing="0" width="480" >
						<tr>
							<td bgcolor="#ffffff" align="left">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="2" style="padding-left:30px; padding-right:15px;padding-bottom:10px; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;">
                      <p>Conserve este correo electrónico, ya que contiene detalles sobre su compra. Le adjuntamos la factura correspondiente.</p>
                    </td>
                  </tr>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor="#ffffff" align="center">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr style="text-align: center;">
																		<img src="cid:logo_c" alt="Company logo" style="width: 20%; max-width: 300px;" />
                                </tr>
									<tr>
										<td bgcolor="#ffffff" align="center" style="padding: 30px 30px 30px 30px; border-top:1px solid #dddddd;">
											<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="left" style="border-radius: 3px;" bgcolor="#426899">
														<a href="#" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 11px 22px; border-radius: 2px; border: 1px solid #426899; display: inline-block;">Visita nuestra pagina web</a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;"> <table border="0" cellpadding="0" cellspacing="0" width="480">
					<tr>
						<td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
							<p style="margin: 0;">Todos los derechos reservados.
								 "<a href="https://company.de" target="_blank" style="color: #111111; font-weight: 700;">FABLAB<a>".</p>
						</td>
					</tr>
				</td>
			</tr>
		</table>
	
	</body>
		</html>';

        $mail->SMTPDebug = SMTP::DEBUG_OFF;

        $mail->isSMTP();

        $mail->Host = "mail.jagudodeveloper.educationhost.cloud";

        $mail->SMTPAuth = true;

        $mail->Username = "support@jagudodeveloper.educationhost.cloud";
        $mail->Password = "Panama09";

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Port = 587;

        $mail->From = "support@jagudodeveloper.educationhost.cloud";
        $mail->FromName = "FabLab";

        $mail->addAddress($customer->getEmail(), $customer->getName());

        $mail->isHTML(true);

        $mail->Subject = "Su compra en Fablab #".$invoice->getInvoice();
        $mail->Body = $html;

        $mail->CharSet = 'UTF-8';

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        unlink('./factura_'.$invoice->getInvoice().'.pdf');
    }



