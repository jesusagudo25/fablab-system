<?php

class Invoice extends Model implements IModel
{
    private $invoice_id;
    private $customer_id;
    private $user_id;
    private $date;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded){

    }

    public function save(...$args)
    {
        // TODO: Implement save() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }
}