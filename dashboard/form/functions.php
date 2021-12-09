<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        if($decoded['datos']['solicitud'] == 'v'){

            $errores = array();

            if(!isset($decoded['datos']['id_cliente'])){
                $codigo = isset($decoded['datos']['newCustomer']['documento']) ? $decoded['datos']['newCustomer']['documento'] : '';
                $documento = isset($decoded['datos']['newCustomer']['codigo']) ? $decoded['datos']['newCustomer']['codigo'] :'';
                $email = isset($decoded['datos']['newCustomer']['email']) ? $decoded['datos']['newCustomer']['email'] :'';
                $telefono = isset($decoded['datos']['newCustomer']['telefono']) ? $decoded['datos']['newCustomer']['telefono'] :'';

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
                        $email= filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
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

            }

            if (count($errores) === 0) {
                $visit = new Visit();
                //->saveAll($decoded['datos']); Se ejecuta al final
                echo json_encode('false');
            }
            else{
                echo json_encode($errores);
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

