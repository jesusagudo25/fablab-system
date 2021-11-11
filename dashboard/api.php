<?php

require_once '../app.php';

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
        else if($decoded['datos']['solicitud'] == 'o'){
            $observation = new Observation();
            $observations = $observation->getAll();
            echo json_encode($observations);
        }
        else if($decoded['datos']['solicitud'] == 'l'){
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
        else if($decoded['datos']['solicitud'] == 'd'){
            $district = new District();
            $districts = $district->getAll();
            echo json_encode($districts);
        }
        else if($decoded['datos']['solicitud'] == 'c'){
            $township = new Township();
            $townships = $township->getAll();
            echo json_encode($townships);
        }
        else if($decoded['datos']['solicitud'] == 's'){
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
