<?php

class UseSaleMaterialLaser extends Model implements IModel
{
    
    private $use_id;
    private $material_id;
    private $width;
    private $height;
    private $amount;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_materials_laser(use_id,material_id, width, height, amount) VALUES (:use_id,:material_id,:width, :height, :amount)');

        $query->execute([
            'use_id' => $this->use_id,
            'material_id' => $this->material_id,
            'width' => $this->width,
            'height' => $this->height,
            'amount' => $this->amount
        ]);
    }

    public function getAll()
    {

    }

    public function get($id)
    {
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

    public function getUseId()
    {
        return $this->use_id;
    }

    public function setUseId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function getMaterialId()
    {
        return $this->material_id;
    }

    public function setMaterialId($material_id)
    {
        $this->material_id = $material_id;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    
}