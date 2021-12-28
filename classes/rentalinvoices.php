<?php

class RentalInvoices extends Model implements IModel
{
    private $invoice_id;
    private $category_id;
    private $number_hours;
    private $price;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevoDetalle = $this->prepare('INSERT INTO rental_invoices( invoice_id,category_id,number_hours,price) VALUES (:invoice_id,:category_id,:number_hours,:price)');

        $nuevoDetalle->execute([
            'invoice_id' => $this->invoice_id,
            'category_id' => $this->category_id,
            'number_hours' => $this->number_hours,
            'price' => $this->price
        ]);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT rc.name,ri.price FROM rental_invoices ri
        INNER JOIN rental_category rc ON rc.category_id = ri.category_id
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
     * @param mixed $invoice_id
     */
    public function setInvoiceId($invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id): void
    {
        $this->category_id = $category_id;
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



}