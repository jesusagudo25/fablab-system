<?php

    require_once '../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $datos = array();

    
    if (isset($_POST['customers'])) {
        $customer = new Customer();
        
        $customerAjax = $customer->getAjax($_POST['customers'],$_POST['document_type']);

        echo json_encode($customerAjax);

    }
    if(isset($_POST['components'])){

        $component = new Component();
        $componentAjax = $component->getAjax($_POST['components']);

        echo json_encode($componentAjax);
    }
    if(isset($_POST['threads'])){

        $thread = new Thread();
        $threadAjax = $thread->getAjax($_POST['threads']);

        echo json_encode($threadAjax);
    }
    if(isset($_POST['vinilos'])){

        $vinilo = new Vinilo();
        $viniloAjax = $vinilo->getAjax($_POST['vinilos']);

        echo json_encode($viniloAjax);
    }
    if(isset($_POST['filaments'])){

        $filament = new Filament();
        $filamentAjax = $filament->getAjax($_POST['filaments']);

        echo json_encode($filamentAjax);
    }
    if(isset($_POST['resins'])){

        $resin = new Resin();
        $resinAjax = $resin->getAjax($_POST['resins']);

        echo json_encode($resinAjax);
    }
    if(isset($_POST['materials_laser'])){

        $material_laser = new MaterialLaser();
        $materialLaserAjax = $material_laser->getAjax($_POST['materials_laser']);

        echo json_encode($materialLaserAjax);
    }
    if(isset($_POST['materials_milling'])){

        $material_milling = new MaterialMilling();
        $materialMillingAjax = $material_milling->getAjax($_POST['materials_milling']);

        echo json_encode($materialMillingAjax);
    }
    if(isset($_POST['softwares'])){

        $software = new Software();
        $softwareAjax = $software->getAjax($_POST['softwares']);

        echo json_encode($softwareAjax);
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

