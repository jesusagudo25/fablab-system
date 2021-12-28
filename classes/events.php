<?php

class Events extends Model implements IModel
{
    private $event_id;
    private $category_id;
    private $name;
    private $initial_date;
    private $final_date;
    private $number_hours;
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
        $nuevaFactura = $this->prepare('INSERT INTO events(category_id,name,initial_date,final_date,number_hours,price,expenses,description_expenses) VALUES (:category_id, :name,:initial_date,:final_date,:number_hours,:price,:expenses,:description_expenses)');

        $nuevaFactura->execute([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'number_hours' => $this->number_hours,
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

    public function getToInvoice()
    {
        $query = $this->query('SELECT * FROM events
WHERE status = 1');

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        return $events;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM events WHERE event_id = :id');
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
        $actualizarDatos = $this->prepare("UPDATE events SET category_id = :category_id, name = :name, initial_date = :initial_date, final_date = :final_date, number_hours = :number_hours, price = :price, expenses = :expenses, description_expenses = :description_expenses WHERE event_id = :id;");
        $actualizarDatos->execute([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'number_hours' => $this->number_hours,
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