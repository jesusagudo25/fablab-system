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
							    Fecha: #'.$invoice->getDate().'</td>
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

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents('./factura_'.$invoice->getInvoice().'.pdf', $output);

        //-----

        $mail->AddEmbeddedImage('../../assets/img/flp.png', 'logo_e');
        $mail->AddEmbeddedImage('../../assets/img/fab.jpg', 'logo_c');
        $mail->AddAttachment('./factura_'.$invoice->getInvoice().'.pdf', 'factura_'.$invoice->getInvoice().'.pdf');

        $html = '<!DOCTYPE html>
<html lang="es">

<head>
  <title>Salted | A Responsive Email Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class="wrapper"]{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class="logo"]{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class="logo"] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class="mobile-hide"]{
          display:none;}

        img[class="mobile-hide"]{
          display: none !important;
        }

        img[class="img-max"]{
          max-width: 100% !important;
          height:auto !important;
        }

        /* FULL-WIDTH TABLES */
        table[class="responsive-table"]{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class="padding"]{
          padding: 10px 5% 15px 5% !important;
        }

        td[class="padding-copy"]{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class="padding-meta"]{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class="no-pad"]{
          padding: 0 0 20px 0 !important;
        }

        td[class="no-padding"]{
          padding: 0 !important;
        }

        td[class="section-padding"]{
          padding: 50px 15px 50px 15px !important;
        }

        td[class="section-padding-bottom-image"]{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class="mobile-wrapper"]{
            padding: 10px 5% 15px 5% !important;
        }

        table[class="mobile-button-container"]{
            margin:0 auto;
            width:100% !important;
        }

        a[class="mobile-button"]{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
  </style>
</head>

<body style="margin: 0; padding: 0;">

  <!-- HEADER -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff">
        <div align="center" style="padding: 0px 15px 0px 15px;">
          <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
            <!-- LOGO/PREHEADER TEXT -->
            <tr>
              <td style="padding: 20px 0px 30px 0px;" class="logo">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td bgcolor="#ffffff" width="100" align="left" style="vertical-align: middle">
                      <a href="#" target="_blank"><img alt="Logo" src="cid:logo_c" width="140" height="50" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a>
                    </td>
                    <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide" style="vertical-align: middle">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr style="vertical-align: middle">
                          <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;">Hola '.$customer->getName().'
							<br>Factura: #'.$invoice->getInvoice().'
							</span></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>

  <!-- ONE COLUMN SECTION -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff" align="center" style="padding: 70px 15px 70px 15px;" class="section-padding">
        <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <!-- HERO IMAGE -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td class="padding-copy">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="text-align: center;">
                                  <a href="#" target="_blank" style="text-align: center;"><img src="cid:logo_e" width="70%" height="200" border="0" alt="Can an email really be responsive?" style="display: inline-block; padding: 0; color: #666666; text-decoration: none; font-family: Helvetica, arial, sans-serif; font-size: 16px;"
                                    class="img-max"></a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <!-- COPY -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy">Gracias por comprar con nosotros</td>
                      </tr>
                      <tr>
                        <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Conserve este correo electrónico, ya que contiene detalles sobre su compra. Le adjuntamos la factura correspondiente.</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <!-- BULLETPROOF BUTTON -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                      <tr>
                        <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                          <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                            <tr>
                              <td align="center"><a href="#" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;"
                                class="mobile-button">Visita nuestro sitio web &rarr;</a></td>
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
        </table>
      </td>
    </tr>
  </table>

  <!-- FOOTER -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td style="padding: 20px 0px 20px 0px;">
              <!-- UNSUBSCRIBE COPY -->
              <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                <tr>
                  <td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                    <span class="appleFooter" style="color:#666666;">Canto del Llano, Santiago, Veraguas, Panamá</span>
                    <br>
                    <a
                    style="color: #666666; text-decoration: none;">FABLAB CIDETE CRUV</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
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



