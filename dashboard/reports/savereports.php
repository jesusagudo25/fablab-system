<?php

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once '../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report = new Report();

    $report->setUserId($_SESSION['user_id']);
    $report->setStartDate(isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '');
    $report->setEndDate(isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '');

    #Mes del reporte o fecha
    setlocale(LC_TIME, "spanish");
    $report->setMonth(ucfirst(strftime("%B",strtotime($report->getStartDate()))));

    $report->save();

    $report->getLastID();

    echo json_encode($report->getReportId());

}