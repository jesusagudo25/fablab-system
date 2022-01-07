<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $category = new RentalCategory();

    if ($_POST['solicitud'] == 'c') {
        $categories = $category->getAll();
        echo json_encode($categories);
    }
    else if ($_POST['solicitud'] == 'c_c') {

        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $category->setStatus($_POST['status']);
        $category->delete($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $category->setCategoryId($_POST['id']);
        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $categories= $category->get($_POST['id']);
        echo json_encode($categories);
    }