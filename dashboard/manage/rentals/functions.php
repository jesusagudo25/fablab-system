<?php

    require_once '../../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    $category = new RentalCategory();

    if(isset($_GET['draw'])){
        $table = <<<EOT
        ( 
            SELECT category_id AS id, name, price, status FROM rental_category
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
    else if ($_POST['solicitud'] == 'c_c') {

        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->save();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'd') {
        $category->setStatus($_POST['status']);
        $category->delete($_POST['id']);

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'u') {
        $category->setCategoryId($_POST['id']);
        $category->setName($_POST['name']);
        $category->setPrice($_POST['price']);

        $category->update();

        echo json_encode('true');
    }
    else if ($_POST['solicitud'] == 'id') {
        $categories= $category->get($_POST['id']);
        echo json_encode($categories);
    }