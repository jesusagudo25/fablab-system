<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../../app.php';

$pagina[] = "gestionar";

$province = new Province();
$provinceAll = $province->getAll();

$range = new AgeRange();
$rangeAll = $range->getAll();

?>
<!DOCTYPE html>
<html lang="es" class="overflow-y-scroll">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script src="<?= constant('URL')?>assets/js/tables/general/fetchcustomers.js" defer></script>
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
                            <h5 class="font-bold uppercase text-gray-600" id="titulo-modal">Editar cliente</h5>
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
                                <span class="text-gray-800 font-medium">Seleccione el tipo de documento</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipodocumento">
                                    <option value="R">RUC</option>
                                    <option value="C">Cédula</option>
                                    <option value="P">Pasaporte</option>
                                </select>
                            </label>
                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium" id="tituloDocumento">Numero de RUC</span>
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input ui-autocomplete-loading" placeholder="Ingrese el número de RUC con guiones" name="documento" required="" type="text" id="autoComplete" autocomplete="off">
                                    <input type="hidden" name="id_customer">
                                <span id="feedbackdocumento" class="text-xs text-red-600 feed"></span>
                            </label>
                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Codigo de cliente CIDETE</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el codigo de cliente CIDETE" type="number" name="codigo" min="0" required="" autocomplete="off">
                                <span id="feedbackcodigo" class="text-xs text-red-600 feed"></span>
                            </label>
                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Nombre</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el nombre del cliente" type="text" name="name" required="" autocomplete="off">
                                <span id="feedbacknombre" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Correo</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el correo electrónico del cliente" type="email" name="email" required="" autocomplete="off">
                                <span id="feedbackcorreo" class="text-xs text-red-600 feed"></span>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Telefono</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de telefono del cliente" type="tel" name="telefono" required="" autocomplete="off">
                                <span id="feedbacktelefono" class="text-xs text-red-600 feed"></span>
                            </label>
                            <div class="text-sm block">
                                <span class="text-gray-700 font-medium">
                                Selecciona la edad
                                </span>
                                <div class="mt-2 grid grid-cols-2 gap-4">
                                    <?php foreach ($rangeAll as $datos => $valor): ?>
                                        <label class="inline-flex items-center text-gray-600">
                                            <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="edad" value="<?= $valor['range_id'] ?>">
                                            <span class="ml-2"><?= $valor['name'] ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <span id="feedbackedad" class="text-xs text-red-600 feed"></span>
                            </div>

                            <div class="text-sm block">
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

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Selecciona la provincia</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="provincia">
                                    <?php foreach ($provinceAll as $datos => $valor): ?>
                                        <option value="<?= $valor['province_id'] ?>"><?= $valor['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>

                            <label class="text-sm block">
                                <span class="text-gray-800 font-medium">Selecciona el distrito</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="distrito"></select>
                            </label>

                            <label class="text-sm block font-medium">
                                <span class="text-gray-800">Selecciona el corregimiento</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="corregimiento"></select>
                            </label>
                        </div>
                        <footer class="flex justify-end align-center border-t p-3" id="footer-modal">
                            <button class="mr-3 p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray close" type="button" name="cancelar" >Cancelar</button>
                            <button class="p-3 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue" type="button" name="guardar">Actualizar</button>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Clientes</h5>
                    </div>
                    <div class="flex justify-center items-center w-full">
                        <table id="datatable-json" class="min-w-full divide-y divide-white">
                            <thead>
                            <tr>
                                <th class="select-disabled">ID</th>
                                <th class="select-disabled">Documento</th>
                                <th class="select-disabled">Codigo</th>
                                <th class="select-disabled">Nombre</th>
                                <th>Edad</th>
                                <th>Genero</th>
                                <th>Correo</th>
                                <th>Telefono</th>
                                <th>Provincia</th>
                                <th>Distrito</th>
                                <th>Corregimiento</th>
                                <th>Estado</th>
                                <th class="select-disabled">Acción</th>
                            </tr>
                            </thead>
                            <!-- Ajax Color Table Body -->
                        </table>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

        </div>

    </div>
</div>
</body>
</html>