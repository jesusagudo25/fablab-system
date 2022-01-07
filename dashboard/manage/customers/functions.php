<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $customer = new Customer();

    if ($_POST['solicitud'] == 'c') {

        $customers = $customer->getAll();

        echo json_encode($customers);

    }else if ($_POST['solicitud'] == 'id_c') {

        $customers= $customer->get($_POST['id']);

        echo json_encode($customers);
        
    }else if ($_POST['solicitud'] == 'd') {
        $customer->setStatus($_POST['status']);
        $customer->delete($_POST['id']);

        echo json_encode('true');
    }else if ($_POST['solicitud'] == 'u') {

        $customer->setDocumentType($_POST['document_type']);
        $customer->setDocument($_POST['document']);
        $customer->setCode(empty($_POST['code']) ? NULL : $_POST['code']);
        $customer->setName($_POST['name']);
        $customer->setEmail(empty($_POST['email']) ? NULL : $_POST['email']);
        $customer->setTelephone(empty($_POST['telephone']) ? NULL : $_POST['telephone']);
        $customer->setAgeRange($_POST['age_range']);
        $customer->setSexo($_POST['sexo']);
        $customer->setProvince($_POST['province_id']);
        $customer->setCity($_POST['district_id']);
        $customer->setTownship($_POST['township_id']);
        $customer->setCustomerID($_POST['id']);
        $customer->update();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'dis'){
        $district = new District();
        $districts = $district->getAll();
        echo json_encode($districts);
    }
    else if($_POST['solicitud'] == 'cor'){
        $township = new Township();
        $townships = $township->getAll();
        echo json_encode($townships);
    }