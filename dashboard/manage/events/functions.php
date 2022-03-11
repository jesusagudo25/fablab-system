<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $event = new Events();
    $category = new EventCategory();
    $area = new Area();

    if($_GET['solicitud'] == 'e'){

        $table = <<<EOT
        ( 
            SELECT e.event_id, ec.name AS category_id, e.name,e.initial_date ,e.final_date, e.status FROM events e
            INNER JOIN event_category ec ON e.category_id = ec.category_id
        ) temp
        EOT;

        $primaryKey = 'event_id';

        $columns = array(
            array( 'db' => 'event_id',          'dt' => 0 ),
            array( 'db' => 'category_id',        'dt' => 1 ),
            array( 'db' => 'name',    'dt' => 2 ),
            array( 'db' => 'initial_date',    'dt' => 3 ),
            array( 'db' => 'final_date',    'dt' => 4 ),
            array( 'db' => 'status',    'dt' => 5 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../../../ssp.class.php' );

        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );

    }
    else if ($_GET['solicitud'] == 'c') {

        if($_GET['status']){
            $categories = $category->getAjax();
            echo json_encode($categories);
        }
        else{
            $table = <<<EOT
            ( 
                SELECT category_id AS id, name,price, status FROM event_category
            ) temp
            EOT;

            $primaryKey = 'id';

            $columns = array(
                array( 'db' => 'id',          'dt' => 0 ),
                array( 'db' => 'name',        'dt' => 1 ),
                array( 'db' => 'price',    'dt' => 2 ),
                array( 'db' => 'status',    'dt' => 3 )
            );
    
            // SQL server connection information
            $sql_details = array(
                'user' => constant('USER'),
                'pass' => constant('PASSWORD'),
                'db'   => constant('DB'),
                'host' => constant('HOST')
            );
    
            require( '../../../ssp.class.php' );
    
            echo json_encode(
                SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
            );
        }
    }
    else if($_POST['solicitud'] == 'c_e'){
        $event->setCategoryId($_POST['categoria']);
        $event->setAreaId($_POST['area']);
        $event->setName($_POST['nombre']);
        $event->setStartTime($_POST['horainicial']);
        $event->setEndTime($_POST['horafinal']);
        $event->setInitialDate($_POST['fechainicial']);
        $event->setFinalDate($_POST['fechafinal']);
        $event->setPrice($_POST['precio']);
        $event->setExpenses($_POST['gastos']);
        $event->setDescriptionExpenses(empty($_POST['descripcion']) ? NULL :$_POST['descripcion']);

        $event->save();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'd_e'){
        $event->setStatus($_POST['status']);
        $event->delete($_POST['id']);

        echo json_encode('true');
    }

    else if($_POST['solicitud'] == 'u_e'){
        $event->setEventId($_POST['id']);
        $event->setCategoryId($_POST['categoria']);
        $event->setAreaId($_POST['area']);
        $event->setName($_POST['nombre']);
        $event->setStartTime($_POST['horainicial']);
        $event->setEndTime($_POST['horafinal']);
        $event->setInitialDate($_POST['fechainicial']);
        $event->setFinalDate($_POST['fechafinal']);
        $event->setPrice($_POST['precio']);
        $event->setExpenses($_POST['gastos']);
        $event->setDescriptionExpenses(empty($_POST['descripcion']) ? NULL :$_POST['descripcion']);

        $event->update();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'id_e'){
        $events= $event->get($_POST['id']);
        echo json_encode($events);
    }
    else if ($_POST['solicitud'] == 'c_c') {

        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd_c') {
        $category->setStatus($_POST['status']);
        $category->delete($_POST['id']);

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
    else if ($_POST['solicitud'] == 'a') {
        $areas= $area->getAjax();
        echo json_encode($areas);
    }