<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $area = new Area();

    if ($_POST['solicitud'] == 'a') {
        $areas = $area->getAll();
        echo json_encode($areas);
    
    }
    else if ($_POST['solicitud'] == 'c') {

        $area->setName($_POST['name']);
        $area->setMeasure($_POST['measure']);

        $area->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $area->setStatus($_POST['status']);
        $area->delete($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $area->setAreaId($_POST['id']);
        $area->setName($_POST['name']);
        $area->setMeasure($_POST['measure']);

        $area->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $areas= $area->get($_POST['id']);
        echo json_encode($areas);
    }