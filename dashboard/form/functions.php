<?php

require_once '../../app.php';

$customer = new Customer();
$error = false;

if ($_POST['solicitud'] == 'v') {
    $visit = new Visit();
    echo json_encode($visit->saveAll($_POST));
} else if ($_POST['solicitud'] == 'doc') {
    if (!empty($_POST['documento'])) {
        $resulDocument = $customer->checkDocument($_POST['documento']);
        if ((int) $resulDocument['length'] > 0) {
            $error = true;
        }
    }
    echo json_encode($error);
} else if ($_POST['solicitud'] == 'cor') {

    if (!empty($_POST['email'])) {
        $resulEmail = $customer->checkEmail($_POST['email']);
        if ((int) $resulEmail['length'] > 0) {
            $error = true;
        }
    }
    echo json_encode($error);
} else if ($_POST['solicitud'] == 'tel') {

    if (!empty($_POST['telefono'])) {
        $resulTelephone = $customer->checkTelephone($_POST['telefono']);
        if ((int) $resulTelephone['length'] > 0) {
            $error = true;
        }
    }
    echo json_encode($error);
} else if ($_POST['solicitud'] == 'd') {
    $district = new District();
    $districts = $district->getAll();
    echo json_encode($districts);
} else if ($_POST['solicitud'] == 'c') {
    $township = new Township();
    $townships = $township->getAll();
    echo json_encode($townships);
} else if ($_POST['solicitud'] == 'book') {
    $data = array();

    $booking = new Booking();
    $booking->setDocumentType($_POST['document_type']);
    $booking->setDocument($_POST['document']);
    $bookingSelect = $booking->getBookingVisit();

    if (empty($bookingSelect)) {
        echo json_encode([
            'count' => 0
        ]);
    } else {
        $data['booking'] = $bookingSelect;

        $customer = new Customer();
        $customer->setDocumentType($bookingSelect['document_type']);
        $customer->setDocument($bookingSelect['document']);
        $customerSelect = $customer->verifyRecord();

        if (empty($customerSelect)) {
            $data['customer'] = [
                'document_type' => $bookingSelect['document_type'],
                'document' => $bookingSelect['document'],
                'name' => $bookingSelect['name']
            ];
        } else {
            $data['customer'] = $customerSelect;
        }

            $bookingArea = new BookingArea();
            $bookingArea->setBookingId($bookingSelect['booking_id']);
            $bookingsAreas = $bookingArea->getBookingsAreasVisit();
            $data['areas'] = $bookingsAreas;

        echo json_encode($data);
    }
}
