<?php

class Customer extends Model implements IModel
{

    private $customer_id;
    private $document_type;
    private $document;
    private $code;
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

        $query = $this->prepare("SELECT document, customer_id, code, name, email, telephone, province_id, district_id, township_id  FROM customers c
WHERE document LIKE CONCAT('%',:documento,'%') AND status = 1 AND document_type = :tipo");
        $query->execute([
            'documento' => $documento,
            'tipo'=>$tipo
        ]);

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['document'], "id" => $row['customer_id'], "code" => $row['code'], "name" => $row['name'], "email" => $row['email'], "telephone" => $row['telephone'], "province" => $row['province_id'], "district" => $row['district_id'], "township" => $row['township_id']);
        }

        return $datos;
    }

    public function save(...$args)
    {
        $nuevoCliente = $this->prepare('INSERT INTO customers(document_type, document,code, name,email,telephone,province,city,township) VALUES (:document_type, :document ,:code ,:name, :email, :telephone, :province, :city, :township)');

        $nuevoCliente->execute([
            'document_type'=>$this->document_type,
            'document'=> $this->document,
            'code'=> $this->code,
            'name'=>$this->name,
            'email'=>$this->email,
            'telephone'=> $this->telephone,
            'province'=> $this->province,
            'city'=>$this->city,
            'township'=>$this->township
        ]);
    }

    public function getLastID(){
        $consultarIDCliente = $this->query('SELECT customer_id FROM customers ORDER BY customer_id DESC LIMIT 1');
        $this->customer_id = $consultarIDCliente->fetch();

        return $this->customer_id['customer_id'];
    }

    public function getLabo(){
        $query = $this->query('SELECT c.name AS Nombre, va.departure_time AS "Fecha de salida", v.visit_id AS Acción, va.area_id FROM visits v
                                        INNER JOIN visits_areas va ON v.visit_id = va.visit_id
                                        INNER JOIN customers c ON v.customer_id = c.customer_id
                                        WHERE (va.departure_time = "00:00:00")');
        $customers = $query->fetchAll(PDO::FETCH_ASSOC);

        return $customers;
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

    /**
     * @param mixed $document_type
     */
    public function setDocumentType($document_type): void
    {
        $this->document_type = $document_type;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document): void
    {
        $this->document = $document;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province): void
    {
        $this->province = $province;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @param mixed $township
     */
    public function setTownship($township): void
    {
        $this->township = $township;
    }


}