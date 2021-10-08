<?php

class Visit extends Model implements IModel
{
    private $visit_id;
    private $customer_id;
    private $reason_id;
    private $date;
    private $observation;

    private $arrival_time;
    private $departure_time;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded){

        $this->customer_id = $decoded['datos']['id_cliente'];
        $tipo_documento = $decoded['datos']['tipo_documento'];
        $this->reason_id = $decoded['datos']['id_razonvisita'];
        $areasChecked = $decoded['datos']['areaschecked'];
        $arrival_time = $decoded['datos']['arrival_time'];
        $departure_time = $decoded['datos']['departure_time'];
        $this->observation = $decoded['datos']['observacion'];

        $nuevaVisita = $this->prepare('INSERT INTO visits(customer_id, reason_id,date, observation) VALUES (:id_cliente, :razonvisita,CURRENT_DATE() ,:observacion)');

        $nuevaVisita->execute([
            'id_cliente'=>$this->customer_id,
            'razonvisita'=> $this->reason_id,
            'observacion'=>$this->observation
        ]);

        $consultarIDVisita = $this->query('SELECT visit_id FROM visits ORDER BY visit_id DESC LIMIT 1');
        $this->visit_id = $consultarIDVisita->fetch();

        for($i=0; $i<count($areasChecked);$i++) {
            $nuevaVisitaArea = $this->prepare('INSERT INTO visits_areas(visit_id,area_id,arrival_time,departure_time) VALUES (:id_visita, :id_area, :hora_llegada, :hora_salida)');

            $nuevaVisitaArea->execute([
                'id_visita' => $this->visit_id['visit_id'],
                'id_area' => $areasChecked[$i],
                'hora_llegada' => $arrival_time[$i],
                'hora_salida' => $departure_time[$i],

            ]);
        }
    }

    public function save(...$args)
    {
        // TODO: Implement save() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getTotal(){

        //SELECT COUNT(*) as total FROM visits WHERE (date BETWEEN :initial AND :final)
        $visits = $this->query("SELECT COUNT(*) AS total FROM visits WHERE (date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");

        $total = $visits->fetch();

        return $total;
    }

    public function getTimeDifAreas(){

        $areastime = $this->query("SELECT a.name, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(v.departure_time,v.arrival_time)))) AS diferencia FROM visits_areas v 
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

    public function getTimeDifTotalAreas(){
        $areastimeTotal = $this->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(v.departure_time,v.arrival_time)))) AS total FROM visits_areas v 
INNER JOIN visits s ON v.visit_id = s.visit_id
WHERE (s.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");

        $total = $areastimeTotal->fetch();

        return $total;
    }

    public function getTimeDifRazones(){

        $razonestime = $this->query("SELECT r.name, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(va.departure_time,va.arrival_time)))) AS diferencia FROM visits v 
INNER JOIN visits_areas va ON v.visit_id = va.visit_id 
RIGHT JOIN reason_visits r ON v.reason_id = r.reason_id
WHERE (v.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())
OR (TIMEDIFF(va.departure_time,va.arrival_time) IS NULL)
OR (TIMEDIFF(va.departure_time,va.arrival_time) IS NOT NULL)
GROUP BY r.reason_id");

        $total = $razonestime->fetchAll(PDO::FETCH_ASSOC);

        return $total;
    }

    public function getTimeDifTotalRazones(){
        $razonestimeTotal = $this->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(va.departure_time,va.arrival_time)))) AS total FROM visits v 
INNER JOIN visits_areas va ON v.visit_id = va.visit_id
WHERE (v.date BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE) AND CURDATE())");

        $total = $razonestimeTotal->fetch();

        return $total;
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