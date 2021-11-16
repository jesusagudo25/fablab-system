<?php

class InvoicesEvents extends Model implements IModel
{
    private $num_detail;
    private $invoice_id;
    private $event_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO invoices_events(num_detail,invoice_id ,event_id) VALUES (:num_detail, :invoice_id,:event_id)');

        $nuevaFactura->execute([
            'num_detail' => $this->num_detail,
            'invoice_id' => $this->invoice_id,
            'event_id' => $this->event_id
        ]);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT ec.name, e.price FROM invoices_events ie
        INNER JOIN events e ON e.event_id = ie.event_id
        INNER JOIN event_category ec ON ec.category_id = e.category_id
        WHERE invoice_id = :invoice_id");

        $query->execute([
            'invoice_id'=> $id,
        ]);

        $detalles = $query->fetchAll(PDO::FETCH_ASSOC);

        return $detalles;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * @param mixed $num_detail
     */
    public function setNumDetail($num_detail): void
    {
        $this->num_detail = $num_detail;
    }

    /**
     * @param mixed $invoice_id
     */
    public function setInvoiceId($invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id): void
    {
        $this->event_id = $event_id;
    }


}