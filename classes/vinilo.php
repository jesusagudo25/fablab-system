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

        $component = $query->fetch(PDO::FETCH_ASSOC);

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
            $datos[] = array("label" => $row['name'], "id" => $row['vinilo_id'], "price" => $row['price'],"width" => $row['width'],"height" => $row['height'], "area" => $row['area']);
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

    public function getStock($vinilo_id)
    {
        $query = $this->prepare('SELECT area FROM vinilos WHERE vinilo_id = :vinilo_id');
        $query->execute([
            'vinilo_id' => $vinilo_id
        ]);

        $stock = $query->fetch(PDO::FETCH_ASSOC);

        return $stock['area'];
    }

    public function updateStock($material_id, $area)
    {
        $query = $this->prepare('UPDATE vinilos SET area = :area WHERE vinilo_id = :vinilo_id');
        $query->execute([
            'area' => $area,
            'vinilo_id' => $material_id
        ]);
    }
}