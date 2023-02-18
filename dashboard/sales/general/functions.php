<?php

require_once '../../../app.php';

session_start();

$customer = new Customer();
$error = false;

if ($_POST['solicitud'] == 's') {
    $eventCategory = new EventCategory();
    $membershipPlans = new MembershipPlans();

    $datos['eventos'] = $eventCategory->getAjax();
    $datos['membresias'] = $membershipPlans->getAjax();
    
    echo json_encode($datos);
} else if ($_POST['solicitud'] == 'evt') {
    $event = new Events();
    $events = $event->getToInvoice();
    echo json_encode($events);
}else if ($_POST['solicitud'] == 'generar_venta') {
     $invoice = new Invoice();
    $invoice->saveAll($_POST['sale'],$_SESSION['user_id'], 'general');
    //require_once './notificarcompra.php';
    echo json_encode($invoice->getInvoiceId());
}
else if ($_POST['solicitud'] == 'd') {
    $district = new District();
    $districts = $district->getAll();
    echo json_encode($districts);
} else if ($_POST['solicitud'] == 'c') {
    $township = new Township();
    $townships = $township->getAll();
    echo json_encode($townships);
} else if ($_POST['solicitud'] == 'doc') {
    if (!empty($_POST['documento'])) {
        $resulDocument = $customer->checkDocument($_POST['documento']);
        if ((int) $resulDocument['length'] > 0) {
            $error = true;
        }
    }
    echo json_encode($error);
} else if ($_POST['solicitud'] == 'cor') {

    if (!empty($_POST['email'])) {
        $resulEmail = $customer->checkEmail($_POST['email']);
        if ((int) $resulEmail['length'] > 0) {
            $error = true;
        }
    }
    echo json_encode($error);
} else if ($_POST['solicitud'] == 'tel') {
}