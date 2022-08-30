<?php
    session_start();

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    require_once '../../../app.php';
    
    $visit = new Visit();
    $visit = $visit->get($_GET['visits_id']);

    $pagina[] = "gestionar";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitante(s) de <?= $visit['name'] ?> - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL') ?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL') ?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.0/b-colvis-2.1.0/r-2.2.9/datatables.min.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/tables/machines/fetchconsumables.js" defer></script>
    <script src="<?= constant('URL') ?>assets/js/templates/basetemplate.js" defer></script>
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

                        </div>
                    </div>
                </div>

                <div class="w-full p-3">
                    <!--Graph Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Visitante(s) de <?= $visit['name'] ?></h5>
                        </div>
                        <div class="flex justify-center items-center flex-col w-full overflow-auto">
                            <table id="datatable-json" class="min-w-full divide-y divide-white">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Cedula</th>
                                        <th>Correo</th>
                                        <th>Telefono</th>
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