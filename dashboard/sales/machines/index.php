<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

/*Para validar el tipo de rol     */
$allowedRoles = ['Operador'];

if (!in_array($_SESSION['rol'], $allowedRoles)) {
    header('Location: ../logout.php');
    die;
}

require_once '../../../app.php';

$pagina[] = "attention";

$area = new Area();
$areaAll = $area->getAjax();

if (isset($_GET['visit_id'])) {
    $visit = new Visit();
    $visit = $visit->get($_GET['visit_id']);
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ventas - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL') ?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL') ?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.2.0/build/global/luxon.min.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="<?= constant('URL') ?>assets/js/templates/basetemplate.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/sales/machines/general.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/sales/machines/sales.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/sales/machines/savesales.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <?php require_once '../../templates/header.php'; ?>

    <!-- Cotización -->
    <div class="fixed bottom-2 right-2">
        <button id="booking" class="animate-bounce flex items-center gap-3 py-3 px-4 bg-blue-500 active:bg-blue-600 hover:bg-blue-700 focus:outline-none text-white rounded-lg font-medium transition-colors duration-150 border border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Capturar cotización
        </button>
    </div>

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
                                    <option value="R" <?= isset($visit['document_type']) ? ($visit['document_type'] == 'R' ? 'selected' : '') : '' ?>>RUC</option>

                                    <option value="C" <?= isset($visit['document_type']) ? ($visit['document_type'] == 'C' ? 'selected' : '') : '' ?>>Cédula</option>

                                    <option value="P" <?= isset($visit['document_type']) ? ($visit['document_type'] == 'P' ? 'selected' : '') : '' ?>>Pasaporte</option>
                                </select>
                            </label>

                            <label class="text-sm w-1/3">
                                <span class="text-gray-800 font-medium" id="tituloDocumento">Numero de documento</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de documento" name="documento" required type="text" id="autoComplete" autocomplete="false" value="<?= isset($visit) ? $visit['document'] : '' ?>">
                                <input type="hidden" name="id_customer" value="<?= isset($visit['customer_id']) ? $visit['customer_id'] : '' ?>">
                                <span id="feedbackdocumento" class="text-xs text-red-600 "></span>
                            </label>

                            <label class="text-sm w-1/4">
                                <span class="text-gray-800 font-medium">Nombre de visitante</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" placeholder="Sin cliente seleccionado" type="text" name="name" disabled value="<?= isset($visit) ? $visit['name'] : '' ?>">
                            </label>
                        </div>
                    </div>

                </div>

                <!--/Modal-->
                <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden min-height-100vh flex items-center justify-center h-screen" id="modal">
                    <div class="w-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-new-bg"> </div>
                        </div>
                        <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                            <div class="border-b p-3 flex justify-between items-center">
                                <h5 class="font-bold uppercase text-gray-600">Detalles del servicio</h5>
                                <button class="border border-transparent focus:border-blue trans-all-linear close" type="button">
                                    <svg class="w-8 h-8 text-grey hover:text-grey-dark" width="32" height="32" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-5" id="modal-content">

                            </div>
                            <footer class="flex justify-end align-center border-t p-3">
                                <button class="mr-3 p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray close" type="button" name="cancelar">Cancelar</button>
                                <button class="p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar">Guardar</button>
                            </footer>
                        </div>
                    </div>
                </div>

                <div class="w-full p-3">

                    <div class="bg-white border rounded shadow overflow-auto">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Datos de la orden</h5>
                        </div>
                        <div class="p-5">
                        <div class="flex justify-between flex-wrap items-center mb-5">
                                
                                <div class="text-sm w-1/5 ">
                                    <span class="text-gray-700 font-medium">
                                        Selecciona el tipo de venta
                                    </span>
                                    <div class="mt-2" id="container-typevisit">
                                        <label class="inline-flex items-center text-gray-600">
                                            <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="typevisit" value="M" <?= isset($visit['reason']) ? ($visit['reason'] == 'Servicios' ? '' : 'checked') : '' ?>>
                                            <span class="ml-2">Maker</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6 text-gray-600">
                                            <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="typevisit" value="S" <?= isset($visit['reason']) ? ($visit['reason'] == 'Servicios' ? 'checked' : '') : 'checked' ?>>
                                            <span class="ml-2">Servicios</span>
                                        </label>
                                    </div>
                                    <span id="feedbacksexo" class="text-xs text-red-600 feed"></span>
                                </div>

                                <label class="w-2/5 text-sm">
                                    <span class="text-gray-800 font-medium">Mano de obra</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad en minutos" required="" min="1" name="mano_obra">
                                </label>
                            <label class="w-1/4 text-sm">
                                    <span class="text-gray-800 font-medium">Fecha de entrega</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_entrega">
                                </label></div>
                            <hr class="my-6">
                            <div class="flex justify-between flex-wrap items-center mb-5">

                                <label class="text-sm w-3/4">
                                    <span class="text-gray-800 font-medium">Seleccione el servicio</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="servicio" required>
                                        <?php foreach ($areaAll as $datos => $valor) : ?>
                                            <option value="<?= $valor['id'] ?>"><?= $valor['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <button type="button" class="w-1/5 self-end px-4 py-2 text-sm font-bold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none" id="agregar">
                                    Añadir
                                </button>
                            </div>

                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded">
                                <table class="w-full min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider"></th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Precio</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="detalle_venta">
                                        <tr>
                                            <td class="p-3 text-center" colspan="6">
                                                <div class="flex flex-col gap-1 justify-center items-center text-base">
                                                    <span class="text-xl text-emerald-500">
                                                        <i class="fas fa-cart-arrow-down"></i>
                                                    </span>
                                                    <p class="font-medium">Añadir servicios a la orden</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="hidden bg-gray-100 divide-y divide-gray-200" id="detalle_totales">
                                        <tr id='total'>
                                            <td class="px-4 py-3 text-sm font-semibold"></td>
                                            <td colspan="2" class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">TOTAL</td>
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
                                    <span>Generar</span>
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