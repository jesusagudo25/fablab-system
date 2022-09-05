<?php

class Resin extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM resins');

        $resins = $query->fetchAll(PDO::FETCH_ASSOC);

        return $resins;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM resins WHERE resin_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($resin){

        $query = $this->prepare("SELECT *  FROM resins r
        WHERE name LIKE CONCAT('%',:resin,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'resin' => $resin
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['resin_id'], "price" => $row['price'],"purchased_weight" => $row['purchased_weight'], "current_weight" => $row['current_weight']);
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