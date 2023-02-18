<?php

class Filament extends Model implements IModel
{

    private $filament_id;
    private $name;
    private $price;
    private $purchase_weight;
    private $current_weight;

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

    public function getFilamentId()
    {
        return $this->filament_id;
    }

    public function setFilamentId($filament_id)
    {
        $this->filament_id = $filament_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPurchaseWeight()
    {
        return $this->purchase_weight;
    }

    public function setPurchaseWeight($purchase_weight)
    {
        $this->purchase_weight = $purchase_weight;
    }

    public function getCurrentWeight()
    {
        return $this->current_weight;
    }

    public function setCurrentWeight($current_weight)
    {
        $this->current_weight = $current_weight;
    }

    public function getStock($id){
        $query = $this->prepare('SELECT current_weight FROM filaments WHERE filament_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $stock = $query->fetch(PDO::FETCH_ASSOC);

        return $stock['current_weight'];
    }

    public function updateStock($id, $current_weight){
        $query = $this->prepare('UPDATE filaments SET current_weight = :current_weight WHERE filament_id = :id');
        $query->execute([
            'id' => $id,
            'current_weight' => $current_weight
        ]);
    }
    
}