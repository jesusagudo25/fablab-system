<?php

class Invoice extends Model implements IModel
{
    private $invoice_id;
    private $invoice;
    private $receipt;
    private $customer_id;
    private $user_id;
    private $date;
    private $total;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded,$user_id){
        $this->customer_id = $decoded['id_cliente'];
        $this->user_id = $user_id;
        $this->date = $decoded['fecha'];
        $this->total = $decoded['total'];
        $this->receipt = empty($decoded['receipt']) ? NULL : $decoded['receipt'];

        $this->save();

        $this->getLastID();

        foreach ($decoded['servicios_ag'] as $datos => $valor){

            $type = match ($valor['categoria']) {
                'membresias', => function ($valor){
                    $membership_invoices = new MembershipInvoices();

                    $membership_invoices->setInvoiceId($this->invoice_id);
                    $membership_invoices->setMembershipId($valor['servicio']);
                    $membership_invoices->setInitialDate($valor['detalles']['fecha_inicial']);
                    $membership_invoices->setFinalDate($valor['detalles']['fecha_final']);
                    $membership_invoices->setPrice($valor['precio']);

                    $membership_invoices->save();
                },
                'eventos' => function ($valor){
                    $invoices_events = new InvoicesEvents();

                    $invoices_events->setInvoiceId($this->invoice_id);
                    $invoices_events->setEventId($valor['detalles']['event_id']);

                    $invoices_events->save();
                },
                'areas' => function ($valor){
                    $use_machine = new UseMachines();

                    $use_machine->setInvoiceId($this->invoice_id);
                    $use_machine->setAreaId($valor['servicio']);
                    $use_machine->setConsumableId($valor['detalles']['tipo_consumible']);
                    $use_machine->setAmount($valor['detalles']['cantidad_unidad']);
                    $use_machine->setUnitPrice($valor['detalles']['precio_unitario']);
                    $use_machine->setPrintingTime($valor['detalles']['cantidad_tiempo']);
                    $use_machine->setPrintingPrice($valor['detalles']['precio_impresion']);
                    $use_machine->setBasePrice($valor['detalles']['costo_base']);
                    $use_machine->setProfitPercentages($valor['detalles']['porcentaje_ganancia']);
                    $use_machine->setTotalPrice($valor['detalles']['costo_total']);

                    $use_machine->save();
                },
                'alquiler' => function ($valor){
                    $rental_invoices = new RentalInvoices();

                    $rental_invoices->setInvoiceId($this->invoice_id);
                    $rental_invoices->setCategoryId($valor['servicio']);
                    $rental_invoices->setNumberHours($valor['detalles']['cantidad_horas']);
                    $rental_invoices->setPrice($valor['precio']);

                    $rental_invoices->save();
                },
            };

            $type($valor);
        }

    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO invoices(receipt, customer_id, user_id,date,total) VALUES (:receipt, :customer_id, :user_id, :date, :total)');

        $nuevaFactura->execute([
            'receipt' => $this->receipt,
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'total' => $this->total
        ]);
    }

    public function getAll()
    {
        $query = $this->query("SELECT LPAD(i.invoice_id,7,'0') AS invoice_id, c.name AS customer_id, CONCAT(u.name,' ',u.lastname) AS user_id, date, total FROM invoices i
        INNER JOIN customers c ON c.customer_id = i.customer_id
        INNER JOIN users u ON u.user_id = i.user_id");

        $invoices = $query->fetchAll(PDO::FETCH_ASSOC);

        return $invoices;
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT invoice_id,LPAD(invoice_id,7,'0') AS invoice,receipt, customer_id,user_id,date,total FROM invoices WHERE invoice_id = :invoice_id");

        $query->execute([
            'invoice_id' => $id
        ]);

        $invoice = $query->fetch();

        $this->invoice = $invoice['invoice'];
        $this->invoice_id = $invoice['invoice_id'];
        $this->receipt = $invoice['receipt'];
        $this->customer_id = $invoice['customer_id'];
        $this->user_id = $invoice['user_id'];
        $this->date = $invoice['date'];
        $this->total = $invoice['total'];
    }

    public function getLastID(){
        $consultarIDFactura = $this->query("SELECT invoice_id,LPAD(invoice_id,7,'0') AS invoice,customer_id,user_id,date FROM invoices ORDER BY invoice_id DESC LIMIT 1");

        $factura = $consultarIDFactura->fetch();

        $this->invoice = $factura['invoice'];
        $this->invoice_id = $factura['invoice_id'];
        $this->customer_id = $factura['customer_id'];
        $this->user_id = $factura['user_id'];
        $this->date = $factura['date'];
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
    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getReceipt()
    {
        return $this->receipt;
    }
    
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice): void
    {
        $this->invoice = $invoice;
    }



}