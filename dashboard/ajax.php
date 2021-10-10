<?php

    require_once '../app.php';

    header("Content-Type: text/html;charset=utf-8");

    $datos = array();

    if (isset($_POST['customers'])) {

        $customer = new Customer();
        $customerAjax = $customer->getAjax($_POST['customers'],$_POST['document_type']);

        echo json_encode($customerAjax);

    }else {
        header("Location: logout.php");
    }


