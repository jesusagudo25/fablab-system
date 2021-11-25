<?php

require_once '../../../app.php';

header('Content-Type: application/json; charset=utf-8');

if ($_POST['solicitud'] == 'v') {

    $visit = new Visit();
    $visits = $visit->getAll();

    echo json_encode($visits);

}else {

}