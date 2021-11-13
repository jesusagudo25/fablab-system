<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

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


    } else {
        header("Location: ../index.php");
    }
}
