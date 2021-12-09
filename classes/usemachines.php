<?php

class UseMachines extends Model implements IModel
{
    private $use_id;
    private $invoice_id;
    private $area_id;
    private $consumable_id;
    private $amount;
    private $unit_price;
    private $printing_time;
    private $printing_price;
    private $base_price;
    private $profit_percentages;
    private $total_price;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO use_machines(invoice_id,area_id,consumable_id,amount,unit_price,printing_time,printing_price,base_price,profit_percentage,total_price) VALUES (:invoice_id,:area_id, :consumable_id,:amount,:unit_price,:printing_time,:printing_price,:base_price,:profit_percentage,:total_price)');

        $nuevaFactura->execute([
            'invoice_id' => $this->invoice_id,
            'area_id' => $this->area_id,
            'consumable_id' => $this->consumable_id,
            'amount' => $this->amount,
            'unit_price' => $this->unit_price,
            'printing_time' => $this->printing_time,
            'printing_price' => $this->printing_price,
            'base_price' => $this->base_price,
            'profit_percentage' => $this->profit_percentages,
            'total_price' => $this->total_price
        ]);
    }


    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id){

    }

    public function getToInvoice($id)
    {
        $query = $this->prepare("SELECT a.name, um.total_price AS price FROM use_machines um
        INNER JOIN areas a ON a.area_id = um.area_id
        WHERE invoice_id = :invoice_id");

        $query->execute([
            'invoice_id'=> $id,
        ]);

        $detalles = $query->fetchAll(PDO::FETCH_ASSOC);

        return $detalles;
    }

    public function getLastID(){
        $consultarIDUsoMaquinas = $this->query('SELECT use_id FROM use_machines ORDER BY use_id DESC LIMIT 1');
        $usoMaquina = $consultarIDUsoMaquinas->fetch();
        $this->use_id = $usoMaquina['use_id'];
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
     * @return mixed
     */
    public function getUseId()
    {
        return $this->use_id;
    }

    /**
     * @param mixed $use_id
     */
    public function setUseId($use_id): void
    {
        $this->use_id = $use_id;
    }

    /**
     * @param mixed $area_id
     */
    public function setAreaId($area_id): void
    {
        $this->area_id = $area_id;
    }

    /**
     * @param mixed $consumable_id
     */
    public function setConsumableId($consumable_id): void
    {
        $this->consumable_id = $consumable_id;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param mixed $unit_price
     */
    public function setUnitPrice($unit_price): void
    {
        $this->unit_price = $unit_price;
    }

    /**
     * @param mixed $printing_time
     */
    public function setPrintingTime($printing_time): void
    {
        $this->printing_time = $printing_time;
    }

    /**
     * @param mixed $printing_price
     */
    public function setPrintingPrice($printing_price): void
    {
        $this->printing_price = $printing_price;
    }

    /**
     * @param mixed $base_price
     */
    public function setBasePrice($base_price): void
    {
        $this->base_price = $base_price;
    }

    /**
     * @param mixed $profit_percentages
     */
    public function setProfitPercentages($profit_percentages): void
    {
        $this->profit_percentages = $profit_percentages;
    }

    /**
     * @param mixed $total_price
     */
    public function setTotalPrice($total_price): void
    {
        $this->total_price = $total_price;
    }

    /**
     * @return mixed
     */
    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    /**
     * @param mixed $invoice_id
     */
    public function setInvoiceId($invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }




}