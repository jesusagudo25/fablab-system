<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    if ($_POST['solicitud'] == 's') {

        $invoice = new Invoice();
        $invoices = $invoice->getAll();

        echo json_encode($invoices);

    }else {

    }


