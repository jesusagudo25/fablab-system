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
<html lang="es" class="overflow-y-scroll">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maquinas - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/tables/machines/fetchareas.js" defer></script>
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
                            <h5 class="font-bold uppercase text-gray-600" id="titulo-modal">Nuevo área</h5>
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
                        <div class="p-5 max-h-96 overflow-auto">
                                <label class="block text-sm">
                                    <span class="text-gray-800 font-medium">Nombre</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="text" name="name" placeholder="Ingrese el nombre del área" required>
                                    <span id="feedbackname" class="text-xs text-red-600 feed"></span>
                                </label>

                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800 font-medium">Unidad</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="measure">
                                        <option value="Horas">Horas</option>
                                        <option value="Gramos">Gramos</option>
                                        <option value="Pulgadas">Pulgadas</option>
                                    </select>
                                </label>

                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800 font-medium">Consumibles</span>
                                    <div class="relative">
                                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del consumible" name="documento" required="" type="text">
                                            <button id="agregar" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-r-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-purple">
                                            <i class="fas fa-plus"></i>
                                            </button>
                                    </div>
                                </label>

                                <div class="mt-5 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="w-full min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100 text-sm">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider w-1/2">Nombre</th>
                                                <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider w-1/4">Estado</th>
                                                <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider w-1/4">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y text-center divide-gray-200 text-sm" id="lista_consumible">
                                            <tr>                    
                                            <td class="px-4 py-4">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-medium">Electrónica</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4">
                                                <button class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                            </td>        

                                            <td class="px-4 py-4">
                                                <button class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <footer class="flex justify-end align-center border-t p-3">
                            <button class="mr-3 p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray close" type="button" name="cancelar" >Cancelar</button>
                            <button class="p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar">Guardar</button>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 min-height-100vh flex items-center justify-center h-screen hidden" id="modal-areas">
                <div class="w-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-new-bg opacity-75"> </div>
                    </div>
                    <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="border-b p-3 flex justify-between items-center">
                            <h5 class="font-bold uppercase text-gray-600">Editar areas</h5>
                            <button class="border border-transparent focus:border-blue trans-all-linear cancelar-areas" type="button">
                                <svg
                                        class="w-8 h-8 text-grey hover:text-grey-dark"
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-3 text-sm max-h-96 overflow-auto" id="modal-areas-content">
                            <span class="text-gray-800 font-medium">Seleccione las áreas de trabajo</span>

                            <span id="feedbackareas" class="inline-block mt-2 text-xs text-red-600 feed"></span>
                        </div>
                        <footer class="flex justify-end align-center border-t p-3">
                            <button class="mr-3 p-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray cancelar-areas" type="button" name="cancelar-areas" >Cancelar</button>
                            <button class="p-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar-areas">Actualizar</button>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Áreas de maquinas</h5>
                    </div>
                    <div class="flex justify-center items-center flex-col w-full overflow-auto">
                        <table id="datatable-json" class="min-w-full divide-y divide-white">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Unidad</th>
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