<?php

require_once '../../app.php';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'd'){
            $district = new District();
            $districts = $district->getAll();
            echo json_encode($districts);
        }
        else if($decoded['datos']['solicitud'] == 'c'){
            $township = new Township();
            $townships = $township->getAll();
            echo json_encode($townships);
        }
        else{
            $visit = new Visit();
            $visit->saveAll($decoded['datos']);
        }

    } else {
        //echo json_encode($errores);
    }
}

