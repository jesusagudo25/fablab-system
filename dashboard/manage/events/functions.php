<?php

require_once '../../../app.php';

header('Content-Type: application/json; charset=utf-8');

if ($_POST['solicitud'] == 'e') {

    $event = new Events();
    $events = $event->getAll();

    echo json_encode($events);

}else {

}