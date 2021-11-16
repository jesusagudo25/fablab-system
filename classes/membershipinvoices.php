<?php

class MembershipInvoices extends Model implements IModel
{
    private $num_detail;
    private $invoice_id;
    private $membership_id;
    private $initial_date;
    private $final_date;
    private $price;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevoDetalle = $this->prepare('INSERT INTO membership_invoices(num_detail, invoice_id,membership_id,initial_date,final_date,price) VALUES (:num_detail, :invoice_id,:membership_id,:initial_date,:final_date,:price)');

        $nuevoDetalle->execute([
            'num_detail' => $this->num_detail,
            'invoice_id' => $this->invoice_id,
            'membership_id' => $this->membership_id,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'price' => $this->price,
        ]);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT mp.name,mi.price FROM membership_invoices mi
        INNER JOIN membership_plans mp ON mp.membership_id = mi.membership_id
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
     * @param mixed $membership_id
     */
    public function setMembershipId($membership_id): void
    {
        $this->membership_id = $membership_id;
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
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $num_detail
     */
    public function setNumDetail($num_detail): void
    {
        $this->num_detail = $num_detail;
    }



}