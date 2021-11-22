<?php
    session_start();
    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    require_once '../../app.php';

    $reason = new ReasonVisit();
    $reasonAll = $reason->getAll();

    $area = new Area();
    $areaAll = $area->getAll();

    $province = new Province();
    $provinceAll = $province->getAll();

    $range = new AgeRange();
    $rangeAll = $range->getAll();

    $pagina[] = "form";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
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
                        <h5 class="font-bold uppercase text-gray-600">Datos del visitante</h5>
                    </div>
                    <div class="p-5 flex justify-between flex-wrap items-center">
                        <label class="text-sm w-1/2">
                            <span class="text-gray-800">Seleccione el tipo de documento</span>
                            <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                <option value="R">RUC</option>
                                <option value="C">Cédula</option>
                                <option value="P">Pasaporte</option>
                            </select>
                        </label>

                        <label class="text-sm w-5/12">
                            <span class="text-gray-800" id="tituloDocumento">Numero de RUC</span>
                            <div class="relative">
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de RUC con guiones" name="documento" required type="text" id="autoComplete" autocomplete="false">
                                <input type="hidden" name="id_customer">
                                <button id="action" class="hidden absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-500 border border-transparent rounded-r-md active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-purple">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                            <span id="feedbackdocumento" class="text-xs text-red-600 hidden">Por favor, proporcione un RUC</span>
                        </label>

                        <div class="w-full flex justify-between flex-wrap items-center hidden" id="containerregister">
                            <label class="text-sm w-1/2 mt-5">
                                <span class="text-gray-800">Codigo de cliente CIDETE</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el codigo de cliente CIDETE" type="number" name="codigo" min="0" required autocomplete="off">

                            </label>
                            <label class="text-sm w-5/12 mt-5">
                                <span class="text-gray-800">Nombre</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el nombre del cliente" type="text" name="name" required autocomplete="off">

                            </label>

                            <label class="text-sm w-1/2 mt-5">
                                <span class="text-gray-800">Correo</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el correo electrónico del cliente" type="email" name="email" required autocomplete="off">

                            </label>

                            <label class="text-sm w-5/12 mt-5">
                                <span class="text-gray-800">Telefono</span>
                                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de telefono del cliente" type="tel" name="telefono" required autocomplete="off">

                            </label>
                            <div class="mt-5 text-sm w-1/2">
                <span class="text-gray-700">
                  Selecciona la edad
                </span>
                                <div class="mt-2 flex flex-wrap justify-between items-center">
                                    <?php foreach ($rangeAll as $datos => $valor): ?>
                                    <label class="inline-flex items-center text-gray-600">
                                        <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="edad" value="<?= $valor['range_id'] ?>">
                                        <span class="ml-2"><?= $valor['name'] ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mt-5 text-sm w-5/12">
                <span class="text-gray-700">
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
                            </div>

                            <label class="text-sm w-1/2 mt-5">
                                <span class="text-gray-800">Selecciona la provincia</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="provincia">
                                    <?php foreach ($provinceAll as $datos => $valor): ?>
                                        <option value="<?= $valor['province_id'] ?>" <?= $valor['name'] == 'Veraguas'  ? 'selected' : '' ?>><?= $valor['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>

                            <label class="text-sm w-5/12 mt-5">
                                <span class="text-gray-800">Selecciona el distrito</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="distrito">
                                </select>
                            </label>

                            <label class="text-sm w-1/2 mt-5">
                                <span class="text-gray-800">Selecciona el corregimiento</span>
                                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="corregimiento">
                                </select>
                            </label>

                        </div>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Datos de la visita</h5>
                    </div>
                    <form class="p-5" method="post">
                        <label class="block text-sm">
                            <span class="text-gray-800">Seleccione la razón de visita</span>
                            <select required name="razonvisita" class="mt-1 text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <?php foreach ($reasonAll as $datos => $valor): ?>
                                    <option value="<?= $valor['reason_id'] ?>" class="<?= $valor['time'] == 1  ? 'notfree' : 'free' ?>"><?= $valor['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <div class="text-sm mt-5" id="containerarea">
                            <span class="text-gray-800">Seleccione las áreas de trabajo</span>
                            <?php foreach ($areaAll as $datos => $valor): ?>
                                <label class="flex items-center mt-4">
                                    <input type="checkbox" value="<?= $valor['id'] ?>" name="areas[]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2"> <?= $valor['name'] ?></span>
                                </label>
                                <div class="p-3 hidden" id="area<?= $valor['id'] ?>">
                                    <label for="arrival_time" class="mr-6">Hora de llegada:
                                        <input type="time" id="arrival_time_area<?= $valor['id'] ?>" name="arrival_time_area<?= $valor['id'] ?>" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </label>

                                    <label for="departure_time">Hora de salida:
                                        <input type="time" id="departure_time_area<?= $valor['id'] ?>" name="departure_time_area<?= $valor['id'] ?>" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <span id="feedbackareas" class="text-xs text-red-600 hidden">Por favor, seleccione las areas deseadas</span>
                        </div>

                        <label class="block text-sm mt-5">
                            <span class="text-gray-800">Seleccione la fecha de la visita</span>
                            <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha" required>
                            <span id="feedbackfecha" class="text-xs text-red-600 hidden">Por favor, proporcione una fecha</span>
                        </label>

                        <label class="block text-sm mt-5">
                            <span class="text-gray-800">Observación complementaria</span>
                            <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese una observación complementaria" name="observation" ></textarea>
                        </label>

                        <hr class="my-8">
                        <div class="flex justify-center items-center">
                            <input type="submit" value="Registrar" class="w-3/5 px-4 py-2 text-m font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        </div>
                    </form>
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
<script src="<?= constant('URL')?>assets/js/app/main.js"></script>
<script src="<?= constant('URL')?>assets/js/app/saveform.js"></script>
<script src="<?= constant('URL')?>assets/js/templates/basetemplate.js"></script>

</body>

</html>