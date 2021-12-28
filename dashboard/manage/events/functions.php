<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $event = new Events();

    if($_POST['solicitud'] == 'c'){

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
    else if($_POST['solicitud'] == 'd'){
        $event->setStatus(0);
        $event->delete($_POST['id']);

        echo json_encode('true');
    }

    else if($_POST['solicitud'] == 'u'){

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
    else if($_POST['solicitud'] == 'id'){
        $events= $event->get($_POST['id']);
        echo json_encode($events);
    }
    else if ($_POST['solicitud'] == 'e') {

        $events = $event->getAll();

        echo json_encode($events);
    }
    else if ($_POST['solicitud'] == 'cat') {

        $category = new EventCategory();
        $categories = $category->getAll();

        echo json_encode($categories);
    }