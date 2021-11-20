<?php

class Invoice extends Model implements IModel
{
    private $invoice_id;
    private $invoice;
    private $customer_id;
    private $user_id;
    private $date;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded,$user_id){
        $this->customer_id = $decoded['id_cliente'];
        $this->user_id = $user_id;
        $this->date = $decoded['fecha'];

        $this->save();

        $this->getLastID();

        $contadorFilas = [0,0,0,0];

        foreach ($decoded['servicios_ag'] as $datos => $valor){

            $type = match ($valor['categoria']) {
                'membresias', => function ($valor,&$contador){
                    $membership_invoices = new MembershipInvoices();

                    $membership_invoices->setNumDetail(++$contador[0]);
                    $membership_invoices->setInvoiceId($this->invoice_id);
                    $membership_invoices->setMembershipId($valor['servicio']);
                    $membership_invoices->setInitialDate($valor['detalles']['fecha_inicial']);
                    $membership_invoices->setFinalDate($valor['detalles']['fecha_final']);
                    $membership_invoices->setPrice($valor['precio']);

                    $membership_invoices->save();
                },
                'eventos' => function ($valor,&$contador){
                    $event = new Events();

                    $event->setCategoryId($valor['servicio']);
                    $event->setName($valor['detalles']['nombre_evento']);
                    $event->setInitialDate($valor['detalles']['fecha_inicial']);
                    $event->setFinalDate($valor['detalles']['fecha_final']);
                    $event->setNumberHours($valor['detalles']['cantidad_horas']);
                    $event->setPrice($valor['precio']);
                    $event->setExpenses(empty($valor['detalles']['gastos_evento']) ? NULL : $valor['detalles']['gastos_evento']);
                    $event->setDescriptionExpenses(empty($valor['detalles']['desc_gastos']) ? NULL : $valor['detalles']['desc_gastos']);

                    $event->save();

                    $event->getLastID();

                    $invoices_events = new InvoicesEvents();

                    $invoices_events->setNumDetail(++$contador[1]);
                    $invoices_events->setInvoiceId($this->invoice_id);
                    $invoices_events->setEventId($event->getEventId());

                    $invoices_events->save();
                },
                'areas' => function ($valor,&$contador){
                    $use_machine = new UseMachines();

                    $use_machine->setAreaId($valor['servicio']);
                    $use_machine->setConsumableId($valor['detalles']['tipo_consumible']);
                    $use_machine->setAmount($valor['detalles']['cantidad_unidad']);
                    $use_machine->setUnitPrice($valor['detalles']['precio_unitario']);
                    $use_machine->setPrintingTime($valor['detalles']['cantidad_tiempo']);
                    $use_machine->setPrintingPrice($valor['detalles']['precio_impresion']);
                    $use_machine->setBasePrice($valor['detalles']['costo_base']);
                    $use_machine->setProfitPercentages($valor['detalles']['porcentaje_ganancia']);
                    $use_machine->setDiscountPercentage($valor['detalles']['porcentaje_descuento']);
                    $use_machine->setTotalPrice($valor['detalles']['costo_total']);

                    $use_machine->save();

                    $use_machine->getLastID();

                    $invoices_use_machines = new InvoicesUseMachines();

                    $invoices_use_machines->setNumDetail(++$contador[2]);
                    $invoices_use_machines->setInvoiceId($this->invoice_id);
                    $invoices_use_machines->setUseId($use_machine->getUseId());

                    $invoices_use_machines->save();
                },
                'alquiler' => function ($valor,&$contador){
                    $rental_invoices = new RentalInvoices();

                    $rental_invoices->setNumDetail(++$contador[3]);
                    $rental_invoices->setInvoiceId($this->invoice_id);
                    $rental_invoices->setCategoryId($valor['servicio']);
                    $rental_invoices->setNumberHours($valor['detalles']['cantidad_horas']);
                    $rental_invoices->setPrice($valor['precio']);

                    $rental_invoices->save();
                },
            };

            $type($valor,$contadorFilas);

        }


    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO invoices(customer_id, user_id,date) VALUES (:customer_id, :user_id,:date)');

        $nuevaFactura->execute([
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'date' => $this->date
        ]);
    }

    public function getAll()
    {
        $query = $this->query("SELECT i.invoice_id, c.name AS customer_id, CONCAT(u.name,' ',u.lastname) AS user_id, date FROM invoices i
        INNER JOIN customers c ON c.customer_id = i.customer_id
        INNER JOIN users u ON u.user_id = i.user_id");

        $invoices = $query->fetchAll(PDO::FETCH_ASSOC);

        return $invoices;
    }

    public function get($id)
    {
        $query = $this->prepare("SELECT invoice_id,LPAD(invoice_id,7,'0') AS invoice,customer_id,user_id,date FROM invoices WHERE invoice_id = :invoice_id");

        $query->execute([
            'invoice_id' => $id
        ]);

        $invoice = $query->fetch();

        $this->invoice = $invoice['invoice'];
        $this->invoice_id = $invoice['invoice_id'];
        $this->customer_id = $invoice['customer_id'];
        $this->user_id = $invoice['user_id'];
        $this->date = $invoice['date'];
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