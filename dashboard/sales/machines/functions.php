<?php

require_once '../../../app.php';

$customer = new Customer();
$error = false;

if ($_POST['solicitud'] == 's') {
    $area = new Area();
    $datos = $area->getAjax();
    echo json_encode($datos);
}else if ($_POST['solicitud'] == 'generar_venta') {
    //echo json_encode($consumables);
}