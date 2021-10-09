<?php

class VisitArea extends Model implements IModel
{

    public function save(...$args)
    {
        foreach ($args[1] as $datos => $valor) {
            $nuevaVisitaArea = $this->prepare('INSERT INTO visits_areas(visit_id,area_id,arrival_time,departure_time) VALUES (:id_visita, :id_area, :hora_llegada, :hora_salida)');

            $nuevaVisitaArea->execute([
                'id_visita' => $args[0],
                'id_area' => $valor['id'],
                'hora_llegada' => $valor['arrival_time'],
                'hora_salida' => $valor['departure_time']
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
}