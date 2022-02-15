<?php

    require_once '../../app.php';

    header('Content-Type: application/json; charset=utf-8');

    if (isset($_GET['start']) && isset($_GET['end'])) {

        $start = new DateTime($_GET['start']);
        $start_new_format = $start->format('Y-m-d');

        $end = new DateTime($_GET['end']);
        $end_new_format = $end->format('Y-m-d');

        $event = new Events();
        $events = $event->getAllRange($start_new_format,$end_new_format);
        
        $data = array();

        foreach($events as $row)
        {
            $data[] = array( //Aqui si deberia ir el id
                'id'   => $row["event_id"],
                'title'   => $row["name"],
                'start'   => $row["initial_date"],
                'end'   => $row["final_date"],
                'className' => 'events'
            );
        }

        $bookingArea = new BookingArea();
        $bookingsAreas = $bookingArea->getAllRange($start_new_format,$end_new_format);

        foreach($bookingsAreas as $row)
        {
            $data[] = array( //Deberia ir id? si
                'groupId' => $row['booking_id'],
                'title'   => $row["name"],
                'start'   => $row["start"],
                'end'   => $row["end"],
                'className' => 'booking',
                'startEditable'=>true
            );
        }

        $booking = new Booking();
        $bookings = $booking->getAllRange($start_new_format,$end_new_format);

        foreach($bookings as $row)
        {
            $data[] = array( //Deberia ir id? si
                'id' => $row['booking_id'],
                'title'   => $row["name"],
                'start'   => $row["date"],
                'allDay'   => true,
                'className' => 'booking',
                'startEditable'=>true
            );
        }
        
        echo json_encode($data);
    }
    else if ($_POST['solicitud'] == 'raz') {

        $reason = new ReasonVisit();
        $reason_visits = $reason->getAll();

        echo json_encode($reason_visits);
    }
    else if ($_POST['solicitud'] == 'b') {
        
        $datos = $_POST['datos'];

        $booking = new Booking();
        $booking->setDocumentType($datos['document_type']);
        $booking->setDocument($datos['document']);
        $booking->setName($datos['name']);
        $booking->setDate($datos['date']);
        $booking->setReasonId($datos['reason_id']);
        $booking->setObservation(empty($datos['observation']) ? NULL : $datos['observation']);

        $booking->save();
                
        if (!empty($datos['areasChecked'])) {
            $booking_areas = new BookingArea();
            $booking_areas->save($booking->getLastID(), $datos['areasChecked']);
        }
        
        echo json_encode('true');
        
    }
    else if ($_POST['solicitud'] == 'evt') {

        $event = new Events();
        $eventSelect = $event->get($_POST['id']);
        echo json_encode($eventSelect);
    }
    else if ($_POST['solicitud'] == 'b_id') {

        $data = array();

        $booking = new Booking();
        $data['booking'] = $booking->get($_POST['id']);
        $bookingArea = new BookingArea();
        $data['areas'] = $bookingArea->get($_POST['id']);
        
        echo json_encode($data);
        
    }
    else if ($_POST['solicitud'] == 'ba_id') {

        $bookingArea = new BookingArea();
        $bookingAreaSelect = $bookingArea->get($_POST['id']);
        
        echo json_encode($bookingAreaSelect);
        
    }
    else if ($_POST['solicitud'] == 'b_up') {

        $datos = $_POST['datos'];

        $booking = new Booking();
        $booking->setDocumentType($datos['document_type']);
        $booking->setDocument($datos['document']);
        $booking->setName($datos['name']);
        $booking->setDate($datos['date']);
        $booking->setReasonId($datos['reason_id']);
        $booking->setObservation(empty($datos['observation']) ? NULL : $datos['observation']);

        $booking->setBookingId($_POST['id']);

        $booking->update();
        
        $booking_areas = new BookingArea();
        empty($datos['areasChecked']) ? $booking_areas->delete($_POST['id']) : $booking_areas->deleteSave($_POST['id'],$datos['areasChecked']);
        
        echo json_encode('true');
        
    }
    else if ($_POST['solicitud'] == 'd') {

        
        $booking_areas = new BookingArea();
        $booking_areas->delete($_POST['id']);
        
        $booking = new Booking();
        $booking->delete($_POST['id']);
        
        echo json_encode('true');
        
    }
    else if ($_POST['solicitud'] == 'b_drop') {
        $booking = new Booking();
        $booking->setDate($_POST['date']);
        $booking->setBookingId($_POST['id']);
        $booking->updateDate();
    }
