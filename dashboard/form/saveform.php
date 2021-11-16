<?php

require_once '../../app.php';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        $visit = new Visit();
        $visit->saveAll($decoded['datos']);

    } else {
        //echo json_encode($errores);
    }
}