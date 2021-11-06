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
                    <h5 class="font-bold uppercase text-gray-600">Datos de venta</h5>
                </div>
                <div>
                    <div class="text-sm mt-5 w-full overflow-x-auto">

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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= constant('URL')?>assets/js/basetemplate.js"></script>

</body>
</html>