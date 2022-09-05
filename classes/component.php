<?php

class Component extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM components');

        $components = $query->fetchAll(PDO::FETCH_ASSOC);

        return $components;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM components WHERE component_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($component){

        $query = $this->prepare("SELECT *  FROM components c
        WHERE name LIKE CONCAT('%',:component,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'component' => $component
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['component_id'], "price" => $row['price'], "stock" => $row['stock'],"category_id" => $row['category_id']);
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