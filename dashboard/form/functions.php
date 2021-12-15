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
        if($decoded['datos']['solicitud'] == 's'){ //Seguridad
            $errores = array();

            $codigo = empty($decoded['datos']['documento']) ? '' : $decoded['datos']['documento'];
            $documento = empty($decoded['datos']['codigo']) ? '' : $decoded['datos']['codigo'];
            $email = empty($decoded['datos']['email']) ? '' :$decoded['datos']['email'];
            $telefono = empty($decoded['datos']['telefono']) ? '' : $decoded['datos']['telefono'];

            $customer = new Customer();

            if(!empty($codigo)){
                $resulDocument = $customer->checkDocument($codigo);
                if ((int) $resulDocument['length'] > 0) {
                    $errores['feedbackdocumento'] = 'El documento ya esta registrado';
                }
            }

            if(!empty($documento)){
                $resulCode = $customer->checkCode($documento);
                if ((int) $resulCode['length'] > 0) {
                    $errores['feedbackcodigo'] = 'La codigo de CIDETE ya esta registrado';
                }
            }

            if(!empty($email)){
                $resulEmail = $customer->checkEmail($email);
                if ((int) $resulEmail['length'] > 0) {
                    $errores['feedbackcorreo'] = 'La dirección de email ya esta registrada';
                }
                else{
                    $email= filter_var($email,FILTER_VALIDATE_EMAIL);
                    if(!$email){
                        $errores['feedbackcorreo'] = 'Por favor, proporcione un correo valido';
                    }
                }
            }

            if(!empty($telefono)){
                $resulTelephone = $customer->checkTelephone($telefono);

                if ((int) $resulTelephone['length'] > 0) {
                    $errores['feedbacktelefono'] = 'El numero de telefono ya esta registrada';
                }
            }

            if (count($errores)) {
                echo json_encode($errores);
            }
            else{
                echo json_encode('false');
            }

        }
        if($decoded['datos']['solicitud'] == 'd'){
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

