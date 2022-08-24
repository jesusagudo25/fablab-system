<?php

    require_once '../../../app.php';

    session_start();

    header('Content-Type: application/json; charset=utf-8');

    $observation = new Observation();

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT o.observation_id, u.name AS autor, CONCAT(SUBSTRING(o.description,1,40),"...") AS descripcion, o.date AS fecha FROM observations o
            INNER JOIN users u ON o.user_id = u.user_id
        ) temp
        EOT;

        $primaryKey = 'observation_id';

        $columns = array(
            array( 'db' => 'observation_id',          'dt' => 0 ),
            array( 'db' => 'autor',        'dt' => 1 ),
            array( 'db' => 'descripcion',    'dt' => 2 ),
            array( 'db' => 'fecha',    'dt' => 3 )
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
    else if($_POST['solicitud'] == 'c'){

        $observation->setDescription($_POST['description']);
        $observation->setDate($_POST['date']);
        $observation->setUserId($_SESSION['user_id']);

        $observation->save();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'd'){
        $observation->delete($_POST['id']);

        echo json_encode('true');
    }

    else if($_POST['solicitud'] == 'u'){

        $observation->setObservationId($_POST['id']);
        $observation->setDescription($_POST['description']);
        $observation->setDate($_POST['date']);

        $observation->update();

        echo json_encode('true');
    }
    else if($_POST['solicitud'] == 'obs_id'){
        $observations = $observation->get($_POST['id']);
        echo json_encode($observations);
    }

