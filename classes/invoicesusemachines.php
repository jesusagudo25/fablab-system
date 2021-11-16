<?php

class InvoicesUseMachines extends Model implements IModel
{
    private $num_detail;
    private $invoice_id;
    private $use_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO invoices_use_machines(num_detail,invoice_id ,use_id) VALUES (:num_detail, :invoice_id,:use_id)');

        $nuevaFactura->execute([
            'num_detail' => $this->num_detail,
            'invoice_id' => $this->invoice_id,
            'use_id' => $this->use_id
        ]);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT a.name, um.total_price AS price FROM invoices_use_machines ium
        INNER JOIN use_machines um ON um.use_id = ium.use_id
        INNER JOIN areas a ON a.area_id = um.area_id
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
     * @param mixed $use_id
     */
    public function setUseId($use_id): void
    {
        $this->use_id = $use_id;
    }


}