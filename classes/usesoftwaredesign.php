<?php

class UseSoftwareDesign extends Model implements IModel
{

    private $use_id;
    private $invoice_id;
    private $software_id;
    private $number_hours;
    private $base_cost;


    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_software_design(invoice_id,software_id, number_hours, base_cost) VALUES (:invoice_id, :software_id, :number_hours,:base_cost)');

        $query->execute([
            'invoice_id' => $this->invoice_id,
            'software_id' => $this->software_id,
            'number_hours' => $this->number_hours,
            'base_cost' => $this->base_cost
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM categories_components');

        $categories_components = $query->fetchAll(PDO::FETCH_ASSOC);

        return $categories_components;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM categories_components WHERE category_component = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function deleteSave(...$args){

    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function getToInvoice($invoice_id)
    {
        $query = $this->prepare("SELECT usd.use_id AS id, s.name, usd.base_cost AS price, 'softwares' AS service FROM use_software_design usd
        INNER JOIN softwares s ON usd.software_id = s.software_id
        WHERE usd.invoice_id = :invoice_id");
        $query->execute([
            'invoice_id' => $invoice_id
        ]);

        $result =$query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getUseId()
    {
        return $this->use_id;
    }

    public function setUseId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    public function setInvoiceId($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function getSoftwareId()
    {
        return $this->software_id;
    }

    public function setSoftwareId($software_id)
    {
        $this->software_id = $software_id;
    }

    public function getNumberHours()
    {
        return $this->number_hours;
    }

    public function setNumberHours($number_hours)
    {
        $this->number_hours = $number_hours;
    }

    public function getBaseCost()
    {
        return $this->base_cost;
    }

    public function setBaseCost($base_cost)
    {
        $this->base_cost = $base_cost;
    }

    
}