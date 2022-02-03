<?php

class Consumable extends Model implements IModel
{

    public function save(...$args)
    {
        foreach ($args[1] as $datos => $valor) {
            $nuevoConsumible = $this->prepare('INSERT INTO consumables(area_id,name,unit_price,printing_price,status) VALUES (:area_id, :name, :unit_price, :printing_price,:status)');


            $nuevoConsumible->execute([
                'area_id' => $args[0],
                'name' => $valor['name'],
                'unit_price' => $valor['unit'],
                'printing_price' => $valor['printing'],
                'status' => $valor['status']
            ]);
        }
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM consumables');

        $consumables = $query->fetchAll(PDO::FETCH_ASSOC);

        return $consumables;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM consumables WHERE area_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $area = $query->fetchAll(PDO::FETCH_ASSOC);

        return $area;
    }

    public function deleteSave(...$args){

        $deleteConsumables = $this->prepare('DELETE FROM consumables WHERE area_id = :area_id');

        $deleteConsumables->execute([
            'area_id' => $args[0]
        ]);

        foreach ($args[1] as $datos => $valor) {
            $nuevoConsumible = $this->prepare('INSERT INTO consumables(area_id,name,unit_price,printing_price,status) VALUES (:area_id, :name, :unit_price, :printing_price,:status)');


            $nuevoConsumible->execute([
                'area_id' => $args[0],
                'name' => $valor['name'],
                'unit_price' => $valor['unit'],
                'printing_price' => $valor['printing'],
                'status' => $valor['status']
            ]);
        }
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