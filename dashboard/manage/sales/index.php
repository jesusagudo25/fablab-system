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
    <title>Ventas - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/r-2.2.9/datatables.min.css"/>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.0.1/b-html5-2.0.1/b-print-2.0.1/r-2.2.9/datatables.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/tables/fetchsales.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/templates/basetemplate.js" defer></script>
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
                        <h5 class="font-bold uppercase text-gray-600">Gestionar</h5>
                    </div>
                    <div class="p-5">
                        <!-- Slider main container -->
                        <div class="swiper pb-5">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="flex items-center p-4 bg-white rounded-lg border-gray-300 border shadow-sm">
                                        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700">
                                                <a class="align-bottom inline-flex items-center justify-center leading-5 transition-colors duration-150 font-medium bg-blue-500 text-white px-4 py-2 rounded-lg text-sm active:bg-blue-600 hover:bg-blue-700 focus:outline-none" type="button" href="/dashboard">Membresías</a>
                                            </p>
                                        </div>
                                    </div></div>
                                <div class="swiper-slide"><div class="flex items-center p-4 bg-white rounded-lg border-gray-300 border shadow-sm">
                                        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700">
                                                <a class="align-bottom inline-flex items-center justify-center leading-5 transition-colors duration-150 font-medium bg-blue-500 text-white px-4 py-2 rounded-lg text-sm active:bg-blue-600 hover:bg-blue-700 focus:outline-none" type="button" href="../events/">Eventos</a>
                                            </p>
                                        </div>
                                    </div></div>
                                <div class="swiper-slide">
                                    <div class="flex items-center p-4 bg-white rounded-lg border-gray-300 border shadow-sm">
                                        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700">
                                                <a class="align-bottom inline-flex items-center justify-center leading-5 transition-colors duration-150 font-medium bg-blue-500 text-white px-4 py-2 rounded-lg text-sm active:bg-blue-600 hover:bg-blue-700 focus:outline-none" type="button" href="/dashboard">Maquinas</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="flex items-center p-4 bg-white rounded-lg border-gray-300 border shadow-sm">
                                        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700">
                                                <a class="align-bottom inline-flex items-center justify-center leading-5 transition-colors duration-150 font-medium bg-blue-500 text-white px-4 py-2 rounded-lg text-sm active:bg-blue-600 hover:bg-blue-700 focus:outline-none" type="button" href="/dashboard">Alquiler</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-pagination -bottom-12"></div>
                        </div>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Ventas</h5>
                    </div>
                    <div class="flex justify-center items-center w-full overflow-auto">
                        <table id="datatable-json" class="min-w-full divide-y divide-white">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <!-- Ajax Color Table Body -->
                        </table>
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