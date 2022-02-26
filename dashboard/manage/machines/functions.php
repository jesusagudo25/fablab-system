<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $area = new Area();

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT area_id AS id, name, measure,status FROM areas
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'measure',    'dt' => 2 ),
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
    else if ($_POST['solicitud'] == 'c') {

        $area->setName($_POST['name']);
        $area->setMeasure($_POST['measure']);
        
        $area->save();
        $area->getLastID();
        
        if (!empty($_POST['consumables'])) {
            $consumables = new Consumable();
            $consumables->save($area->getAreaId(),$_POST['consumables']);
        }
        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $area->setStatus($_POST['status']);
        $area->delete($_POST['id']);
        
        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $area->setAreaId($_POST['id']);
        $area->setName($_POST['name']);
        $area->setMeasure($_POST['measure']);
        
        $area->update();

        if (!empty($_POST['consumables'])) {
            $consumables = new Consumable();
            $consumables->deleteSave($_POST['id'],$_POST['consumables']);
        }

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $consumable = new Consumable();

        $datos['area']= $area->get($_POST['id']);
        $datos['consumables']= $consumable->get($_POST['id']);
        echo json_encode($datos);
    }