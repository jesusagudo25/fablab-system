<?php

class Events extends Model implements IModel
{
    private $event_id;
    private $category_id;
    private $area_id;
    private $name;
    private $start_time;
    private $end_time;
    private $initial_date;
    private $final_date;
    private $price;
    private $expenses;
    private $description_expenses;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevoEvento = $this->prepare('INSERT INTO events(category_id,area_id,name,start_time, end_time, initial_date,final_date,price,expenses,description_expenses) VALUES (:category_id, :area_id, :name, :start_time, :end_time, :initial_date, :final_date, :price, :expenses, :description_expenses)');

        $nuevoEvento->execute([
            'category_id' => $this->category_id,
            'area_id' => $this->area_id,
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'price' => $this->price,
            'expenses' => $this->expenses,
            'description_expenses' => $this->description_expenses
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT e.event_id, ec.name AS category_id, e.name,e.initial_date ,e.final_date, e.number_hours, e.price, e.expenses, e.description_expenses, e.status FROM events e
        INNER JOIN event_category ec ON e.category_id = ec.category_id');

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        return $events;
    }

    public function getAllRange($start,$end){
        $query = $this->prepare('SELECT e.event_id, ec.name AS category_id, a.name AS area_id, e.name, e.start_time ,e.end_time, e.initial_date ,e.final_date, e.price, e.expenses, e.description_expenses, e.status FROM events e
        INNER JOIN event_category ec ON e.category_id = ec.category_id
        INNER JOIN areas a ON e.area_id = a.area_id 
        WHERE (e.initial_date >= :start 
        OR e.final_date <= :end)
        AND e.status = 1');

        $query->execute([
            'start' => $start,
            'end' => $end
        ]);

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        return $events;
    }

    public function getToInvoice()
    {
        $query = $this->query('SELECT e.event_id, e.category_id, a.name AS area_id, e.name, e.start_time ,e.end_time, e.initial_date ,e.final_date, e.price, e.expenses, e.description_expenses, e.status FROM events e
        INNER JOIN event_category ec ON e.category_id = ec.category_id
        INNER JOIN areas a ON e.area_id = a.area_id 
        WHERE (final_date >= CURDATE())
        AND
        e.status = 1');

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        return $events;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT e.event_id, ec.name AS category_name, a.name AS area_name, ec.category_id, a.area_id , e.name, e.start_time ,e.end_time, e.initial_date ,e.final_date, e.price, e.expenses, e.description_expenses FROM events e
        INNER JOIN event_category ec ON e.category_id = ec.category_id
        INNER JOIN areas a ON e.area_id = a.area_id
        WHERE e.event_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $event = $query->fetch(PDO::FETCH_ASSOC);

        return $event;
    }

    public function getLastID(){
        $consultarIDEventos = $this->query('SELECT event_id FROM events ORDER BY event_id DESC LIMIT 1');
        $evento = $consultarIDEventos->fetch();
        $this->event_id = $evento['event_id'];
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE events SET status = :status WHERE event_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE events SET category_id = :category_id,
        area_id = :area_id, name = :name, start_time = :start_time, end_time = :end_time, initial_date = :initial_date, final_date = :final_date, price = :price, expenses = :expenses, description_expenses = :description_expenses WHERE event_id = :id;");
        $actualizarDatos->execute([
            'category_id' => $this->category_id,
            'area_id' => $this->area_id,
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'price' => $this->price,
            'expenses' => $this->expenses,
            'description_expenses' => $this->description_expenses,
            'id'=>$this->event_id
        ]);
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id): void
    {
        $this->event_id = $event_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setAreaId($area_id): void
    {
        $this->area_id = $area_id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $initial_date
     */
    public function setInitialDate($initial_date): void
    {
        $this->initial_date = $initial_date;
    }

    /**
     * @param mixed $final_date
     */
    public function setFinalDate($final_date): void
    {
        $this->final_date = $final_date;
    }

    /**
     * @param mixed $initial_date
     */
    public function setStartTime($start_time): void
    {
        $this->start_time = $start_time;
    }

    /**
     * @param mixed $final_date
     */
    public function setEndTime($end_time): void
    {
        $this->end_time = $end_time;
    }

    /**
     * @param mixed $number_hours
     */
    public function setNumberHours($number_hours): void
    {
        $this->number_hours = $number_hours;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $expenses
     */
    public function setExpenses($expenses): void
    {
        $this->expenses = $expenses;
    }

    /**
     * @param mixed $description_expenses
     */
    public function setDescriptionExpenses($description_expenses): void
    {
        $this->description_expenses = $description_expenses;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }





}