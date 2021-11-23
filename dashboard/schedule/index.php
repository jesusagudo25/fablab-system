<?php
session_start();

if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
    header('Location: ../../index.php');
    die;
}

require_once '../../app.php';

$pagina[] = "schedule";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report = new Report();

    if (isset($_REQUEST['borrar'])) {
        $report->delete($_REQUEST['borrar']);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agenda - Fablab System</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link href="<?= constant('URL')?>assets/css/main.min.css" rel="stylesheet">
    <script src="<?= constant('URL')?>assets/js/plugins/main.min.js"></script>
    <script src="<?= constant('URL')?>assets/js/plugins/locales-all.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                initialDate: '2021-11-07',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'Evento todo el día',
                        start: '2021-11-01'
                    },
                    {
                        title: 'Evento largo',
                        start: '2021-11-07',
                        end: '2021-11-10'
                    },
                    {
                        groupId: '999',
                        title: 'Evento que se repite',
                        start: '2021-11-09T16:00:00'
                    },
                    {
                        groupId: '999',
                        title: 'Evento que se repite',
                        start: '2021-11-16T16:00:00'
                    },
                    {
                        title: 'Conferencia',
                        start: '2021-11-11',
                        end: '2021-11-13'
                    },
                    {
                        title: 'Cita',
                        start: '2021-11-12T10:30:00',
                        end: '2021-11-12T12:30:00'
                    },
                    {
                        title: 'Taller',
                        start: '2021-11-12T12:00:00'
                    },
                    {
                        title: 'Cita',
                        start: '2021-11-12T14:30:00'
                    },
                    {
                        title: 'Taller',
                        start: '2021-11-13T07:00:00'
                    },
                    {
                        title: 'Inauguración del Fablab',
                        url: 'http://google.com/',
                        start: '2021-11-28'
                    }
                ]
            });

            calendar.render();
        });

    </script>
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
                        <h5 class="font-bold uppercase text-gray-600">Agenda Web</h5>
                    </div>
                    <div class="flex justify-center items-center w-full overflow-auto p-5">
                        <div id='calendar' class="w-full"></div>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

        </div>

    </div>
</div>


<script src="<?= constant('URL')?>assets/js/templates/basetemplate.js"></script>

</body>
</html>