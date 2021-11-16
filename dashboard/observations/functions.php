<?php

require_once '../../app.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        $observation = new Observation();

        if($decoded['datos']['solicitud'] == 'c'){

            $observation->setDescription($decoded['datos']['description']);
            $observation->setDate($decoded['datos']['date']);
            $observation->setUserId($_SESSION['user_id']);

            $observation->save();
        }
        else if($decoded['datos']['solicitud'] == 'd'){
            $observation->delete($decoded['datos']['id']);
        }

        else if($decoded['datos']['solicitud'] == 'u'){

            $observation->setObservationId($decoded['datos']['id']);
            $observation->setDescription($decoded['datos']['description']);
            $observation->setDate($decoded['datos']['date']);

            $observation->update();
        }
        else if($decoded['datos']['solicitud'] == 'o'){
            $observations = $observation->getAll();
            echo json_encode($observations);
        }
        else if($decoded['datos']['solicitud'] == 'obs_id'){
            $observations = $observation->get($decoded['datos']['id']);
            echo json_encode($observations);
        }


    } else {
        header("Location: ../index.php");
    }
}
