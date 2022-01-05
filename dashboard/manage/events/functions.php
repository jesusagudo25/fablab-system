<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $event = new Events();
    $category = new EventCategory();

    if($_POST['solicitud'] == 'c_e'){

        $event->setCategoryId($_POST['categoria']);
        $event->setName($_POST['nombre']);
        $event->setNumberHours($_POST['horas']);
        $event->setInitialDate($_POST['inicial']);
        $event->setFinalDate($_POST['final']);
        $event->setPrice($_POST['precio']);
        $event->setExpenses(empty($_POST['gastos']) ? NULL :$_POST['gastos']);
        $event->setDescriptionExpenses(empty($_POST['descripcion']) ? NULL :$_POST['descripcion']);

        $event->save();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'd_e'){
        $event->setStatus($_POST['status']);
        $event->switched($_POST['id']);

        echo json_encode('true');
    }

    else if($_POST['solicitud'] == 'u_e'){

        $event->setEventId($_POST['id']);
        $event->setCategoryId($_POST['categoria']);
        $event->setName($_POST['nombre']);
        $event->setNumberHours($_POST['horas']);
        $event->setInitialDate($_POST['inicial']);
        $event->setFinalDate($_POST['final']);
        $event->setPrice($_POST['precio']);
        $event->setExpenses(empty($_POST['gastos']) ? NULL :$_POST['gastos']);
        $event->setDescriptionExpenses(empty($_POST['descripcion']) ? NULL :$_POST['descripcion']);

        $event->update();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'id_e'){
        $events= $event->get($_POST['id']);
        echo json_encode($events);
    }
    else if ($_POST['solicitud'] == 'e') {

        $events = $event->getAll();

        echo json_encode($events);
    }
    else if ($_POST['solicitud'] == 'c') {

        if($_POST['status']){
            $categories = $category->getAjax();
            echo json_encode($categories);
        }
        else{
            $categories = $category->getAll();
            echo json_encode($categories);
        }

    }
    else if ($_POST['solicitud'] == 'c_c') {

        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd_c') {
        $category->setStatus($_POST['status']);
        $category->switched($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u_c') {
        $category->setCategoryID($_POST['id']);
        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id_c') {
        $categories= $category->get($_POST['id']);
        echo json_encode($categories);
    }