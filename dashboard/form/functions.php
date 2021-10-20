<?php

require_once '../../app.php';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'd'){
            $district = new District();
            $district->setProvinceId($decoded['datos']['id']);
            $districts = $district->getForProvince();
            echo json_encode($districts);
        }
        else if($decoded['datos']['solicitud'] == 'c'){
            $township = new Township();
            $township->setDistrictId($decoded['datos']['id']);
            $townships = $township->getForDistrict();
            echo json_encode($townships);
        }


    } else {
        header("Location: ../index.php");
    }
}
