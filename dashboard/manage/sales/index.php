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
<html lang="es">
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/r-2.2.9/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.0.1/b-html5-2.0.1/b-print-2.0.1/r-2.2.9/datatables.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/tables/general/fetchsales.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/templates/basetemplate.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<?php require_once '../../templates/header.php'; ?>

<div class="container w-10/12 mx-auto pt-20">

    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

        <div class="flex flex-row flex-wrap flex-grow">

            <div class="w-full p-3">
                <div class="bg-white border rounded shadow">
                    <div class="border-b p-3">
                        <h5 class="font-bold uppercase text-gray-600">Ventas</h5>
                    </div>
                    <div class="flex justify-center items-center w-full overflow-auto">
                        <table id="datatable-json" class="min-w-full divide-y divide-white">
                            <thead>
                            <tr>
                                <th>Num. Factura</th>
                                <th>Cliente</th>
                                <th>Agente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>


</div>
</body>
</html>