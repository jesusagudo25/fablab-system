<?php

class Customer extends Model implements IModel
{

    private $customer_id;
    private $document_type;
    private $document;
    private $telephone;
    private $name;
    private $email;
    private $province;
    private $city;
    private $township;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAjax($documento,$tipo){

        $query = $this->prepare("SELECT * FROM customers WHERE document LIKE CONCAT('%',:documento,'%') AND status = 1 AND document_type = :tipo");
        $query->execute([
            'documento' => $documento,
            'tipo'=>$tipo
        ]);

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['document'], "id" => $row['customer_id']);
        }

        return $datos;
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