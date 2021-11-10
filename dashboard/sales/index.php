<!DOCTYPE html>
<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../app.php';

$pagina[] = "sales";

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
    <title>Ventas - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="../../assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="../../assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
                    <div class="p-5 flex justify-between flex-wrap items-center">
                        <label class="text-sm w-1/4">
                            <span class="text-gray-800">Seleccione el tipo de documento</span>
                            <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                <option value="R">RUC</option>
                                <option value="C">Cédula</option>
                                <option value="P">Pasaporte</option>
                            </select>
                        </label>

                        <label class="text-sm w-1/3">
                            <span class="text-gray-800" id="tituloDocumento">Numero de RUC</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de RUC con guiones" name="documento" required type="text" id="autoComplete" autocomplete="false">
                                <input type="hidden" name="id_customer">
                            <span id="feedbackdocumento" class="text-xs text-red-600 hidden">Por favor, proporcione un RUC</span>
                        </label>

                        <label class="text-sm w-1/4">
                            <span class="text-gray-800">Nombre</span>
                            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" placeholder="Sin cliente seleccionado" type="text" name="name" disabled>
                        </label>
                    </div>
                </div>
                <!--/Graph Card-->
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
                            <span class="text-gray-800">Seleccione la fecha</span>
                            <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha" required="">
                            <span id="feedbackfecha" class="text-xs text-red-600 hidden">Por favor, proporcione una fecha</span>
                        </label>
                        </div>
                        <div class="flex justify-between flex-wrap items-center mb-5">
                            <label class="text-sm w-2/5">
                                <span class="text-gray-800">Seleccione la categoría del servicio</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="categoria_servicio">
                                    <option value="membresias">Membresías</option>
                                    <option value="eventos">Eventos</option>
                                    <option value="areas" selected>Maquinas</option>
                                    <option value="alquiler">Alquiler</option>
                                </select>
                            </label>

                            <label class="text-sm w-2/5">
                                <span class="text-gray-800">Seleccione el servicio</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="servicio">

                                </select>
                            </label>

                            <button type="button" class="self-end px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-500 border border-transparent rounded-md active:bg-green-600 hover:bg-green-700 focus:outline-none w-1/12" id="agregar"><i class="fas fa-plus"></i></button>
                        </div>
                        <table class="w-full whitespace-no-wrap overflow-hidden">
                            <thead>
                            <tr class="bg-gray-100 text-sm font-semibold tracking-wide text-left uppercase">
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3">Descripción</th>
                                <th class="px-4 py-3">Precio</th>
                                <th class="px-4 py-3">Cantidad</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Acción</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white" id="detalle_venta">

                            </tbody>
                            <tfoot class="hidden text-sm bg-gray-100" id="detalle_totales">
                            <tr class="border-t font-semibold">
                                <td colspan="3" class="px-4 py-3">SUBTOT</td>
                                <td class="px-4 py-3"></td>
                            </tr>
                            <tr class="border-t font-semibold">
                                <td colspan="3" class="px-4 py-3">ITBMS 07.00%</td>
                                <td class="px-4 py-3"></td>
                            </tr>
                            <tr class="border-t font-semibold">
                                <td colspan="3" class="px-4 py-3">TOTAL</td>
                                <td class="px-4 py-3"></td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="text-sm mt-8 hidden" id="acciones">
                  <span class="text-gray-800">
                  Acciones
                  </span>
                            <div class="flex items-center">
                                <button class="mt-3 flex items-center justify-between mr-5 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 focus:outline-none" id="anular">
                                    <i class="fas fa-times mr-3"></i>
                                    <span>Anular</span>
                                </button>
                                <button class="mt-3 flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-500 border border-transparent rounded-md active:bg-green-600 hover:bg-green-700 focus:outline-none" id="generar">
                                    <i class="fas fa-save mr-3"></i>
                                    <span>Generar venta</span>
                                </button>
                            </div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= constant('URL')?>assets/js/templates/basetemplate.js"></script>
<script src="<?= constant('URL')?>assets/js/app/sales.js"></script>

</body>
</html>