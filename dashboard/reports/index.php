<!DOCTYPE html>
<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../app.php';

$pagina[] = "reports";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report = new Report();

    if (isset($_REQUEST['borrar'])) {
        $report->delete($_REQUEST['borrar']);
    }
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
    <link rel="icon" href="../../assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="../../assets/css/tailwind.output.css" rel="stylesheet">
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
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Generar</h5>
                    </div>
                    <div class="p-5">
                            <form>
                        <span class="text-gray-800 text-sm ">Rango de fechas</span>
                        <div date-rangepicker datepicker-autohide datepicker-format="yyyy/mm/dd" class="flex flex-wrap items-center justify-between">
                                <label class="text-sm w-1/4 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 datepicker pl-10 p-2.5" type="text" name="start_date" placeholder="Seleccionar fecha de inicio" required>
                                </label>

                                <div class="text-blue-500 self-center mt-1 p-3 rounded-full bg-blue-100 mx-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>

                                <label class="text-sm w-1/4 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 datepicker pl-10 p-2.5" type="text" name="end_date" placeholder="Seleccionar fecha de finalización" required>
                                </label>

                                <button value="reporte" type="submit" name="generar" class="w-1/3 ml-4 mt-1 self-center px-4 py-2 text-sm font-bold uppercase leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none"><i class="fas fa-chart-bar fa-fw mr-3"></i> Generar reporte
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="<?= constant('URL')?>assets/js/fetchreports.js"></script>
<script src="<?= constant('URL')?>assets/js/basetemplate.js"></script>

</body>
</html>