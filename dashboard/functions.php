<?php

require_once '../app.php';

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'l'){
            $customer = new Customer();
            $customers = $customer->getLabo();
            echo json_encode($customers);
        }
        else if($decoded['datos']['solicitud'] == 'u'){
            $visit = new VisitArea();
            $visit->setVisitId($decoded['datos']['visit_id']);
            $visit->setAreaId($decoded['datos']['area_id']);
            $visit->setDepartureTime($decoded['datos']['departure_time']);
            $visit->setLabo();
        }


    } else {
        header("Location: ../index.php");
    }
}
