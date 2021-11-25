<?php

    require_once '../../app.php';

    session_start();

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    use Dompdf\Dompdf;
    use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $invoice = new Invoice();
    $invoice->get($_REQUEST['factura']);

    $user = new User();
    $user->get($invoice->getUserId());

    $customer = new Customer();
    $customer->get($invoice->getCustomerId());

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

    $html = '<!DOCTYPE html> 
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="https://explorando.xyz/FABLAB/assets/img/fab.ico" type="image/x-icon">
	<title>Factura #'.$invoice->getInvoice().'</title>

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

							<td>Factura: #'.$invoice->getInvoice().'<br/>
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

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $fecha = new DateTime();
    $filename = $fecha->getTimestamp().'_'.'sales_'.$invoice->getInvoice();

    // Output the generated PDF to Browser
    $dompdf->stream($filename, array("Attachment" => 0));
}