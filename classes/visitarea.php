<?php

class VisitArea extends Model implements IModel
{
    private $visit_id;
    private $area_id;
    private $arrival_time;
    private $departure_time;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        foreach ($args[1] as $datos => $valor) {
            $nuevaVisitaArea = $this->prepare('INSERT INTO visits_areas(visit_id,area_id,arrival_time,departure_time) VALUES (:id_visita, :id_area, :hora_llegada, :hora_salida)');

            $hora_salida = empty($valor['departure_time']) ? NULL : $valor['departure_time'];
            $nuevaVisitaArea->execute([
                'id_visita' => $args[0],
                'id_area' => $valor['id'],
                'hora_llegada' => $valor['arrival_time'],
                'hora_salida' => $hora_salida
            ]);
        }
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
     * @param mixed $visit_id
     */
    public function setVisitId($visit_id): void
    {
        $this->visit_id = $visit_id;
    }

    /**
     * @param mixed $area_id
     */
    public function setAreaId($area_id): void
    {
        $this->area_id = $area_id;
    }

    /**
     * @param mixed $departure_time
     */
    public function setDepartureTime($departure_time): void
    {
        $this->departure_time = $departure_time;
    }

    public function setLabo(){
        $query = $this->prepare('UPDATE visits_areas SET departure_time = :departure_time WHERE (visit_id = :visit_id AND area_id = :area_id)');

        $hora_salida = empty($this->departure_time) ? NULL : $this->departure_time;

        $query->execute([
            'visit_id' => $this->visit_id,
            'area_id'=>$this->area_id,
            'departure_time' => $hora_salida
        ]);
    }
}