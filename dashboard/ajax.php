<?php

    require_once '../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $datos = array();

    $customer = new Customer();

    if (isset($_POST['customers'])) {

        $customerAjax = $customer->getAjax($_POST['customers'],$_POST['document_type']);

        echo json_encode($customerAjax);

    }
    else if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT LPAD(i.invoice_id,7,'0') AS invoice_id, receipt, c.name AS customer_id, u.name AS user_id, date, total FROM invoices i
            INNER JOIN customers c ON c.customer_id = i.customer_id
            INNER JOIN users u ON u.user_id = i.user_id
        ) temp
        EOT;

        $primaryKey = 'invoice_id';

        $columns = array(
            array( 'db' => 'invoice_id',          'dt' => 0 ),
            array( 'db' => 'user_id',    'dt' => 1 ),
            array( 'db' => 'date',    'dt' => 2 ),
            array( 'db' => 'total',    'dt' => 3 )
        );

        // SQL server connection information
        $sql_details = array(
            'user' => constant('USER'),
            'pass' => constant('PASSWORD'),
            'db'   => constant('DB'),
            'host' => constant('HOST')
        );

        require( '../ssp.class.php' );

        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
    }
    else if($_POST['solicitud'] == 'u'){

        $visit = new VisitArea();
        $visit->setVisitId($_POST['visit_id']);
        $visit->setAreaId($_POST['area_id']);
        $visit->setDepartureTime($_POST['departure_time']);
        $visit->setLabo();

        echo json_encode('true');
    }

