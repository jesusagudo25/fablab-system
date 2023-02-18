<?php

class Invoice extends Model implements IModel
{
    private $invoice_id;
    private $invoice;
    private $receipt;
    private $customer_id;
    private $user_id;
    private $date;
    private $use_type;
    private $total;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded, $user_id, $typeSale)
    {

        $id_cliente = empty($decoded['id_cliente']) ? 0 : $decoded['id_cliente'];

        if (isset($decoded['newCustomer'])) {
            $customer = new Customer();
            $customer->setDocumentType($decoded['newCustomer']['tipo_documento']);
            $customer->setDocument($decoded['newCustomer']['documento']);
            $customer->setEmail($decoded['newCustomer']['email']);
            $customer->setName($decoded['newCustomer']['nombre']);
            $customer->setTelephone(empty($decoded['newCustomer']['telefono']) ? null : $decoded['newCustomer']['telefono']);
            $customer->setAgeRange($decoded['newCustomer']['edad']);
            $customer->setSexo($decoded['newCustomer']['sexo']);
            $customer->setProvince($decoded['newCustomer']['provincia']);
            $customer->setCity($decoded['newCustomer']['distrito']);
            $customer->setTownship($decoded['newCustomer']['corregimiento']);
            $customer->save();
            $id_cliente = $customer->getLastCustomer();
        }

        $this->customer_id = $id_cliente;
        $this->user_id = $user_id;
        $this->total = $decoded['total'];
        $this->use_type = empty($decoded['reason']) ? null : $decoded['reason'];
        $this->date_delivery = isset($decoded['date_delivery']) ? $decoded['date_delivery'] : null;
        $this->save();
        $this->getLastID();

        if ($typeSale == 'machines') {

            foreach ($decoded['servicios_ag'] as $datos => $valor) {

                $type = match ($valor['descripcion']) {

                    'Electrónica' => function ($valor) {

                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['components'])) {
                            foreach ($valor['detalles']['components'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleComponent = new UseSaleComponent();
                                $saleComponent->setUseId($useSaleMachine->getUseId());
                                $saleComponent->setComponentId($valor['component_id']);

                                //Reverifico el stock
                                $component = new Component();
                                $stock = $component->getStock($valor['component_id']);
                                $total = $stock - $valor['cantidad'];
                                $component->updateStock($valor['component_id'], $total);

                                $saleComponent->setNumberComponents($valor['cantidad']);
                                $saleComponent->save();
                            }
                        }
                    },
                    'Mini Fresadora CNC' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['materials'])) {
                            foreach ($valor['detalles']['materials'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleMaterialMiniMilling();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setMaterialId($valor['material_id']);

                                //Reverifico el stock
                                $material = new MaterialMilling();
                                $stock = $material->getStock($valor['material_id']);
                                $total = $stock - $valor['cantidad'];
                                $material->updateStock($valor['material_id'], $total);

                                $saleMaterial->setAmount($valor['cantidad']);
                                $saleMaterial->save();
                            }
                        }
                    },
                    'Láser CNC' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['materials'])) {
                            foreach ($valor['detalles']['materials'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleMaterialLaser();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setMaterialId($valor['material_id']);
                                $saleMaterial->setAmount($valor['cantidad']);
                                $saleMaterial->setWidth($valor['width']);
                                $saleMaterial->setHeight($valor['height']);

                                //Stock por verificar en la clase: MaterialLaser
                                $materialLaser = new MaterialLaser();
                                $stock = $materialLaser->getStock($valor['material_id']);
                                $total = $stock - ($valor['cantidad'] * ($valor['width'] * $valor['height']));
                                $materialLaser->updateStock($valor['material_id'], $total);

                                $saleMaterial->save();
                            }
                        }
                    },
                    'Cortadora de Vinilo' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['materials'])) {
                            foreach ($valor['detalles']['materials'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleVinilo();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setViniloId($valor['vinilo_id']);
                                $saleMaterial->setWidth($valor['width']);
                                $saleMaterial->setHeight($valor['height']);

                                //Stock por verificar en la clase: Vinilo
                                $vinilo = new Vinilo();
                                $currentVinilo = $vinilo->get($valor['vinilo_id']);
                                $total = $currentVinilo['area'] - ($currentVinilo['width'] * $valor['height']);
                                $vinilo->updateStock($valor['vinilo_id'], $total);

                                $saleMaterial->save();
                            }
                        }
                    },
                    'Impresión 3D en filamento' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['filaments'])) {
                            foreach ($valor['detalles']['filaments'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleFilament();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setFilamentId($valor['filament_id']);
                                $saleMaterial->setNumberGrams($valor['cantidad']);

                                //Verificar Stock
                                $filament = new Filament();
                                $stock = $filament->getStock($valor['filament_id']);
                                $total = $stock - $valor['cantidad'];
                                $filament->updateStock($valor['filament_id'], $total);


                                $saleMaterial->save();
                            }
                        }
                    },
                    'Impresión 3D en resina' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['resins'])) {
                            foreach ($valor['detalles']['resins'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleResin();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setResinId($valor['resin_id']);
                                $saleMaterial->setNumberGrams($valor['cantidad']);

                                //Verificar Stock
                                $resin = new Resin();
                                $stock = $resin->getStock($valor['resin_id']);
                                $total = $stock - $valor['cantidad'];
                                $resin->updateStock($valor['resin_id'], $total);


                                $saleMaterial->save();
                            }
                        }
                    },
                    'Software de diseño' => function ($valor) {
                        $useSaleMachine = new UseSoftwareDesign();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setSoftwareId($valor['detalles']['software']);
                        $useSaleMachine->setNumberHours($valor['detalles']['horas_area']);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                    },
                    'Bordadora CNC' => function ($valor) {
                        $useSaleMachine = new UseSaleMachine();
                        $useSaleMachine->setInvoiceId($this->invoice_id);
                        $useSaleMachine->setAreaId($valor['servicio']);
                        $useSaleMachine->setNumberMinutes(isset($valor['detalles']['minutos_area']) ? $valor['detalles']['minutos_area'] : 0);
                        $useSaleMachine->setBaseCost($valor['detalles']['costo_base']);
                        $useSaleMachine->save();
                        $useSaleMachine->getLastID();

                        if (isset($valor['detalles']['threads'])) {
                            foreach ($valor['detalles']['threads'] as $datos => $valor) {

                                //Registro en ventas de componentes
                                $saleMaterial = new UseSaleThread();
                                $saleMaterial->setUseId($useSaleMachine->getUseId());
                                $saleMaterial->setThreadId($valor['thread_id']);
                                $saleMaterial->setNumberStitches($valor['cantidad']);

                                //Verificar Stock
                                $thread = new Thread();
                                $currentThread = $thread->get($valor['thread_id']);

                                if ($currentThread['purchased_amount'] == 550) {
                                    if ($valor['cantidad'] > 0 && $valor['cantidad'] <= 10000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 3 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 10000 && $valor['cantidad'] <= 20000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 5 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 20000 && $valor['cantidad'] <= 30000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 10 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 30000 && $valor['cantidad'] <= 40000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 15 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    }
                                } else {
                                    if ($valor['cantidad'] > 0 && $valor['cantidad'] <= 10000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 2 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 10000 && $valor['cantidad'] <= 20000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 4 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 20000 && $valor['cantidad'] <= 30000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 8 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    } elseif ($valor['cantidad'] > 30000 && $valor['cantidad'] <= 40000) {
                                        $total = $currentThread['current_amount'] - ($currentThread['current_amount'] * 13 / 100);
                                        $thread->updateStock($valor['thread_id'], $total);
                                    }
                                }


                                $saleMaterial->save();
                            }
                        }
                    }
                };

                $type($valor);
            }

            if ($this->use_type == 'S') {
                $task = new Task();
                $task->setCustomerId($this->customer_id);
                $task->setName('Factura: ' . $this->invoice_id);
                $task->setDescription('Descripción de la tarea');
                $task->setDateDelivery($decoded['fecha_entrega']);
                $task->save();
            }
        } else {
            foreach ($decoded['servicios_ag'] as $datos => $valor) {

                $type = match ($valor['categoria']) {
                    'membresias', => function ($valor) {
                        $membership_invoices = new MembershipInvoices();

                        $membership_invoices->setInvoiceId($this->invoice_id);
                        $membership_invoices->setMembershipId($valor['servicio']);
                        $membership_invoices->setInitialDate($valor['detalles']['fecha_inicial']);
                        $membership_invoices->setFinalDate($valor['detalles']['fecha_final']);
                        $membership_invoices->setPrice($valor['precio']);

                        $membership_invoices->save();
                    },
                    'eventos' => function ($valor) {
                        $invoices_events = new InvoicesEvents();

                        $invoices_events->setInvoiceId($this->invoice_id);
                        $invoices_events->setEventId($valor['detalles']['event_id']);

                        $invoices_events->save();
                    },
                };

                $type($valor);
            }
        }
    }

    public function save(...$args)
    {
        $nuevaFactura = $this->prepare('INSERT INTO invoices(customer_id, user_id,use_type, total) VALUES (:customer_id, :user_id, :use_type, :total)');

        $nuevaFactura->execute([
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'use_type' => $this->use_type,
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
        $query = $this->prepare("SELECT invoice_id,LPAD(invoice_id,7,'0') AS invoice,receipt, customer_id,user_id,date,total, use_type FROM invoices WHERE invoice_id = :invoice_id");

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
        $this->use_type = $invoice['use_type'];
    }

    public function getLastID()
    {
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
        $actualizarDatos = $this->prepare("UPDATE invoices SET receipt = :receipt WHERE invoice_id = :invoice_id;");
        $actualizarDatos->execute([
            'receipt' => $this->receipt,
            'invoice_id' => $this->invoice_id
        ]);
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

    /**
     * @param mixed $invoice_id
     */
    public function setInvoiceId($invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @param mixed $receipt
     */
    public function setReceipt($receipt): void
    {
        $this->receipt = $receipt;
    }

    public function setUseType($use_type): void
    {
        $this->use_type = $use_type;
    }

    public function getUseType()
    {
        return $this->use_type;
    }

    public function getTotalSales($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(total),0) AS total FROM invoices WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetch();

        return $total['total'];
    }

    public function getAmountSales($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COUNT(invoice_id) AS amount FROM invoices WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $amount = $query->fetch();

        return $amount['amount'];
    }

    public function getSalesForUseMachine($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(i.total),0) AS total FROM invoices i INNER JOIN use_sale_machine usl ON usl.invoice_id = i.invoice_id
        WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetch();

        return $total['total'];
    }

    public function getSalesForEvent($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(i.total),0) AS total FROM invoices i INNER JOIN invoices_events ie ON ie.invoice_id = i.invoice_id
        WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetch();

        return $total['total'];
    }

    public function getSalesForMembership($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(i.total),0) AS total FROM invoices i INNER JOIN membership_invoices mi ON mi.invoice_id = i.invoice_id
        WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetch();

        return $total['total'];
    }

    public function getSalesForUseMachineGroupByArea($start_date, $end_date)
    {
        $query = $this->prepare("SELECT a.name, COALESCE(x.total,0) AS total FROM
        (
            SELECT SUM(i.total) AS total, a.area_id FROM invoices i 
            INNER JOIN use_sale_machine usl ON usl.invoice_id = i.invoice_id
            RIGHT JOIN areas a ON a.area_id = usl.area_id
            WHERE (i.date BETWEEN :start_date AND :end_date)
            GROUP BY a.area_id
        ) as x
        RIGHT JOIN areas a ON x.area_id = a.area_id
        WHERE a.name != 'Software de diseño'
        GROUP BY a.area_id;");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetchAll();

        return $total;
    }

    public function getSalesForUseSoftware($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(i.total),0) AS total FROM invoices i INNER JOIN use_software_design usd ON usd.invoice_id = i.invoice_id
        WHERE date BETWEEN :start_date AND :end_date");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $total = $query->fetch();

        return $total['total'];
    }

    public function getAmountForUseType($start_date, $end_date)
    {
        $query = $this->prepare("SELECT
        COUNT(CASE WHEN i.use_type = 'S' THEN i.invoice_id END) AS S,
        COUNT(CASE WHEN i.use_type = 'M' THEN i.invoice_id END) AS M 
        FROM invoices i
        WHERE (i.date BETWEEN :start_date AND :end_date)");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $amount = $query->fetchAll();

        return $amount;
    }
}
