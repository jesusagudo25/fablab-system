<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

/*Para validar el tipo de rol     */
$allowedRoles = ['Secretaria'];

if (!in_array($_SESSION['rol'], $allowedRoles)) {
    header('Location: ../logout.php');
    die;
}

require_once '../../../app.php';

$pagina[] = "sales";

if (isset($_GET['id'])) {
    $customer = new Customer();
    $customer = $customer->get($_GET['id']);
}

$province = new Province();
$provinceAll = $province->getAll();

$range = new AgeRange();
$rangeAll = $range->getAll();

$membershipPlans = new MembershipPlans();
$membershipPlansAll = $membershipPlans->getAll();

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
    <script src="<?= constant('URL') ?>assets/js/app/sales/general/sales.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/sales/general/savesales.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/sales/general/customer.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <?php require_once '../../templates/header.php'; ?>

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
                            <label class="text-sm w-2/5">
                                <span class="text-gray-800 font-medium">Seleccione el tipo de documento</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                    <option value="R" <?= isset($customer['document_type']) ? ($customer['document_type'] == 'R' ? 'selected' : '') : '' ?>>RUC</option>

                                    <option value="C" <?= isset($customer['document_type']) ? ($customer['document_type'] == 'C' ? 'selected' : '') : '' ?>>Cédula</option>

                                    <option value="P" <?= isset($customer['document_type']) ? ($customer['document_type'] == 'P' ? 'selected' : '') : '' ?>>Pasaporte</option>
                                </select>
                            </label>

                            <label class="text-sm w-7/11">
                                <span class="text-gray-800 font-medium" id="tituloDocumento">Número de documento</span>
                                <div class="relative">
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de documento" name="documento" required type="text" id="autoComplete" autocomplete="false" value="<?= isset($customer['document']) ? $customer['document'] : '' ?>">
                                    <input type="hidden" name="id_customer" value="<?= isset($customer['customer_id']) ? $customer['customer_id'] : '' ?>">
                                    <button id="action" class="hidden absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-r-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-purple">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                </div>
                                <span id="feedbackdocumento" class="text-xs text-red-600 "></span>
                            </label>

                            <div class="w-full flex justify-between flex-wrap items-start hidden" id="containerregister">
                                <label class="text-sm w-2/5 mt-5">
                                    <span class="text-gray-800 font-medium">Nombre</span>
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el nombre del cliente" type="text" name="name" required autocomplete="off">
                                    <span id="feedbacknombre" class="text-xs text-red-600 feed"></span>
                                </label>

                                <label class="text-sm w-7/11 mt-5">
                                    <span class="text-gray-800 font-medium">Correo</span>
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el correo electrónico del cliente" type="email" name="email" required autocomplete="off">
                                    <span id="feedbackcorreo" class="text-xs text-red-600 "></span>
                                </label>

                                <label class="text-sm w-2/5 mt-5">
                                    <span class="text-gray-800 font-medium">Telefono</span>
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de telefono del cliente" type="tel" name="telefono" required autocomplete="off">
                                    <span id="feedbacktelefono" class="text-xs text-red-600 "></span>
                                </label>

                                <div class="mt-5 text-sm w-7/11">
                                    <span class="text-gray-700 font-medium">
                                        Selecciona la edad
                                    </span>
                                    <div class="mt-2 flex flex-wrap justify-between items-center">
                                        <?php foreach ($rangeAll as $datos => $valor) : ?>
                                            <label class="inline-flex items-center text-gray-600">
                                                <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="edad" value="<?= $valor['range_id'] ?>">
                                                <span class="ml-2"><?= $valor['name'] ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <span id="feedbackedad" class="text-xs text-red-600 feed"></span>
                                </div>

                                <div class="mt-5 text-sm w-2/5">
                                    <span class="text-gray-700 font-medium">
                                        Selecciona el sexo
                                    </span>
                                    <div class="mt-2">
                                        <label class="inline-flex items-center text-gray-600">
                                            <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="sexo" value="F">
                                            <span class="ml-2">F</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6 text-gray-600">
                                            <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="sexo" value="M">
                                            <span class="ml-2">M</span>
                                        </label>
                                    </div>
                                    <span id="feedbacksexo" class="text-xs text-red-600 feed"></span>
                                </div>

                                <label class="text-sm w-7/11 mt-5">
                                    <span class="text-gray-800 font-medium">Selecciona la provincia</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="provincia">
                                        <?php foreach ($provinceAll as $datos => $valor) : ?>
                                            <option value="<?= $valor['province_id'] ?>" <?= $valor['name'] == 'Veraguas'  ? 'selected' : '' ?>><?= $valor['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <label class="text-sm w-2/5 mt-5">
                                    <span class="text-gray-800 font-medium">Selecciona el distrito</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="distrito">
                                    </select>
                                </label>

                                <label class="text-sm w-7/11 mt-5 font-medium">
                                    <span class="text-gray-800">Selecciona el corregimiento</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="corregimiento">
                                    </select>
                                </label>

                            </div>

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
                                <label class="text-sm w-2/5">
                                    <span class="text-gray-800 font-medium">Seleccione la categoría del servicio</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="categoria_servicio">
                                        <option value="membresias">Membresías</option>
                                        <option value="eventos">Eventos</option>
                                    </select>
                                </label>

                                <label class="text-sm w-2/5">
                                    <span class="text-gray-800 font-medium">Seleccione el servicio</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="servicio">
                                        <?php foreach ($membershipPlansAll as $datos => $valor) : ?>
                                            <option value="<?= $valor['id'] ?>"><?= $valor['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>

                                <button type="button" class="self-end px-4 py-2 text-sm font-bold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none w-1/12" id="agregar">
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