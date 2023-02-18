<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $area = new Area();

    if($_GET['solicitud'] == 'areas'){
        $table = <<<EOT
        ( 
            SELECT area_id AS id, name,status FROM areas
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'status',    'dt' => 2 )
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
    else if($_GET['solicitud'] == 'filaments'){
        $table = <<<EOT
        ( 
            SELECT filament_id AS id, name, price, purchased_weight, current_weight, status FROM filaments
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',        'dt' => 2 ),
            array( 'db' => 'purchased_weight',        'dt' => 3 ),
            array( 'db' => 'current_weight',        'dt' => 4 ),
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
    else if($_GET['solicitud'] == 'components'){
        $table = <<<EOT
        ( 
            SELECT c.component_id AS id, c.name , c.price, c.stock, cc.name AS category_id, c.status FROM components c
            INNER JOIN categories_components cc ON c.category_id = cc.category_id
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',    'dt' => 2 ),
            array( 'db' => 'stock',    'dt' => 4 ),
            array( 'db' => 'category_id',    'dt' => 5 ),
            array( 'db' => 'status',    'dt' => 6 )
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
    else if($_GET['solicitud'] == 'vinyls'){
        $table = <<<EOT
        ( 
            SELECT vinilo_id AS id, name,price, width, height, area, status FROM vinilos
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',        'dt' => 2 ),
            array( 'db' => 'width',        'dt' => 3 ),
            array( 'db' => 'height',        'dt' => 4 ),
            array( 'db' => 'area',        'dt' => 5 ),
            array( 'db' => 'status',    'dt' => 6 )
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
    else if($_GET['solicitud'] == 'software'){
        $table = <<<EOT
        ( 
            SELECT software_id AS id, name, price, expiration_date, status FROM softwares
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',        'dt' => 2 ),
            array( 'db' => 'expiration_date',        'dt' => 3 ),
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
    else if($_GET['solicitud'] == 'laser'){
        $table = <<<EOT
        ( 
            SELECT material_id AS id, name,price, width, height, area, status FROM materials_laser
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',        'dt' => 2 ),
            array( 'db' => 'width',        'dt' => 3 ),
            array( 'db' => 'height',        'dt' => 4 ),
            array( 'db' => 'area',        'dt' => 5 ),
            array( 'db' => 'status',    'dt' => 6 )
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
    else if($_GET['solicitud'] == 'resins'){
        $table = <<<EOT
        ( 
            SELECT resin_id AS id, name, price, purchased_weight, current_weight, status FROM resins
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',        'dt' => 2 ),
            array( 'db' => 'purchased_weight',        'dt' => 3 ),
            array( 'db' => 'current_weight',        'dt' => 4 ),
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
    else if($_GET['solicitud'] == 'milling'){
        $table = <<<EOT
        ( 
            SELECT material_id AS id, name , price, stock, status FROM materials_mini_milling
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'price',    'dt' => 2 ),
            array( 'db' => 'stock',    'dt' => 4 ),
            array( 'db' => 'status',    'dt' => 6 )
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
    else if($_GET['solicitud'] == 'threads'){
        $table = <<<EOT
        ( 
            SELECT thread_id AS id, name,purchased_amount, current_amount, status FROM threads
        ) temp
        EOT;

        $primaryKey = 'id';

        $columns = array(
            array( 'db' => 'id',          'dt' => 0 ),
            array( 'db' => 'name',        'dt' => 1 ),
            array( 'db' => 'purchased_amount',        'dt' => 2 ),
            array( 'db' => 'current_amount',        'dt' => 3 ),
            array( 'db' => 'status',    'dt' => 4 )
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
        
        $area->save();

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
        
        $area->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        echo json_encode($area->get($_POST['id']));
    }