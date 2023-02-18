<?php

    require_once '../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    if(isset($_GET['draw'])){

        $table = <<<EOT
        ( 
            SELECT t.task_id, c.name AS customer_id, t.name, t.date_delivery, t.status 
            FROM tasks t
            INNER JOIN customers c ON c.customer_id = t.customer_id
            WHERE t.status != TRUE
        ) temp
        EOT;

        $primaryKey = 'task_id';

        $columns = array(
            array( 'db' => 'task_id',          'dt' => 0 ),
            array( 'db' => 'customer_id',          'dt' => 1 ),
            array( 'db' => 'name',    'dt' => 2 ),
            array( 'db' => 'date_delivery',    'dt' => 3 ),
            array( 'db' => 'status',    'dt' => 4 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../../ssp.class.php' );

        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
    }
    else if($_POST['solicitud'] == 'interruptor'){
        $task = new Task();
        $task->interruptor(true, $_POST['id']);
    }