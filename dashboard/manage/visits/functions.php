<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $visit = new Visit();
    $visit_area = new VisitArea();

    if ($_POST['solicitud'] == 'v') {

        $visits = $visit->getAll();

        echo json_encode($visits);

    }else if ($_POST['solicitud'] == 'raz') {

        $reason = new ReasonVisit();
        $reason_visits = $reason->getAll();

        echo json_encode($reason_visits);
    }else if ($_POST['solicitud'] == 'id_v') {
        $data = array();

        $data['visits']= $visit->get($_POST['id']);
        $data['areas']= $visit_area->get($_POST['id']);

        echo json_encode($data);

    }else if ($_POST['solicitud'] == 'd') {
        $visit->setStatus($_POST['status']);
        $visit->delete($_POST['id']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'up_v') {

        $datos = $_POST['datos'];
        
        $visit->setCustomerId($datos['customer_id']);
        $visit->setReasonId($datos['reason_id']);
        $visit->setDate($datos['date']);
        $visit->setObservation(empty($datos['observation']) ? NULL : $datos['observation']);
        $visit->setVisitId($_POST['visit_id']);
        
        $visit->update();

        empty($datos['areasChecked']) ? $visit_area->delete($_POST['visit_id']) : $visit_area->deleteSave($_POST['visit_id'],$datos['areasChecked']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'id_va') {

        $visit_area = new VisitArea();
        $visits_areas= $visit_area->get($_POST['id']);

        echo json_encode($visits_areas);
    }
    