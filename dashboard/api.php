<?php

require_once '../app.php';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'r'){
            $report = new Report();
            $reports = $report->getAll();
            echo json_encode($reports);

        }
        else if($decoded['datos']['solicitud'] == 'c'){

        }


    } else {
        header("Location: index.php");
    }
}
