<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $plan = new MembershipPlans();

    if ($_POST['solicitud'] == 'p') {
        $plans = $plan->getAll();
        echo json_encode($plans);
    
    }
    else if ($_POST['solicitud'] == 'c') {

        $plan->setName($_POST['name']);
        $plan->setPrice($_POST['price']);

        $plan->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $plan->setStatus($_POST['status']);
        $plan->delete($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $plan->setMembershipID($_POST['id']);
        $plan->setName($_POST['name']);
        $plan->setPrice($_POST['price']);

        $plan->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $plans= $plan->get($_POST['id']);
        echo json_encode($plans);
    }