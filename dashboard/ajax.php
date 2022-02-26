<?php

    require_once '../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $datos = array();

    $customer = new Customer();

    if (isset($_POST['customers'])) {

        $customerAjax = $customer->getAjax($_POST['customers'],$_POST['document_type']);

        echo json_encode($customerAjax);

    }
    else if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT v.visit_id , va.area_id, c.name AS nombre_cliente,a.name AS nombre_area,va.departure_time FROM visits v 
            INNER JOIN visits_areas va ON v.visit_id = va.visit_id 
            INNER JOIN customers c ON v.customer_id = c.customer_id 
            INNER JOIN areas a ON va.area_id = a.area_id 
            WHERE (va.departure_time IS NULL ) AND (v.status = 1)
        ) temp
        EOT;

        $primaryKey = 'visit_id';

        $columns = array(
            array( 'db' => 'visit_id',          'dt' => 0 ),
            array( 'db' => 'area_id',        'dt' => 1 ),
            array( 'db' => 'nombre_cliente',    'dt' => 2 ),
            array( 'db' => 'nombre_area',    'dt' => 3 ),
            array( 'db' => 'departure_time',    'dt' => 4 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../ssp.class.php' );

        echo json_encode(
    	    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );

    }
    else if($_POST['solicitud'] == 'u'){

        $visit = new VisitArea();
        $visit->setVisitId($_POST['visit_id']);
        $visit->setAreaId($_POST['area_id']);
        $visit->setDepartureTime($_POST['departure_time']);
        $visit->setLabo();

        echo json_encode('true');
    }

