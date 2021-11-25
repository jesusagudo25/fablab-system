<?php

class Visit extends Model implements IModel
{
    private $visit_id;
    private $customer_id;
    private $reason_id;
    private $date;
    private $observation;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded){
        $newCustomer = isset($decoded['newCustomer']) ? $decoded['newCustomer'] : '';

        if (!empty($newCustomer)) {
            $customer = new Customer();

            //Validaciones
            $customer->setDocumentType($newCustomer['tipo_documento']);
            $customer->setDocument($newCustomer['documento']);
            $customer->setCode(empty($newCustomer['codigo']) ? NULL : $newCustomer['codigo']);
            $customer->setName($newCustomer['nombre']);
            $customer->setEmail(empty($newCustomer['email']) ? NULL : $newCustomer['email']);
            $customer->setTelephone(empty($newCustomer['telefono']) ? NULL : $newCustomer['telefono']);
            $customer->setAgeRange($newCustomer['edad']);
            $customer->setSexo($newCustomer['sexo']);
            $customer->setProvince($newCustomer['provincia']);
            $customer->setCity($newCustomer['distrito']);
            $customer->setTownship($newCustomer['corregimiento']);
            $customer->save();
            $this->customer_id = $customer->getLastID();

        }
        else{
            $this->customer_id = isset($decoded['id_cliente']) ? $decoded['id_cliente'] : '';
        }

        $this->reason_id = isset($decoded['id_razonvisita']) ? $decoded['id_razonvisita'] : '';
        $areasChecked = isset($decoded['areasChecked']) ? $decoded['areasChecked'] : '';
        $this->date = isset($decoded['fecha']) ? $decoded['fecha'] : '';
        $this->observation = empty($decoded['observacion']) ? NULL : $decoded['observacion'];

        $this->save();
        $this->getLastID();

        if (!empty($areasChecked)) {
            $visits_areas = new VisitArea();
            $visits_areas->save($this->visit_id, $areasChecked);
        }

    }

    public function save(...$args) //Save solo table_visit
    {
        $nuevaVisita = $this->prepare('INSERT INTO visits(customer_id, reason_id,date, observation) VALUES (:id_cliente, :razonvisita,:fecha ,:observacion)');

        $nuevaVisita->execute([
            'id_cliente' => $this->customer_id,
            'razonvisita' => $this->reason_id,
            'fecha' => $this->date,
            'observacion' => $this->observation
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT v.visit_id, c.name AS customer_id, r.name AS reason_id,r.time ,v.date, CONCAT(SUBSTRING(v.observation ,1,20),"...") as observation FROM visits v
        INNER JOIN customers c ON c.customer_id = v.customer_id
        INNER JOIN reason_visits r ON r.reason_id = v.reason_id');

        $visits = $query->fetchAll(PDO::FETCH_ASSOC);

        return $visits;
    }

    public function getVisitsNotFree($start_date,$end_date){

        $query = $this->prepare("SELECT COUNT(*) AS total FROM visits v 
    INNER JOIN reason_visits r ON v.reason_id = r.reason_id 
    WHERE (v.date BETWEEN :start_date AND :end_date)                                         AND (r.time = 1)");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $visits = $query->fetch();

        return $visits;
    }

    public function getVisitsFree($start_date,$end_date){
        $query = $this->prepare("SELECT COUNT(*) AS total FROM visits v 
    INNER JOIN reason_visits r ON v.reason_id = r.reason_id 
    WHERE (v.date BETWEEN :start_date AND :end_date)                                       
    AND (r.time = 0)");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $visitasFree = $query->fetch();

        return $visitasFree['total'];
    }

    public function getTimeDifAreas($start_date,$end_date){

        $query = $this->prepare("SELECT a.name, COALESCE(x.diferencia,0) AS diferencia FROM
(
    SELECT SUM(HOUR(TIMEDIFF(v.departure_time,v.arrival_time))) AS diferencia, a.area_id FROM visits_areas v 
    INNER JOIN visits s ON v.visit_id = s.visit_id 
    RIGHT JOIN areas a ON v.area_id = a.area_id
    WHERE (s.date BETWEEN :start_date AND :end_date)
    GROUP BY a.area_id
) as x
RIGHT JOIN areas a ON x.area_id = a.area_id
GROUP BY a.area_id;");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $areastime = $query->fetchAll(PDO::FETCH_ASSOC);

        return $areastime;
    }

    public function getTimeDifRazones($start_date,$end_date){

        $query = $this->prepare("SELECT r.name, COALESCE(x.diferencia,0) AS diferencia, r.time FROM
(
    SELECT SUM(HOUR(TIMEDIFF(va.departure_time,va.arrival_time))) AS diferencia, r.reason_id FROM visits v
    INNER JOIN visits_areas va ON v.visit_id = va.visit_id
    RIGHT JOIN reason_visits r ON v.reason_id = r.reason_id
    WHERE (v.date BETWEEN :start_date AND :end_date)
    GROUP BY r.reason_id
) as x
RIGHT JOIN reason_visits r ON x.reason_id = r.reason_id
GROUP BY r.reason_id;");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $razonestime = $query->fetchAll(PDO::FETCH_ASSOC);

        return $razonestime;
    }

    public function getTimeDifTotal($start_date,$end_date){
        $query = $this->prepare("SELECT COALESCE(SUM(HOUR(TIMEDIFF(v.departure_time,v.arrival_time))),0) AS total FROM visits_areas v 
INNER JOIN visits s ON v.visit_id = s.visit_id
WHERE (s.date BETWEEN :start_date AND :end_date)");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $areastimeTotal = $query->fetch();

        return $areastimeTotal;
    }


    public function getLastID(){
        $consultarIDVisita = $this->query('SELECT visit_id FROM visits ORDER BY visit_id DESC LIMIT 1');
        $visitaResultado = $consultarIDVisita->fetch();
        $this->visit_id = $visitaResultado['visit_id'];
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