<?php
    session_start();

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    require_once '../../app.php';

    $pagina[] = "sales";

    $area = new Area();
    $areaAll = $area->getAjax();

?>
<!DOCTYPE html>
<html lang="es" class="overflow-y-scroll">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ventas - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.2.0/build/global/luxon.min.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="<?= constant('URL')?>assets/js/templates/basetemplate.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/app/sales.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/app/savesales.js" defer></script>
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
                        <h5 class="font-bold uppercase text-gray-600">Datos del cliente</h5>
                    </div>
                    <div class="p-5 flex justify-between flex-wrap items-start">
                        <label class="text-sm w-1/4">
                            <span class="text-gray-800 font-medium">Seleccione el tipo de documento</span>
                            <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                <option value="R">RUC</option>
                                <option value="C">Cédula</option>
                                <option value="P">Pasaporte</option>
                            </select>
                        </label>

                        <label class="text-sm w-1/3">
                            <span class="text-gray-800 font-medium" id="tituloDocumento">Numero de RUC</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de RUC con guiones" name="documento" required type="text" id="autoComplete" autocomplete="false">
                                <input type="hidden" name="id_customer">
                                <span id="feedbackdocumento" class="text-xs text-red-600 "></span>
                        </label>

                        <label class="text-sm w-1/4">
                            <span class="text-gray-800 font-medium">Nombre de visitante</span>
                            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" placeholder="Sin cliente seleccionado" type="text" name="name" disabled>
                        </label>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden min-height-100vh flex items-center justify-center h-screen" id="modal">
                <div class="w-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-new-bg"> </div>
                    </div>
                    <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="border-b p-3 flex justify-between items-center">
                            <h5 class="font-bold uppercase text-gray-600">Detalles del servicio</h5>
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
                        <div class="p-5" id="modal-content">

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
                <div class="bg-white border rounded shadow overflow-auto">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Datos de venta</h5>
                    </div>
                    <div class="p-5">

                        <div class="flex justify-between flex-wrap items-center mb-5">
                        <label class="w-2/5 text-sm">
                            <span class="text-gray-800 font-medium">Seleccione la fecha</span>
                            <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha" required="">
                            <span id="feedbackfecha" class="text-xs text-red-600"></span>
                        </label>
                        </div>
                        <div class="flex justify-between flex-wrap items-center mb-5">
                            <label class="text-sm w-2/5">
                                <span class="text-gray-800 font-medium">Seleccione la categoría del servicio</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="categoria_servicio">
                                    <option value="membresias">Membresías</option>
                                    <option value="eventos">Eventos</option>
                                    <option value="areas" selected>Maquinas</option>
                                    <option value="alquiler">Alquiler</option>
                                </select>
                            </label>

                            <label class="text-sm w-2/5">
                                <span class="text-gray-800 font-medium">Seleccione el servicio</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="servicio">
                                    <?php foreach ($areaAll as $datos => $valor): ?>
                                    <option value="<?= $valor['id'] ?>"><?=$valor['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>

                            <button type="button" class="self-end px-4 py-2 text-base font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none w-1/12" id="agregar"><i class="fas fa-plus"></i></button>
                        </div>

                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded">
                            <table class="w-full min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider"></th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Precio</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Acción</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="detalle_venta">

                                </tbody>
                                <tfoot class="hidden bg-gray-100 divide-y divide-gray-200" id="detalle_totales">
                                <tr>
                                    <td class="px-4 py-3 text-sm font-semibold"></td>
                                    <td colspan="3" class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">TOTAL</td>
                                    <td class="px-4 py-3 text-sm font-semibold"></td>
                                    <td class="px-4 py-3 text-sm font-semibold"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-5 hidden flex items-center justify-center" id="acciones">
                            <button class="mt-3 flex items-center justify-between mr-5 px-4 py-2 text-sm font-semibold uppercase leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 focus:outline-none" id="anular">
                                <i class="fas fa-times mr-3"></i>
                                <span>Anular</span>
                            </button>
                            <button class="mt-3 flex items-center justify-between px-4 py-2 text-sm font-semibold uppercase leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none" id="generar">
                                <i class="fas fa-save mr-3"></i>
                                <span>Generar venta</span>
                            </button>
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

</body>
</html>