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

    $report = new Report();
    $report->get($_REQUEST['reporte']);

    #Total  de visitantes
    $visits = new Visit();
    $visitasParaUsoEquipos = $visits->getVisitsForMachine($report->getStartDate(), $report->getEndDate());
    $visitasParaServicio = $visits->getVisitsForService($report->getStartDate(), $report->getEndDate());

    #Total de horas entre todos los visitantes
    $timeTotal = $visits->getTimeDifTotal($report->getStartDate(), $report->getEndDate());

    #Tabla 1 - Uso por áreas de trabajo
    $areasTime = $visits->getTimeDifAreas($report->getStartDate(), $report->getEndDate());

    #Tabla 2 - Uso por razon de visita
    $razonesTime = $visits->getTimeDifRazones($report->getStartDate(), $report->getEndDate());

    #Observaciones generales...
    $observations = new Observation();
    $observationsAll = $observations->getObsMonth($report->getStartDate(), $report->getEndDate());

    $user = new User();
    $user->get($report->getUserId());

    $customer = new Customer();
    $cantidadPorRango= $customer->getAllAgeRange($report->getStartDate(), $report->getEndDate());
    $cantidadPorSexo= $customer->getAllTypeSex($report->getStartDate(), $report->getEndDate());

    $invoice = new Invoice();
    $totalSales = $invoice->getTotalSales($report->getStartDate(), $report->getEndDate());
    $AmountSales = $invoice->getAmountSales($report->getStartDate(), $report->getEndDate());

    $salesForUseMachine = $invoice->getSalesForUseMachine($report->getStartDate(), $report->getEndDate());
    $salesForEvent = $invoice->getSalesForEvent($report->getStartDate(), $report->getEndDate());
    $salesForMembership = $invoice->getSalesForMembership($report->getStartDate(), $report->getEndDate());

    $salesForUseMachineGroupByArea = $invoice->getSalesForUseMachineGroupByArea($report->getStartDate(), $report->getEndDate());
    $salesForUseSoftware = $invoice->getSalesForUseSoftware($report->getStartDate(), $report->getEndDate());
    $amountForUseType = $invoice->getAmountForUseType($report->getStartDate(), $report->getEndDate());

    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);

    // instantiate and use the dompdf class
    $dompdf = new Dompdf($options);

    $html = '<!DOCTYPE html> <html lang="es">
                <head>
                    <meta charset="utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1" />
                        <link rel="icon" href="https://explorando.xyz/FABLAB/assets/img/fab.ico" type="image/x-icon">
                    <title>Informe mensual de ' . strtolower($report->getMonth()) . '</title>
                
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

                        .page_break { page-break-before: always; }
                
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
                
                                            <td>Informe mensual de ' . strtolower($report->getMonth()) . ' '. $report->getYear().'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                
                            <tr class="information">
                                <td colspan="3">
                                    <table>
                                        <tr>
                                            <td>
                                                Total de visitas por uso de equipos: ' . $visitasParaUsoEquipos . '<br />
                                                Total de visitas por servicio: ' . $visitasParaServicio . '<br />
                                                Total de horas entre todos los visitantes por uso de equipos: ' . $timeTotal . '<br />
                                                Total de reservaciones: 0 <br />
                                            </td>
                
                                            <td>
                                                ' . $user->getName() . '<br />
                                                ' . $user->getEmail() . '
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                
                        <table style="border: 1px solid rgb(141, 141, 141);">
                            
                            <tr class="heading">
                                <td style="text-align: center; " width="50%">Áreas de trabajo</td>
                                <td style="text-align: center;" width="20%">Horas</td>
                                <td style="text-align: center;" width="30%">% del total de horas</td>
                            </tr>';
    foreach ($areasTime as $datos => $valor) {

        try {
            $porcentaje = number_format(((int)$valor['diferencia'] / (int)$timeTotal) * 100, 0);
        }catch(DivisionByZeroError $e){
            $porcentaje = 0;
        }

        $html .= '
                            <tr class="item">
                                <td style="text-align: center;">' . $valor['name'] . '</td>
                                <td style="text-align: center;">' . $valor['diferencia']. '</td>
                                <td style="text-align: center;">' .$porcentaje. '</td>
                            </tr>';
    }
    $html .= '</table>
                
                        <table style="margin-top: 30px; border: 1px solid rgb(141, 141, 141);">
                            <tr class="heading">
                                <td style="text-align: center; " width="50%">Razón de visita</td>
                                <td style="text-align: center;" width="20%">Horas</td>
                                <td style="text-align: center;" width="30%">% del total de horas</td>
                            </tr>';

    foreach ($razonesTime as $datos => $valor) {

        try {
            $porcentaje = number_format(((int)$valor['diferencia'] / (int)$timeTotal) * 100, 0);
        }catch(DivisionByZeroError $e){
            $porcentaje = 0;
        }

        if($valor['time'] == 1){
            $html .= '   <tr class="item">
                                <td style="text-align: center;">' . $valor['name'] . '</td>
                                <td style="text-align: center;">' . $valor['diferencia'] . '</td>
                                <td style="text-align: center;">' . $porcentaje . '</td>
                            </tr>';
        }

    }
    $html .= '</table>
                        <table style="margin-top: 30px;" >
                        <tr>
                            <td width="40%">	<table style="border: 1px solid rgb(141, 141, 141);">
	
		<tr class="heading">
			<td style="text-align: center; " width="70%">Rangos de edad</td>
			<td style="text-align: center;" width="30%">Cantidad</td>
		</tr>';

    foreach ($cantidadPorRango as $datos => $valor) {

        $html .= '<tr class="item" >
			<td style = "text-align: center;" > ' . $valor['name'] . ' </td >
			<td style = "text-align: center;" > ' . $valor['total']. ' </td >
		</tr >';

        }

    $html .= '</table></td>
                        	<td width="40%" >	<table style="border: 1px solid rgb(141, 141, 141);">
	
		<tr class="heading">
			<td style="text-align: center; " width="70%">Tipo de sexo</td>
			<td style="text-align: center;" width="30%">Cantidad</td>
		</tr>';

		$html .= '<tr class="item">
			<td style = "text-align: center;" > F </td >
			<td style = "text-align: center;" > ' . $cantidadPorSexo[0]['F']. ' </td >
		</tr>
		<tr class="item">
				         <td style = "text-align: center;" > M </td >
			<td style = "text-align: center;" > ' . $cantidadPorSexo[0]['M']. ' </td >
	</tr>
	</table></td>
                        </tr>

                        </table>
                        
                        <div style="
                        text-align: left; margin-top: 30px; font-size: 16px;">
                        <span>Observaciones:</span>
                        <ul>';
    if(count($observationsAll)){
        foreach ($observationsAll as $datos => $valor) {
                $html .= '   <li style="text-align: justify">' . $valor['description'] . ' - ' . $valor['name'] . ' , ' . $valor['date'] . '</li>';
            }
    }
    else{
        $html .= '   <li>'.'Sin observaciones'.'</li>';
    }

    $html .= '</ul></div>
    </div>

    <div class="page_break"></div>
        <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="3">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="https://explorando.xyz/FABLAB/assets/img/fab.jpg" alt="Company logo" style="width: 100%; max-width: 300px;" />
                            </td>

                            <td>Informe mensual de ' . strtolower($report->getMonth()) . ' '. $report->getYear().'</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="3">
                    <table>
                        <tr>
                            <td>
                                Cantidad de ventas procesadas: ' . $AmountSales . '<br />
                                Cantidad de cotizaciones generadas: 0 <br />
                            </td>

                            <td>
                                ' . $user->getName() . '<br />
                                ' . $user->getEmail() . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="border: 1px solid rgb(141, 141, 141);">                 
            <tr class="heading">
                <td style="text-align: center; " width="70%">Servicios</td>
                <td style="text-align: center;" width="30%">Ingresos</td>
            </tr>
            <tr class="item">
                <td style="text-align: center;">Membresías</td>
                <td style="text-align: center;">' . $salesForMembership. '</td>
            </tr>
            <tr class="item">
                <td style="text-align: center;">Eventos</td>
                <td style="text-align: center;">' . $salesForEvent. '</td>
            </tr>
            <tr class="item">
                <td style="text-align: center;">Uso de equipos</td>
                <td style="text-align: center;">' . $salesForUseMachine. '</td>
            </tr>
        </table>
        
        <table style="margin-top: 30px; border: 1px solid rgb(141, 141, 141);">
            <tr class="heading">
                <td style="text-align: center; " width="70%">Maquinas</td>
                <td style="text-align: center;" width="30%">Ingresos</td>
            </tr>';

    foreach ($salesForUseMachineGroupByArea as $datos => $valor) {

        $html .= '<tr class="item" >
			<td style = "text-align: center;" > ' . $valor['name'] . ' </td >
			<td style = "text-align: center;" > ' . $valor['total']. ' </td >
		</tr >';

        }
        $html .= '
        <tr class="item" >
			<td style = "text-align: center;" > Software de diseño </td >
			<td style = "text-align: center;" > ' . $salesForUseSoftware. ' </td >
		</tr >
        
        </table>

        <table style="margin-top: 30px; border: 1px solid rgb(141, 141, 141);">
            <tr class="heading">
                <td style="text-align: center; " width="70%">Tipo de ventas</td>
                <td style="text-align: center;" width="30%">Cantidad</td>
            </tr>
            <tr class="item">
                <td style="text-align: center;">Maker</td>
                <td style="text-align: center;">' . $amountForUseType[0]['M']. '</td>
            </tr>
            <tr class="item">
                <td style="text-align: center;">Servicios</td>
                <td style="text-align: center;">' . $amountForUseType[0]['S']. '</td>
            </tr>
        </table>

        <div style="margin-top: 55px; font-style: italic; color: #777;">
            <h1>                                Total de ingresos </h1>
            <h1>' . $totalSales . '</h1>
        </div>
        </body>
        </html>';

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A3', 'portrait');

    $dompdf->render();

    $fecha = new DateTime();
    $filename = $fecha->getTimestamp().'_'.'reporte_'.$report->getMonth();

    // Output the generated PDF to Browser
    $dompdf->stream($filename, array("Attachment" => 0)); 
}