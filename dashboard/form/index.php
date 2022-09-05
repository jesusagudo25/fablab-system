<?php
session_start();
if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

/*Para validar el tipo de rol*/
$allowedRoles = ['Secretaria'];

if (!in_array($_SESSION['rol'], $allowedRoles)) {
    header('Location: ../logout.php');
    die;
}

require_once '../../app.php';

$reason = new ReasonVisit();
$reasonAll = $reason->getAll();

$area = new Area();
$areaAll = $area->getAjax();

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
    <link rel="icon" href="<?= constant('URL') ?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL') ?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.2.0/build/global/luxon.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/form/main.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/app/form/saveform.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/templates/basetemplate.js" defer></script>

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <?php require_once '../templates/header.php'; ?>

    <!-- Booking -->
    <div class="fixed bottom-2 right-2">
        <button id="booking" class="animate-bounce flex items-center gap-3 py-3 px-4 bg-blue-500 active:bg-blue-600 hover:bg-blue-700 focus:outline-none text-white rounded-lg font-medium transition-colors duration-150 border border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            ¿Es una reservación?
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
                            <h5 class="font-bold uppercase text-gray-600">Datos del visitante</h5>
                        </div>
                        <div class="p-5 flex justify-between flex-wrap items-start">

                            <div class="mb-5 text-sm w-full">
                                <span class="text-gray-700 font-medium">
                                    Selecciona el tipo de visita
                                </span>
                                <div class="mt-2">
                                    <label class="inline-flex items-center text-gray-600">
                                        <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="typevisit" value="I" checked>
                                        <span class="ml-2">Individual</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6 text-gray-600">
                                        <input type="radio" class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="typevisit" value="G">
                                        <span class="ml-2">Grupal</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between flex-wrap items-start w-full" id="container_individual">

                                <label class="text-sm w-1/2">
                                    <span class="text-gray-800 font-medium">Seleccione el tipo de documento</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                        <option value="R">RUC</option>
                                        <option value="C">Cédula</option>
                                        <option value="P">Pasaporte</option>
                                    </select>
                                </label>

                                <label class="text-sm w-5/12">
                                    <span class="text-gray-800 font-medium" id="tituloDocumento">Número de documento</span>
                                    <div class="relative">
                                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de documento" name="documento" required type="text" id="autoComplete" autocomplete="false">
                                        <input type="hidden" name="id_customer">
                                        <button id="action" class="hidden absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-r-md active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-purple">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </div>
                                    <span id="feedbackdocumento" class="text-xs text-red-600 "></span>
                                </label>

                                <div class="w-full flex justify-between flex-wrap items-start hidden" id="containerregister">
                                    <label class="text-sm w-1/2 mt-5">
                                        <span class="text-gray-800 font-medium">Nombre</span>
                                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el nombre del cliente" type="text" name="name" required autocomplete="off">
                                        <span id="feedbacknombre" class="text-xs text-red-600 feed"></span>
                                    </label>

                                    <label class="text-sm w-5/12 mt-5">
                                        <span class="text-gray-800 font-medium">Correo</span>
                                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el correo electrónico del cliente" type="email" name="email" required autocomplete="off">
                                        <span id="feedbackcorreo" class="text-xs text-red-600 "></span>
                                    </label>

                                    <label class="text-sm w-1/2 mt-5">
                                        <span class="text-gray-800 font-medium">Telefono</span>
                                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de telefono del cliente" type="tel" name="telefono" required autocomplete="off">
                                        <span id="feedbacktelefono" class="text-xs text-red-600 "></span>
                                    </label>

                                    <div class="mt-5 text-sm w-5/12">
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

                                    <div class="mt-5 text-sm w-1/2">
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

                                    <label class="text-sm w-5/12 mt-5">
                                        <span class="text-gray-800 font-medium">Selecciona la provincia</span>
                                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="provincia">
                                            <?php foreach ($provinceAll as $datos => $valor) : ?>
                                                <option value="<?= $valor['province_id'] ?>" <?= $valor['name'] == 'Veraguas'  ? 'selected' : '' ?>><?= $valor['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>

                                    <label class="text-sm w-1/2 mt-5">
                                        <span class="text-gray-800 font-medium">Selecciona el distrito</span>
                                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="distrito">
                                        </select>
                                    </label>

                                    <label class="text-sm w-5/12 mt-5 font-medium">
                                        <span class="text-gray-800">Selecciona el corregimiento</span>
                                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="corregimiento">
                                        </select>
                                    </label>

                                </div>
                            </div>

                            <div class="flex justify-between items-center w-full hidden" id="container_grupal">
                                <label class="text-sm w-1/3">
                                    <span class="text-gray-800 font-medium">Descarga la plantilla de excel</span>
                                    <a href="<?= constant('URL') ?>assets/template/visit.xlsx" class="mt-2 w-full px-4 py-2 text-base font-bold leading-5 uppercase flex justify-center items-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-md active:bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                        <span class="ml-2">Descargar</span>
                                    </a>
                                </label>
                                <div class="text-blue-500 self-center mt-4 p-3 rounded-full bg-blue-100 mx-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <label class="text-sm w-1/3">
                                    <span class="text-gray-800 font-medium" id="tituloDocumento">Subir la lista de visitantes</span>
                                    <input type="file" class="mt-2 block w-full text-base rounded-md shadow-sm focus:border-blue-300 border border-gray-300
                                        file:mr-4 file:py-2 file:px-4
                                        file:uppercase file:border-0
                                        file:text-base file:font-semibold
                                        file:bg-blue-500 file:text-white
                                    " id="fileupload" name="fileupload" />
                            </div>
                            
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
                            <span class="text-gray-800 font-medium">Seleccione la razón de visita</span>
                            <select required name="razonvisita" class="mt-1 text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <?php foreach ($reasonAll as $datos => $valor) : ?>
                                    <option value="<?= $valor['reason_id'] ?>" class="<?= $valor['time'] == 1  ? 'notfree' : 'free' ?> <?= $valor['isGroup'] == 1  ? 'isgroup' : 'notgroup' ?>"><?= $valor['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <div class="text-sm mt-5" id="container-trabajo">
                            <span class="text-gray-800 font-medium">Seleccione las áreas de trabajo</span>
                            <div id="container-areas">
                                <?php foreach ($areaAll as $datos => $valor) : ?>
                                    <label class="flex items-center mt-4">
                                        <input type="checkbox" value="<?= $valor['id'] ?>" name="areacheck<?= $valor['id'] ?>" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2"> <?= $valor['name'] ?></span>
                                    </label>
                                    <div class="p-3 hidden" id="area<?= $valor['id'] ?>">
                                        <label for="arrival_time" class="mr-6">Hora de llegada:
                                            <input type="time" name="arrival_time_area<?= $valor['id'] ?>" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                        </label>
                                        <label for="departure_time">Hora de salida:
                                            <input type="time" name="departure_time_area<?= $valor['id'] ?>" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                        </label>

                                        <br />
                                        <span id="feedbackarea<?= $valor['id'] ?>" class="text-xs text-red-600 feed"></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <label class="flex items-center mt-4 <?= $reasonAll[0]['time'] == 1 ? 'hidden' : '' ?>" id="container-check-all">
                                <i class="fas fa-arrow-right fa-fw mr-3"></i>
                                <input type="checkbox" value="" name="" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2">Marcar todo</span>
                            </label>
                            <div class="p-3 hidden" id="area-all">
                                <label for="arrival_time" class="mr-6">Hora de llegada:
                                    <input type="time" name="arrival_time_area-all" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                </label>
                                <label for="departure_time">Hora de salida:
                                    <input type="time" name="departure_time_area-all" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">
                                </label>

                                <br />
                                <span id="feedbackarea<?= $valor['id'] ?>" class="text-xs text-red-600 feed"></span>
                            </div>

                            <span id="feedbackareas" class="inline-block mt-2 text-xs text-red-600 feed"></span>
                        </div>

                        <label class="block text-sm mt-5">
                            <span class="text-gray-800 font-medium">Observación complementaria</span>
                            <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese una observación complementaria" name="observation"></textarea>
                        </label>

                        <hr class="my-8">
                        <div class="flex justify-center items-center">
                            <button type="submit" value="Submit" class="w-1/2 px-4 py-2 text-base font-bold leading-5 uppercase flex justify-center items-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Registrar</button>
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

</body>

</html>