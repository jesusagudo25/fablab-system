<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 's'){
            $eventCategory = new EventCategory();
            $membershipPlans = new MembershipPlans();
            $rentalCategory = new RentalCategory();
            $area = new Area();

            $datos['eventos']= $eventCategory->getAll();
            $datos['membresias']= $membershipPlans->getAll();
            $datos['alquiler']= $rentalCategory->getAll();
            $datos['areas']= $area->getAll();
            echo json_encode($datos);
        }
        else if($decoded['datos']['solicitud'] == 'cons'){
            $consumable = new Consumable();
            $consumables = $consumable->getAll();
            echo json_encode($consumables);
        }


    } else {
        header("Location: ../index.php");
    }
}
