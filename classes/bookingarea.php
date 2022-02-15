<?php

class BookingArea extends Model implements IModel
{
    private $booking_id;
    private $area_id;
    private $arrival_time;
    private $departure_time;

    public function __construct()
    {
        parent::__construct();
    }


    public function save(...$args)
    {
        foreach ($args[1] as $datos) {
            $newBookingArea = $this->prepare('INSERT INTO booking_area(booking_id,area_id,arrival_time,departure_time) VALUES (:id_booking, :id_area, :hora_llegada, :hora_salida)');

            $newBookingArea->execute([
                'id_booking' => $args[0],
                'id_area' => $datos['area_id'],
                'hora_llegada' => $datos['arrival_time'],
                'hora_salida' => $datos['departure_time']
            ]);
        }
    }

    public function getAll()
    {

    }
    
    public function getAllRange($start,$end){
        $query = $this->prepare("SELECT b.booking_id, CONCAT(b.date,' ',ba.arrival_time) AS start, CONCAT(b.date,' ',ba.departure_time) AS end, a.name FROM booking_area ba 
        INNER JOIN areas a ON ba.area_id = a.area_id
        INNER JOIN bookings b ON b.booking_id = ba.booking_id
        WHERE b.date BETWEEN :start AND :end");

        $query->execute([
            'start' => $start,
            'end' => $end
        ]);

        $bookingsAreas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $bookingsAreas;
    }

    public function getBookingsAreasVisit(){
        $query = $this->prepare("SELECT area_id, arrival_time, departure_time FROM booking_area
        WHERE booking_id = :id");

        $query->execute([
            'id' => $this->booking_id
        ]);

        $bookingsAreas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $bookingsAreas;
    }

    /**
     * @param mixed $booking_id
     */
    public function setBookingId($booking_id): void
    {
        $this->booking_id= $booking_id;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM booking_area
        WHERE booking_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $booking_area = $query->fetchAll(PDO::FETCH_ASSOC);

        return $booking_area;
    }

    public function delete($id)
    {
        $deleteData = $this->prepare("DELETE FROM booking_area WHERE booking_id = :id;");
        $deleteData->execute([
            'id'=>$id
        ]);
    }

    public function deleteSave(...$args){

        $deleteBA = $this->prepare('DELETE FROM booking_area WHERE booking_id = :id');

        $deleteBA->execute([
            'id' => $args[0]
        ]);

        foreach ($args[1] as $datos) {
            $newBookingArea = $this->prepare('INSERT INTO booking_area(booking_id,area_id,arrival_time,departure_time) VALUES (:id_booking, :id_area, :hora_llegada, :hora_salida)');

            $newBookingArea->execute([
                'id_booking' => $args[0],
                'id_area' => $datos['area_id'],
                'hora_llegada' => $datos['arrival_time'],
                'hora_salida' => $datos['departure_time']
            ]);
        }
    }

    public function update()
    {

    }

}