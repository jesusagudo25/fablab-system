<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    if(isset($_GET['draw'])){

        $table = <<<EOT
        ( 
            SELECT LPAD(i.invoice_id,7,'0') AS invoice_id, c.name AS customer_id, CONCAT(u.name,' ',u.lastname) AS user_id, date, total FROM invoices i
            INNER JOIN customers c ON c.customer_id = i.customer_id
            INNER JOIN users u ON u.user_id = i.user_id
        ) temp
        EOT;

        $primaryKey = 'invoice_id';

        $columns = array(
            array( 'db' => 'invoice_id',          'dt' => 0 ),
            array( 'db' => 'customer_id',        'dt' => 1 ),
            array( 'db' => 'user_id',    'dt' => 2 ),
            array( 'db' => 'date',    'dt' => 3 ),
            array( 'db' => 'total',    'dt' => 4 )
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

