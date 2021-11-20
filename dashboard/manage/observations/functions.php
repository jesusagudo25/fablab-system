<?php

    require_once '../../../app.php';

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    $observation = new Observation();

    if($_POST['solicitud'] == 'c'){

        $observation->setDescription($_POST['description']);
        $observation->setDate($_POST['date']);
        $observation->setUserId($_SESSION['user_id']);

        $observation->save();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'd'){
        $observation->delete($_POST['id']);

        echo json_encode('true');
    }

    else if($_POST['solicitud'] == 'u'){

        $observation->setObservationId($_POST['id']);
        $observation->setDescription($_POST['description']);
        $observation->setDate($_POST['date']);

        $observation->update();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'o'){
        $observations = $observation->getAll();

        echo json_encode($observations);
    }
    else if($_POST['solicitud'] == 'obs_id'){
        $observations = $observation->get($_POST['id']);
        echo json_encode($observations);
    }

