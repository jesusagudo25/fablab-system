<?php

require_once '../../../app.php';

$customer = new Customer();
$error = false;

session_start();

if ($_POST['solicitud'] == 's') {
    $area = new Area();
    $datos = $area->getAjax();
    echo json_encode($datos);
}else if ($_POST['solicitud'] == 'generar_venta') {
    $invoice = new Invoice();
    $invoice->saveAll($_POST['sale'],$_SESSION['user_id'], 'machines');
    //require_once './notificarcompra.php';
    echo json_encode($invoice->getInvoiceId());
}