<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

session_start();

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        $invoice = new Invoice();
        $invoice->saveAll($decoded['datos'],$_SESSION['user_id']);
        require_once './notificarcompra.php';
        echo json_encode($invoice->getInvoiceId());

    } else {
        //echo json_encode($errores);
    }
}
