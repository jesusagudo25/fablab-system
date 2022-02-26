<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $plan = new MembershipPlans();

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT membership_id AS id,name,price,status FROM membership_plans
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
    else if ($_POST['solicitud'] == 'c') {

        $plan->setName($_POST['name']);
        $plan->setPrice($_POST['price']);

        $plan->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $plan->setStatus($_POST['status']);
        $plan->delete($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $plan->setMembershipID($_POST['id']);
        $plan->setName($_POST['name']);
        $plan->setPrice($_POST['price']);

        $plan->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $plans= $plan->get($_POST['id']);
        echo json_encode($plans);
    }