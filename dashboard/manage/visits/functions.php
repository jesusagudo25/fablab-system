<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $visit = new Visit();
    $visit_area = new VisitArea();

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT v.visit_id, c.name AS customer_id, r.name AS reason_id ,v.date, v.status FROM visits v
            INNER JOIN customer_visit cv ON cv.visit_id = v.visit_id
            INNER JOIN customers c ON c.customer_id = cv.customer_id
            INNER JOIN reason_visits r ON r.reason_id = v.reason_id
            group by v.visit_id, c.name, r.name, v.date, v.status
        ) temp
        EOT;

        $primaryKey = 'visit_id';

        $columns = array(
            array( 'db' => 'customer_id',       'dt' => 1 ),
            array( 'db' => 'visit_id',          'dt' => 0 ),
            array( 'db' => 'reason_id',    'dt' => 2 ),
            array( 'db' => 'date',    'dt' => 3 ),
            array( 'db' => 'status',    'dt' => 4 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../../../ssp.class.php' );

        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );

    }else if ($_POST['solicitud'] == 'raz') {

        $reason = new ReasonVisit();
        $reason_visits = $reason->getAll();

        echo json_encode($reason_visits);
    }else if ($_POST['solicitud'] == 'id_v') {
        $data = array();

        $data['visits']= $visit->get($_POST['id']);
        $data['areas']= $visit_area->get($_POST['id']);

        echo json_encode($data);

    }else if ($_POST['solicitud'] == 'd') {
        $visit->setStatus($_POST['status']);
        $visit->delete($_POST['id']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'up_v') {

        $datos = $_POST['datos'];
        
        $visit->setCustomerId($datos['customer_id']);
        $visit->setReasonId($datos['reason_id']);
        $visit->setDate($datos['date']);
        $visit->setObservation(empty($datos['observation']) ? NULL : $datos['observation']);
        $visit->setVisitId($_POST['visit_id']);
        
        $visit->update();

        empty($datos['areasChecked']) ? $visit_area->delete($_POST['visit_id']) : $visit_area->deleteSave($_POST['visit_id'],$datos['areasChecked']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'id_va') {

        $visit_area = new VisitArea();
        $visits_areas= $visit_area->get($_POST['id']);

        echo json_encode($visits_areas);
    }
    