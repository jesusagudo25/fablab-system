<!DOCTYPE html>
<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../app.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$pagina[] = "reports";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report = new Report();

    if(isset($_REQUEST['generar'])){



        #Mes del reporte o fecha
        setlocale(LC_TIME, "spanish");
        $currentDate = strftime("%B");

        #Total  de visitantes
        $first_day_this_month = date('Y-m-01');
        $day_actual_month = date('Y-m-d');

        $visits = new Visit();
        $visitasNoFree = $visits->getVisitsNotFree();
        $visitasFree = $visits->getVisitsFree();

        $fecha = new DateTime();
        $nombreArchivo = $fecha->getTimestamp().'_'.'reporte_'.$day_actual_month;

        #Total de horas entre todos los visitantes
        $timeTotal = $visits->getTimeDifTotal();

        #Tabla 1 - Uso por áreas de trabajo
        $areasTime = $visits->getTimeDifAreas();

        #Tabla 2 - Uso por razon de visita
        $razonesTime = $visits->getTimeDifRazones();

        #Observaciones generales...
        $observations = new Observation();
        $observationsAll = $observations->getObsMonth();

        session_start();
        $user = new User();
        $resultUser = $user->get($_SESSION['user_id']);

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);

        $html = '<!DOCTYPE html> <html lang="es">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
            
                <title>Informe mensual de '.$currentDate.'</title>
            
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
                                            <img src="https://fablab-system.herokuapp.com/assets/img/fab.jpg" alt="Company logo" style="width: 100%; max-width: 300px;" />
                                        </td>
            
                                        <td>Informe mensual de '.$currentDate.'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
            
                        <tr class="information">
                            <td colspan="3">
                                <table>
                                    <tr>
                                        <td>
                                            Total de visitantes por razones sociales: '.$visitasFree.'<br />
                                            Total de visitantes por razones economicas: '.$visitasNoFree['total'].'<br />
                                            Total de horas entre todos los visitantes por razones economicas: '.$timeTotal['total'].'
                                        </td>
            
                                        <td>
                                            '.$resultUser['name']. ' '. $resultUser['lastname'].'<br />
                                            '.$resultUser['email'].'
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
        foreach ($areasTime as $datos => $valor){
            $html.='
                        <tr class="item">
                            <td style="text-align: center;">'.$valor['name'].'</td>
                            <td style="text-align: center;">'.$valor['diferencia'].'</td>
                            <td style="text-align: center;">'.number_format(((int)$valor['diferencia']/ (int)$timeTotal['total']) * 100 ,0).'</td>
                        </tr>';
        }
        $html.='</table>
            
                    <table style="margin-top: 30px; border: 1px solid rgb(141, 141, 141);">
                        <tr class="heading">
                            <td style="text-align: center; " width="50%">Razón de visita</td>
                            <td style="text-align: center;" width="20%">Horas</td>
                            <td style="text-align: center;" width="30%">% del total de horas</td>
                        </tr>';

        foreach ($razonesTime as $datos => $valor){
            $html.='   <tr class="item">
                            <td style="text-align: center;">'.$valor['name'].'</td>
                            <td style="text-align: center;">'.$valor['diferencia'].'</td>
                            <td style="text-align: center;">'.number_format(((int)$valor['diferencia']/ (int)$timeTotal['total']) * 100 ,0).'</td>
                        </tr>';
        }
        $html.='</table>
                    
                    <div style="
                    text-align: left; margin-top: 30px; font-size: 16px">
                    <span>Observaciones:</span>
                    <ul>';
        foreach ($observationsAll as $datos => $valor) {
            $html .= '   <li>'.$valor['description']. ' - '.$valor['name'].' , '.$valor['date'].'</li>';
        }
        $html.='</ul></div>
                </div>
            </body>
            </html>';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A3', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents('../documents/'.$nombreArchivo.'.pdf', $output);

        $report->save(ucfirst($currentDate),$resultUser['user_id'],$nombreArchivo);

    } else if(isset($_REQUEST['borrar'])){
        if(file_exists($_REQUEST['direccion'])){
            unlink($_REQUEST['direccion']);
        }

        $report->delete($_REQUEST['borrar']);
    }

    header("Location: index.php");

}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reportes - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="../../assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<?php require_once '../templates/header.php'; ?>

<!--Container-->
<div class="container w-10/12 mx-auto pt-20">

    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

        <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full p-3">
                <!--Graph Card-->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <button value="reporte" type="submit" name="generar" class="w-2/6 px-4 py-2 text-sm font-bold uppercase leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none"><i class="fas fa-chart-bar fa-fw mr-3"></i> Generar reporte</button>
                </form>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Reportes</h5>
                    </div>
                    <div>
                        <div class="text-sm mt-5 w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap table">

                            </table>
                        </div>

                    </div>
                </div>
                <!--/Graph Card-->
            </div>

        </div>

        <!--/ Console Content-->

    </div>


</div>
<!--/container-->

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="<?= constant('URL')?>assets/js/fetchreports.js"></script>
<script src="<?= constant('URL')?>assets/js/basetemplate.js"></script>

</body>
</html>