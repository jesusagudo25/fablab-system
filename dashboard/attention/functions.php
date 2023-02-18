<?php

    require_once '../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT v.visit_id, c.name AS customer_id, r.name AS reason, r.reason_id, v.isAttended FROM visits v
            INNER JOIN customer_visit cv ON cv.visit_id = v.visit_id
            INNER JOIN customers c ON c.customer_id = cv.customer_id
            INNER JOIN reason_visits r ON r.reason_id = v.reason_id
            WHERE r.name != 'Eventos'
            AND v.isAttended != TRUE
            group by v.visit_id, c.name, r.name, v.date, v.status
        ) temp
        EOT;

        $primaryKey = 'visit_id';

        $columns = array(
            array( 'db' => 'visit_id',          'dt' => 0 ),
            array( 'db' => 'customer_id',       'dt' => 1 ),
            array( 'db' => 'reason',    'dt' => 2 ),
            array( 'db' => 'reason_id',    'dt' => 3 ),
            array( 'db' => 'isAttended',    'dt' => 4 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../../ssp.class.php' );

        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );

    }else if($_POST['solicitud'] == 'interruptor'){
        $visit = new Visit();
        $visit->setAttended(true, $_POST['id']);

    }