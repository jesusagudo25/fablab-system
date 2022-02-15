<?php

class Booking extends Model implements IModel
{
    private $booking_id;
    private $document_type;
    private $document;
    private $name;
    private $reason_id;
    private $date;
    private $observation;

    public function __construct()
    {
        parent::__construct();
    }


    public function save(...$args)
    {
        $newBooking = $this->prepare('INSERT INTO bookings(document_type,document,name, reason_id,date, observation) VALUES (:document_type,:document,:name, :reason_id,:date ,:observation)');

        $newBooking->execute([
            'document_type' => $this->document_type,
            'document' => $this->document,
            'name' => $this->name,
            'reason_id' => $this->reason_id,
            'date' => $this->date,
            'observation' => $this->observation
        ]);
    }

    public function getAll()
    {

    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM bookings WHERE booking_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $booking = $query->fetch(PDO::FETCH_ASSOC);

        return $booking;
    }

    public function getLastID()
    {
        $consultarIDReservacion = $this->query('SELECT booking_id FROM bookings ORDER BY booking_id DESC LIMIT 1');
        $reservaResultado = $consultarIDReservacion->fetch();
        $this->booking_id = $reservaResultado['booking_id'];

        return $this->booking_id;
    }

    public function delete($id)
    {
        $deleteData = $this->prepare("DELETE FROM bookings WHERE booking_id = :id;");
        $deleteData->execute([
            'id'=>$id
        ]);
    }

    public function updateDate()
    {
        $query = $this->prepare('UPDATE bookings SET date = :date WHERE booking_id = :booking_id');

        $query->execute([
            'date' => $this->date,
            'booking_id' => $this->booking_id
        ]);
    }

    public function update()
    {
        $query = $this->prepare('UPDATE bookings SET document_type = :document_type ,document = :document, name = :name, reason_id = :reason_id,date = :date,observation = :observation WHERE booking_id = :booking_id');

        $query->execute([
            'document_type' => $this->document_type,
            'document' => $this->document,
            'name' => $this->name,
            'reason_id' => $this->reason_id,
            'date' => $this->date,
            'observation' => $this->observation,
            'booking_id' => $this->booking_id
        ]);
    }

    public function getAllRange($start,$end){
        $query = $this->prepare("SELECT b.booking_id, b.date, rv.name FROM bookings b 
        INNER JOIN reason_visits rv ON rv.reason_id = b.reason_id
        WHERE b.date BETWEEN :start AND :end
        AND rv.time = 0");

        $query->execute([
            'start' => $start,
            'end' => $end
        ]);

        $bookings = $query->fetchAll(PDO::FETCH_ASSOC);

        return $bookings;
    }

    public function getBookingVisit(){
        $query = $this->prepare("SELECT b.booking_id, b.document_type, b.document, b.name, b.reason_id, rv.time, b.date, b.observation 
        FROM bookings b
        INNER JOIN reason_visits rv ON rv.reason_id = b.reason_id
        WHERE b.date = CURDATE()
        AND b.document_type = :document_type
        AND b.document = :document");

        $query->execute([
            'document_type' => $this->document_type,
            'document' => $this->document
        ]);

        $bookings = $query->fetch(PDO::FETCH_ASSOC);

        return $bookings;
    }

    /**
     * @param mixed $document_type
     */
    public function setDocumentType($document_type): void
    {
        $this->document_type = $document_type;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document): void
    {
        $this->document = $document;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $reason_id
     */
    public function setReasonId($reason_id): void
    {
        $this->reason_id = $reason_id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $observation
     */
    public function setObservation($observation): void
    {
        $this->observation = $observation;
    }
    
    /**
     * @param mixed $booking_id
     */
    public function setBookingId($booking_id): void
    {
        $this->booking_id =$booking_id;
    }

}