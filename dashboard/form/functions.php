<?php

require_once '../../app.php';

header('Content-Type: application/json; charset=utf-8');

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if(is_array($decoded)) {

        $customer = new Customer();
        $error = false;

        if($decoded['datos']['solicitud'] == 'v'){
            $visit = new Visit();
            echo json_encode($visit->saveAll($decoded['datos']));
        }
        else if($decoded['datos']['solicitud'] == 'doc'){ //Seguridad
            
            if(!empty($decoded['datos']['documento'])){
                $resulDocument = $customer->checkDocument($decoded['datos']['documento']);
                if ((int) $resulDocument['length'] > 0) {
                    $error = true;
                }
            }
            echo json_encode($error);
        }
        else if($decoded['datos']['solicitud'] == 'cod'){

            if(!empty($decoded['datos']['codigo'])){
                $resulCode = $customer->checkCode($decoded['datos']['codigo']);
                if ((int) $resulCode['length'] > 0) {
                    $error = true;
                }
            }
            echo json_encode($error);
        }
        else if($decoded['datos']['solicitud'] == 'cor'){

            if(!empty($decoded['datos']['email'])){
                $resulEmail = $customer->checkEmail($decoded['datos']['email']);
                if ((int) $resulEmail['length'] > 0) {
                    $error = true;
                }
            }
            echo json_encode($error);
        }
        else if($decoded['datos']['solicitud'] == 'tel'){

            if(!empty($decoded['datos']['telefono'])){
                $resulTelephone = $customer->checkTelephone($decoded['datos']['telefono']);
                if ((int) $resulTelephone['length'] > 0) {
                    $error = true;
                }
            }
            echo json_encode($error);
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
        else if($decoded['datos']['solicitud'] == 'book'){
            $data = array();

            $booking = new Booking();
            $booking->setDocumentType($decoded['datos']['document_type']);
            $booking->setDocument($decoded['datos']['document']);
            $bookingSelect = $booking->getBookingVisit();
            
            if(empty($bookingSelect)){
                echo json_encode([
                    'count' => 0
                ]);
            }
            else{
                $data['booking'] = $bookingSelect;

                $customer = new Customer();
                $customer->setDocumentType($bookingSelect['document_type']);
                $customer->setDocument($bookingSelect['document']);
                $customerSelect = $customer->verifyRecord();

                if(empty($customerSelect)){
                    $data['customer'] = [
                        'document_type' => $bookingSelect['document_type'],
                        'document' => $bookingSelect['document'],
                        'name' => $bookingSelect['name']
                    ];
                }
                else{
                    $data['customer'] = $customerSelect;
                }
                if($bookingSelect['time']){
                    $bookingArea = new BookingArea();
                    $bookingArea->setBookingId($bookingSelect['booking_id']);
                    $bookingsAreas = $bookingArea->getBookingsAreasVisit();
                    $data['areas'] = $bookingsAreas;
                }

                echo json_encode($data);
            }
        }
    }
}

