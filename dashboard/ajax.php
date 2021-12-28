<?php

    require_once '../app.php';

    header("Content-Type: text/html;charset=utf-8");

    $datos = array();

    $customer = new Customer();

    if (isset($_POST['customers'])) {

        $customerAjax = $customer->getAjax($_POST['customers'],$_POST['document_type']);

        echo json_encode($customerAjax);

    }
    else if($_POST['solicitud'] == 'l'){


        $customers = $customer->getLabo();
        echo json_encode($customers);

    }
    else if($_POST['solicitud'] == 'u'){

        $visit = new VisitArea();
        $visit->setVisitId($_POST['visit_id']);
        $visit->setAreaId($_POST['area_id']);
        $visit->setDepartureTime($_POST['departure_time']);
        $visit->setLabo();

        echo json_encode('true');
    }

