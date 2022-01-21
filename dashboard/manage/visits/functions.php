<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $visit = new Visit();

    if ($_POST['solicitud'] == 'v') {

        $visits = $visit->getAll();

        echo json_encode($visits);

    }else if ($_POST['solicitud'] == 'raz') {

        $reason = new ReasonVisit();
        $reason_visits = $reason->getAll();

        echo json_encode($reason_visits);
    }else if ($_POST['solicitud'] == 'id_v') {

        $visits= $visit->get($_POST['id']);

        echo json_encode($visits);
    }else if ($_POST['solicitud'] == 'd') {
        $visit->setStatus($_POST['status']);
        $visit->delete($_POST['id']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'up_v') {

        if($_POST['time']){
            $visit_area = new VisitArea();
            $visit_area->delete($_POST['visit_id']);
        }
        else{
            if (!empty($_POST['areas'])) {
                $visit_area = new VisitArea();
                $visit_area->deleteSave($_POST['visit_id'],$_POST['areas']);
            }
        }

        $visit->setCustomerId($_POST['customer_id']);
        $visit->setReasonId($_POST['reason_id']);
        $visit->setDate($_POST['date']);
        $visit->setObservation(empty($_POST['observation']) ? NULL : $_POST['observation']);
        $visit->setVisitId($_POST['visit_id']);

        $visit->update();

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'id_va') {

        $visit_area = new VisitArea();
        $visits_areas= $visit_area->get($_POST['id']);

        echo json_encode($visits_areas);
    }