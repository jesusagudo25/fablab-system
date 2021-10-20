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

$pagina[] = "gestionar";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report = new Report();

    if(isset($_REQUEST['generar'])){

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);

        $dompdf = new Dompdf($options);

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
        $observationsAll = $observations->getAll();

        session_start();
        $user = new User();
        $resultUser = $user->get($_SESSION['user_id']);


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
    <title>Observaciones - Fablab System</title>
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
                    <button id="observacion" class="w-2/6 px-4 py-2 text-sm font-bold uppercase leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none"><i class="fas fa-sticky-note fa-fw mr-3"></i> Nueva observación</button>
                <!--/Graph Card-->
            </div>

            <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden" id="modal">
                <div class="flex items-center justify-center min-height-100vh pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-current opacity-75"> </div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="border-b p-3 flex justify-between items-center">
                            <h5 class="font-bold uppercase text-gray-600">Nueva observación</h5>
                            <button class="border border-transparent focus:border-blue trans-all-linear close" type="button">
                                <svg
                                        class="w-8 h-8 text-grey hover:text-grey-dark"
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                </svg>
                            </button>
                        </div>
                        <p class="p-5">
                                <label class="block text-sm">
                                    <span class="text-gray-800">Observación</span>
                                    <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Descripción" name="descripcion" ></textarea>
                                </label>

                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800">Fecha</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha" required></input>
                                </label>

                                <label id="containerestado" class="block text-sm hidden mt-5">
                                    <span class="text-gray-800">Estado</span>
                                    <select required name="estado" class="mt-1 text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                    </select>
                                </label>

                        </p>
                        <footer class="flex justify-end align-center border-t p-3">
                            <button class="mr-3 p-3 text-sm font-semibold leading-5 text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray close" type="button" name="cancelar" >Cancelar</button>
                            <button class="p-3 text-sm font-semibold leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar">Guardar</button>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Observaciones</h5>
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
<script src="<?= constant('URL')?>assets/js/fetchobservations.js"></script>
<script src="<?= constant('URL')?>assets/js/basetemplate.js"></script>

</body>
</html>