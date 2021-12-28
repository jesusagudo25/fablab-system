<?php

    require_once '../../app.php';

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    $report = new Report();

    if($_POST['solicitud'] == 'c'){

        $report->setUserId($_SESSION['user_id']);
        $report->setStartDate(isset($_POST['start_date']) ? $_REQUEST['start_date'] : '');
        $report->setEndDate(isset($_POST['end_date']) ? $_POST['end_date'] : '');

        #Mes del reporte o fecha
        setlocale(LC_TIME, "spanish");
        $report->setMonth(ucfirst(strftime("%B",strtotime($report->getStartDate()))));

        $report->save();

        $report->getLastID();

        echo json_encode($report->getReportId());
    }
    else if($_POST['solicitud'] == 'r'){

        $reports = $report->getAll();
        echo json_encode($reports);

    }
    else if($_POST['solicitud'] == 'd'){
        $report->delete($_POST['id']);

        echo json_encode('true');
    }
