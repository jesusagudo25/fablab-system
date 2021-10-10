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
        $newCustomer = isset($decoded['datos']['newCustomer']) ? $decoded['datos']['newCustomer'] : '';

        if (!empty($newCustomer)) {
            $customer = new Customer();
            $customer->setDocumentType($newCustomer['tipo_documento']);
            $customer->setDocument($newCustomer['documento']);
            $customer->setCode($newCustomer['codigo']);
            $customer->setName($newCustomer['nombre']);
            $customer->setEmail($newCustomer['email']);
            $customer->setTelephone($newCustomer['telefono']);
            $customer->setProvince($newCustomer['provincia']);
            $customer->setCity($newCustomer['ciudad']);
            $customer->setTownship($newCustomer['corregimiento']);
            $customer->save();
            $this->customer_id = $customer->getLastID();

        }
        else{
            $this->customer_id = isset($decoded['datos']['id_cliente']) ? $decoded['datos']['id_cliente'] : '';
        }

        $this->reason_id = isset($decoded['datos']['id_razonvisita']) ? $decoded['datos']['id_razonvisita'] : '';
        $areasChecked = isset($decoded['datos']['areasChecked']) ? $decoded['datos']['areasChecked'] : '';
        $this->date = isset($decoded['datos']['fecha']) ? $decoded['datos']['fecha'] : '';
        $this->observation = isset($decoded['datos']['observacion']) ? $decoded['datos']['observacion'] : '';

        /*
        if (empty($newUser)) {
            //Aqui insertar una visita

            if (!empty($areasChecked)) {

                //Aqui metodo para obtener el ultimo id de visita

                //Aqui ejecutar metodo save de visits_areas

            }
        }
        else{
            //Aqui registrar el nuevo usuario

            //Aqui metodo para obtener el ultimo id de visita

            //Aqui insertar una visita

            if (!empty($areasChecked)) {
                //Aqui metodo para obtener el ultimo id de visita

                //Aqui ejecutar metodo save de visits_areas
            }
        }*/

        ##--------------------->Nueva version


        $this->save();
        $this->getLastID();

        if (!empty($areasChecked)) {
            $visits_areas = new VisitArea();
            $visits_areas->save($this->visit_id, $areasChecked);
        }

        #Verificar si hay un nuevo cliente (Si hay, se crea) -> y se obtiene el ultimo id
        #Insertar una nueva visita -> se obtiene el ultimo id
        #Verificar si hay areas...

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
        // TODO: Implement getAll() method.
    }

    public function getVisitsNotFree(){

        //SELECT COUNT(*) as total FROM visits WHERE (date BETWEEN :initial AND :final)
        $visits = $this->query("SELECT COUNT(*) AS total FROM visits v INNER JOIN reason_visits r ON v.reason_id = r.reason_id WHERE r.time = 1 AND (v.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");

        $total = $visits->fetch();

        return $total;
    }

    public function getVisitsFree(){
        $consultarTotalVisitasFree = $this->query("SELECT COUNT(*) AS total FROM visits v INNER JOIN reason_visits r ON v.reason_id = r.reason_id WHERE r.time = 0 AND (v.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");
        $visitasFree = $consultarTotalVisitasFree->fetch();
        return $visitasFree['total'];
    }

    public function getTimeDifAreas(){

        $areastime = $this->query("SELECT a.name, HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(v.departure_time,v.arrival_time))))) AS diferencia FROM visits_areas v 
INNER JOIN visits s ON v.visit_id = s.visit_id 
RIGHT JOIN areas a ON v.area_id = a.area_id
WHERE (s.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())
OR (TIMEDIFF(v.departure_time,v.arrival_time) IS NULL)
OR (TIMEDIFF(v.departure_time,v.arrival_time) IS NOT NULL)
GROUP BY a.area_id
        ");

        $total = $areastime->fetchAll(PDO::FETCH_ASSOC);

        return $total;
    }

    public function getTimeDifTotal(){
        $areastimeTotal = $this->query("SELECT SUM(HOUR(TIMEDIFF(v.departure_time,v.arrival_time))) AS total FROM visits_areas v 
INNER JOIN visits s ON v.visit_id = s.visit_id
WHERE (s.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");

        $total = $areastimeTotal->fetch();

        return $total;
    }

    public function getTimeDifRazones(){

        $razonestime = $this->query("SELECT r.name, HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(va.departure_time,va.arrival_time))))) AS diferencia FROM visits v 
INNER JOIN visits_areas va ON v.visit_id = va.visit_id 
RIGHT JOIN reason_visits r ON v.reason_id = r.reason_id
WHERE (v.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())
AND (r.time = 1)
GROUP BY r.reason_id");

        $total = $razonestime->fetchAll(PDO::FETCH_ASSOC);

        return $total;
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