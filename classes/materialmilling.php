<?php

class MaterialMilling extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM materials_milling');

        $materials_milling = $query->fetchAll(PDO::FETCH_ASSOC);

        return $materials_milling;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM materials_milling WHERE material_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($materials){

        $query = $this->prepare("SELECT *  FROM materials_mini_milling m
        WHERE name LIKE CONCAT('%',:materials,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'materials' => $materials
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['material_id'], "price" => $row['price'],"stock" => $row['stock']);
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

    public function getStock($material_id)
    {
        $query = $this->prepare('SELECT stock FROM materials_mini_milling WHERE material_id = :material_id');
        $query->execute([
            'material_id' => $material_id
        ]);

        $stock = $query->fetch(PDO::FETCH_ASSOC);

        return $stock['stock'];
    }

    public function updateStock($material_id, $amount)
    {
        $query = $this->prepare('UPDATE materials_mini_milling SET stock = :stock WHERE material_id = :material_id');
        $query->execute([
            'stock' => $amount,
            'material_id' => $material_id
        ]);
    }
}