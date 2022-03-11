<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../../app.php';

$pagina[] = "gestionar";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eventos - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/tables/events/fetchevents.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/templates/basetemplate.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<?php require_once '../../templates/header.php'; ?>

<!--Container-->
<div class="container w-10/12 mx-auto pt-20">

    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

        <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden min-height-100vh flex items-center justify-center h-screen" id="modal">
                <div class="w-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-new-bg opacity-75"> </div>
                    </div>
                    <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="border-b p-3 flex justify-between items-center">
                            <h5 class="font-bold uppercase text-gray-600" id="titulo-modal">Nuevo evento</h5>
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
                        <div class="p-5 grid grid-cols-2 gap-5 max-h-96 overflow-auto">

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Nombre</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="text" placeholder="Ingrese el nombre del evento" name="nombre_evento" value="">
                                <span id="feedbacknombre" class="text-xs text-red-600 feed"></span>
                            </label>
                            
                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Precio</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese el precio del evento" name="precio" min="0.00" step="0.01" value="">
                                <span id="feedbackprecio" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Área</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="area"></select>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Categoría</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="categoria"></select>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Fecha inicial</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_inicial" value="">
                                <span id="feedbackinitialdate" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Fecha final</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_final" value="">
                                <span id="feedbackfinaldate" class="text-xs text-red-600 feed"></span>
                            </label>

                            </label><label class="text-sm block">
                                <span class="text-gray-800 font-medium">Hora inicial</span>
                                <input type="time" name="hora_inicial" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                <span id="feedbackstarttime" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Hora final</span>
                                <input type="time" name="hora_final" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                <span id="feedbackendtime" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Gastos</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese el gasto total del evento" name="gastos_evento" min="0.00" step="0.01" value="">
                                <span id="feedbackgastos" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Descripción de gastos</span>
                                <textarea class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Describa los gastos del evento" name="desc_gastos"></textarea>
                            </label>
                        </div>
                        <footer class="flex justify-end align-center border-t p-3">
                            <button class="mr-3 p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray close" type="button" name="cancelar" >Cancelar</button>
                            <button class="p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar">Guardar</button>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Eventos</h5>
                    </div>
                    <div class="flex justify-center items-center flex-col w-full">
                        <div class="w-full flex pl-2 pt-2 pr-2">
                            <button id="evento" class="w-1/5 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Nuevo evento<i class="fas fa-calendar-alt fa-fw ml-3"></i></button>

                            <a href="./categories.php" class="w-1/4 ml-2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none">Gestionar categorias<i class="fas fa-cogs fa-fw ml-3"></i></a>
                        </div>

                        <table id="datatable-json" class="min-w-full divide-y divide-white">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Categoria</th>
                                <th>Nombre</th>
                                <th>Fecha inicial</th>
                                <th>Fecha final</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>