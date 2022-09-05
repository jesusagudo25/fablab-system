<?php

class Filament extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM filaments');

        $filaments = $query->fetchAll(PDO::FETCH_ASSOC);

        return $filaments;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM filaments WHERE filament_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($filament){

        $query = $this->prepare("SELECT *  FROM filaments f
        WHERE name LIKE CONCAT('%',:filament,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'filament' => $filament
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['filament_id'], "price" => $row['price'],"purchased_weight" => $row['purchased_weight'], "current_weight" => $row['current_weight']);
        }

        return $datos;
    }

    public function deleteSave(...$args){

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