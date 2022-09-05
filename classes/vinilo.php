<?php

class Vinilo extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM vinilos');

        $components = $query->fetchAll(PDO::FETCH_ASSOC);

        return $components;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM vinilos WHERE vinilo_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($vinilos){

        $query = $this->prepare("SELECT *  FROM vinilos v
        WHERE name LIKE CONCAT('%',:vinilos,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'vinilos' => $vinilos
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['vinilo_id'], "price" => $row['price'],"width" => $row['width'],"height" => $row['height']);
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