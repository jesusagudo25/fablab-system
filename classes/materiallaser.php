<?php

class MaterialLaser extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM materials_laser');

        $material = $query->fetchAll(PDO::FETCH_ASSOC);

        return $material;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM materials_laser WHERE material_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($materials){

        $query = $this->prepare("SELECT *  FROM materials_laser m
        WHERE name LIKE CONCAT('%',:materials,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'materials' => $materials
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['material_id'], "price" => $row['price'],"width" => $row['width'],"height" => $row['height'],"area" => $row['area']);
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
        $query = $this->prepare('SELECT area FROM materials_laser WHERE material_id = :material_id');
        $query->execute([
            'material_id' => $material_id
        ]);

        $stock = $query->fetch(PDO::FETCH_ASSOC);

        return $stock['area'];
    }

    public function updateStock($material_id, $area)
    {
        $query = $this->prepare('UPDATE materials_laser SET area = :area WHERE material_id = :material_id');
        $query->execute([
            'area' => $area,
            'material_id' => $material_id
        ]);
    }
}