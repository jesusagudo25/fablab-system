<?php

    require_once '../../app.php';

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    $report = new Report();

    if(isset($_GET['draw'])){

        $table = <<<EOT
        ( 
            SELECT r.report_id, r.month, u.name AS autor ,r.start_date ,r.end_date 
            FROM reports r
            INNER JOIN users u ON r.user_id = u.user_id
        ) temp
        EOT;

        $primaryKey = 'report_id';

        $columns = array(
            array( 'db' => 'report_id',          'dt' => 0 ),
            array( 'db' => 'month',        'dt' => 1 ),
            array( 'db' => 'autor',    'dt' => 2 ),
            array( 'db' => 'start_date',    'dt' => 3 ),
            array( 'db' => 'end_date',    'dt' => 4 )
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

    }
    else if($_POST['solicitud'] == 'c'){

        $report->setUserId($_SESSION['user_id']);
        $report->setStartDate(isset($_POST['start_date']) ? $_POST['start_date'] : '');
        $report->setEndDate(isset($_POST['end_date']) ? $_POST['end_date'] : '');

        #Mes del reporte o fecha
        setlocale(LC_TIME, "spanish");
        $report->setMonth(ucfirst(strftime("%B",strtotime($report->getStartDate()))));

        $report->save();

        $report->getLastID();

        echo json_encode($report->getReportId());
    }
    else if($_POST['solicitud'] == 'd'){
        $report->delete($_POST['id']);

        echo json_encode('true');
    }
