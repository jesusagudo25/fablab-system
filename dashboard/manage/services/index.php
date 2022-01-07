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
    <title>Servicios - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/plugins/swiper.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/templates/basetemplate.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    <script src="<?= constant('URL')?>assets/js/graphs/line.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/graphs/doughnut.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<?php require_once '../../templates/header.php'; ?>

<!--Container-->
<div class="container w-10/12 mx-auto pt-20">

    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

        <div class="flex flex-row flex-wrap flex-grow">

            <div class="w-full p-3">
                <!--Graph Card-->
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Servicios</h5>
                    </div>
                    <div class="p-5">
                    <div class="swiper pb-5">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    
                        <a href="../memberships/" class="w-3/4">
                            <div class="bg-gray-100 border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-green-600">
                                            <i class="fas fa-users fa-2x fa-fw fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-semibold text-2xl">Membresías</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                                </div>
                                <div class="swiper-slide">
                                <a href="../events/" class="w-3/4">
                            <div class="bg-gray-100 border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-pink-600">
                                            <i class="fas fa-calendar-week fa-2x fa-fw fa-inverse"></i>   
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-semibold text-2xl">Eventos</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                                </div>
                                <div class="swiper-slide">
                        <a href="../machines/" class="w-3/4">
                            <div class="bg-gray-100 border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-yellow-600">
                                            <i class="fas fa-desktop fa-2x fa-fw fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-semibold text-2xl">Maquinas</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                                </div>
                                <div class="swiper-slide">
                        <a href="../rentals/" class="w-3/4">
                            <div class="bg-gray-100 border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-blue-600"><i class="fas fa-clock fa-2x fa-fw fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-semibold text-2xl">Alquiler</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                                </div>
                            </div>
                            <div class="swiper-pagination -bottom-12"></div>
                    </div>

                    

                    </div>
                </div>
                <!--/Graph Card-->
            </div>
            <div class="w-full md:w-1/2 p-3">
                    <!--Graph Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Gráfico - Ventas por mes</h5>
                        </div>
                        <div class="p-5">
                            <canvas id="line"></canvas>

                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

                <div class="w-full md:w-1/2 p-3">
                    <!--Graph Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Gráfico - Servicios demandados</h5>
                        </div>
                        <div class="p-5">
                            <canvas id="doughnut"></canvas>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>
        </div>

    </div>
</div>
</body>
</html>