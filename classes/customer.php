<?php

class Customer extends Model implements IModel
{

    private $customer_id;
    private $document_type;
    private $document;
    private $code;
    private $telephone;
    private $age_range;
    private $sexo;
    private $name;
    private $email;
    private $province;
    private $city;
    private $township;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAjax($documento,$tipo){

        $query = $this->prepare("SELECT *  FROM customers c
        WHERE document_type = :tipo AND document LIKE CONCAT('%',:documento,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'documento' => $documento,
            'tipo'=>$tipo
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['document'], "id" => $row['customer_id'], "code" => $row['code'], "name" => $row['name'], "email" => $row['email'], "telephone" => $row['telephone'],"age_range" => $row['range_id'],"sex" => $row['sex'],"province" => $row['province_id'], "district" => $row['district_id'], "township" => $row['township_id']);
        }

        return $datos;
    }

    public function save(...$args){
        $nuevoCliente = $this->prepare('INSERT INTO customers(document_type, document,code, name,email,telephone,range_id,sex,province_id,district_id,township_id) VALUES (:document_type, :document ,:code ,:name, :email, :telephone,:range_id, :sex,:province, :city, :township)');

        $nuevoCliente->execute([
            'document_type'=>$this->document_type,
            'document'=> $this->document,
            'code'=> $this->code,
            'name'=>$this->name,
            'email'=>$this->email,
            'telephone'=> $this->telephone,
            'range_id'=> $this->age_range,
            'sex'=> $this->sexo,
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
        $query = $this->query('SELECT v.visit_id , va.area_id, c.name AS nombre_cliente,a.name AS nombre_area,va.departure_time FROM visits v
                                        INNER JOIN visits_areas va ON v.visit_id = va.visit_id
                                        INNER JOIN customers c ON v.customer_id = c.customer_id
        INNER JOIN areas a ON va.area_id = a.area_id
                                        WHERE (va.departure_time IS NULL ) AND (v.status = 1);');
        $customers = $query->fetchAll(PDO::FETCH_ASSOC);

        return $customers;
    }

    public function getAllAgeRange($start_date,$end_date){
        $query = $this->prepare("SELECT ar.name, COALESCE(x.total,0) AS total FROM
        (
        SELECT COUNT(v.visit_id) AS total, ar.range_id FROM visits v
        INNER JOIN customers c ON v.customer_id = c.customer_id
        RIGHT JOIN age_range ar ON c.range_id = ar.range_id
        WHERE (v.date BETWEEN :start_date AND :end_date)
        AND (v.status = 1)
        GROUP BY ar.range_id
        ) as x
        RIGHT JOIN age_range ar ON x.range_id = ar.range_id
        GROUP BY ar.range_id;");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $sexType = $query->fetchAll(PDO::FETCH_ASSOC);

        return $sexType;
    }

    public function getAllTypeSex($start_date,$end_date){
        $query = $this->prepare("SELECT
        COUNT(CASE WHEN c.sex = 'M' THEN c.customer_id END) AS M,
        COUNT(CASE WHEN c.sex = 'F' THEN c.customer_id END) AS F 
        FROM visits v
        INNER JOIN customers c ON c.customer_id = v.customer_id
        WHERE (v.date BETWEEN :start_date AND :end_date)
        AND (v.status = 1);");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $ageRanges = $query->fetchAll(PDO::FETCH_ASSOC);

        return $ageRanges;
    }

    public function getAll(){
        $query = $this->query('SELECT c.customer_id, c.document_type, c.document, c.code, c.name AS customer_name, ar.name AS range_name, c.sex, c.email, c.telephone, p.name AS province_name, d.name AS district_name, t.name AS township_name, c.status FROM customers c
        INNER JOIN age_range ar ON c.range_id = ar.range_id
        INNER JOIN provinces p ON c.province_id = p.province_id
        INNER JOIN districts d ON c.district_id = d.district_id
        INNER JOIN townships t ON c.township_id = t.township_id
        ');

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        return $events;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM customers WHERE customer_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $customer = $query->fetch(PDO::FETCH_ASSOC);

        return $customer;
    }

    public function getDetails($id){
        $query = $this->prepare("SELECT c.document_type, c.document,c.code, c.name, c.email, c.telephone, p.name AS province, d.name AS city, t.name AS township  FROM customers c 
        INNER JOIN provinces p ON p.province_id = c.province_id
        INNER JOIN districts d ON d.district_id = c.district_id
        INNER JOIN townships t ON t.township_id = c.township_id
        WHERE customer_id = :customer_id");

        $query->execute([
            'customer_id' => $id
        ]);

        $customer = $query->fetch(PDO::FETCH_ASSOC);

        $this->document = $customer['document'];
        $this->document_type = $customer['document_type'];
        $this->code = $customer['code'];
        $this->telephone = $customer['telephone'];
        $this->name = $customer['name'];
        $this->email = $customer['email'];
        $this->province = $customer['province'];
        $this->city = $customer['city'];
        $this->township = $customer['township'];
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE customers SET status = :status WHERE customer_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE customers SET document_type = :document_type, document = :document, code = :code, name = :name, range_id = :range_id, sex = :sex, email = :email, telephone = :telephone, province_id = :province_id, district_id = :district_id, township_id = :township_id WHERE customer_id = :id;");
        $actualizarDatos->execute([
            'document_type' => $this->document_type,
            'document' => $this->document,
            'code' => $this->code,
            'name' => $this->name,
            'range_id' => $this->age_range,
            'sex' => $this->sexo,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'province_id' => $this->province,
            'district_id' => $this->city,
            'township_id' => $this->township,
            'id'=>$this->customer_id
        ]);
    }

    public function checkDocument($document){
        $miConsulta = $this->prepare('SELECT COUNT(*) as length FROM customers WHERE document = :document;');

        $miConsulta->execute([
            'document' => $document
        ]);

        $resultado = $miConsulta->fetch();

        return $resultado;
    }

    public function checkCode($code){
        $miConsulta = $this->prepare('SELECT COUNT(*) as length FROM customers WHERE code = :code;');

        $miConsulta->execute([
            'code' => $code
        ]);

        $resultado = $miConsulta->fetch();

        return $resultado;
    }

    public function checkEmail($email){
        $miConsulta = $this->prepare('SELECT COUNT(*) as length FROM customers WHERE email = :email;');

        $miConsulta->execute([
            'email' => $email
        ]);

        $resultado = $miConsulta->fetch();

        return $resultado;
    }

    public function checkTelephone($telephone){
        $miConsulta = $this->prepare('SELECT COUNT(*) as length FROM customers WHERE telephone = :telephone;');

        $miConsulta->execute([
            'telephone' => $telephone
        ]);

        $resultado = $miConsulta->fetch();

        return $resultado;
    }

    public function verifyRecord(){
        $miConsulta = $this->prepare('SELECT customer_id, document_type, document, code, name, range_id, sex, email, telephone, province_id, district_id, township_id  FROM customers WHERE document_type = :type AND document = :document AND status = 1');

        $miConsulta->execute([
            'type' => $this->document_type,
            'document' =>$this->document
        ]);

        $resultado = $miConsulta->fetch(PDO::FETCH_ASSOC);

        return $resultado;
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

    /**
     * @return mixed
     */
    public function getAgeRange()
    {
        return $this->age_range;
    }

    /**
     * @param mixed $age_range
     */
    public function setAgeRange($age_range): void
    {
        $this->age_range = $age_range;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param mixed $sexo
     */
    public function setSexo($sexo): void
    {
        $this->sexo = $sexo;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param mixed $customer_id
     */
    public function setCustomerID($customer_id): void
    {
        $this->customer_id = $customer_id;
    }    
    
    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getTownship()
    {
        return $this->township;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getDocumentType()
    {
        return $this->document_type;
    }

}