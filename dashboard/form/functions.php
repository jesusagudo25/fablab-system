<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'v'){
            $visit = new Visit();
            $visit->saveAll($decoded['datos']);
        }
        else if($decoded['datos']['solicitud'] == 'doc'){ //Seguridad
            $error = false;

            $documento = empty($decoded['datos']['documento']) ? '' : $decoded['datos']['documento'];

            $customer = new Customer();

            if(!empty($documento)){
                $resulDocument = $customer->checkDocument($documento);
                if ((int) $resulDocument['length'] > 0) {
                    $error = true;
                }
            }

            if ($error) {
                echo json_encode($error);
            }
            else{
                echo json_encode($error);
            }

        }
        else if($decoded['datos']['solicitud'] == 'cod'){
            $error = false;

            $codigo = empty($decoded['datos']['codigo']) ? '' : $decoded['datos']['codigo'];

            $customer = new Customer();

            if(!empty($codigo)){
                $resulCode = $customer->checkCode($codigo);
                if ((int) $resulCode['length'] > 0) {
                    $error = true;
                }
            }

            if ($error) {
                echo json_encode($error);
            }
            else{
                echo json_encode($error);
            }

        }
        else if($decoded['datos']['solicitud'] == 'cor'){
            $error = 'false';

            $email = empty($decoded['datos']['email']) ? '' :$decoded['datos']['email'];

            $customer = new Customer();

            if(!empty($email)){
                $resulEmail = $customer->checkEmail($email);
                if ((int) $resulEmail['length'] > 0) {
                    $error = 'true';
                }
                else{
                    $email= filter_var($email,FILTER_VALIDATE_EMAIL);
                    if(!$email){
                        $error = 'validate';
                    }
                }
            }

            if ($error) {
                echo json_encode($error);
            }
            else{
                echo json_encode($error);
            }

        }
        else if($decoded['datos']['solicitud'] == 'tel'){ //Seguridad
            $error = false;

            $telefono = empty($decoded['datos']['telefono']) ? '' : $decoded['datos']['telefono'];

            $customer = new Customer();

            if(!empty($telefono)){
                $resulTelephone = $customer->checkTelephone($telefono);

                if ((int) $resulTelephone['length'] > 0) {
                    $error = true;
                }
            }

            if ($error) {
                echo json_encode($error);
            }
            else{
                echo json_encode($error);
            }

        }
        else if($decoded['datos']['solicitud'] == 'd'){
            $district = new District();
            $districts = $district->getAll();
            echo json_encode($districts);
        }
        else if($decoded['datos']['solicitud'] == 'c'){
            $township = new Township();
            $townships = $township->getAll();
            echo json_encode($townships);
        }
    }
}

