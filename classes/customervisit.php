<?php

class CustomerVisit extends Model implements IModel
{

    private $customer_id;
    private $visit_id;

    public function save(...$args)
    {
        foreach ($args[0] as $dato){
            $nuevaVisita = $this->prepare('INSERT INTO customer_visit(customer_id,visit_id) VALUES (:id_cliente, :id_visita)');
            $nuevaVisita->execute([
                'id_cliente' => $dato,
                'id_visita' => $this->visit_id
            ]);
        }
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM customer_visit');

        $customer_visit = $query->fetchAll(PDO::FETCH_ASSOC);

        return $customer_visit;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT cv.customer_id, cv.visit_id, c.name FROM customer_visit cv
        INNER JOIN customers c ON cv.customer_id = c.customer_id
        WHERE cv.visit_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $visit_area = $query->fetchAll(PDO::FETCH_ASSOC);

        return $visit_area;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setVisitId($visit_id)
    {
        $this->visit_id = $visit_id;
    }

    public function getVisitId()
    {
        return $this->visit_id;
    }
}
