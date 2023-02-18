<?php

class UseSaleMachine extends Model implements IModel
{
    private $use_id;
    private $invoice_id;
    private $area_id;
    private $number_minutes;
    private $base_cost;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_machine(invoice_id,area_id, number_minutes, base_cost) VALUES (:invoice_id,:area_id, :number_minutes,:base_cost)');

        $query->execute([
            'invoice_id' => $this->invoice_id,
            'area_id' => $this->area_id,
            'number_minutes' => $this->number_minutes,
            'base_cost' => $this->base_cost
        ]);
    }
    

    public function getAll()
    {
    }

    public function get($id)
    {
    }

    public function getToInvoice($invoice_id)
    {
        $query = $this->prepare("SELECT usm.use_id AS id, a.name, usm.base_cost AS price, 'areas' AS service FROM use_sale_machine usm
        INNER JOIN areas a ON usm.area_id = a.area_id
        WHERE usm.invoice_id = :invoice_id");

        $query->execute([
            'invoice_id' => $invoice_id
        ]);

        $result =$query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
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

    public function setUseId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function getUseId()
    {
        return $this->use_id;
    }

    public function setInvoiceId($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    public function setAreaId($area_id)
    {
        $this->area_id = $area_id;
    }

    public function getAreaId()
    {
        return $this->area_id;
    }

    public function setNumberMinutes($number_minutes)
    {
        $this->number_minutes = $number_minutes;
    }

    public function getNumberMinutes()
    {
        return $this->number_minutes;
    }

    public function setBaseCost($base_cost)
    {
        $this->base_cost = $base_cost;
    }

    public function getBaseCost()
    {
        return $this->base_cost;
    }

    public function getLastID(){
        $query = $this->query("SELECT use_id FROM use_sale_machine ORDER BY use_id DESC LIMIT 1");

        $use = $query->fetch();

        $this->use_id = $use['use_id'];
    }


}