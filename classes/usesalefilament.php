<?php

class UseSaleFilament extends Model implements IModel
{

    private $use_id;
    private $filament_id;
    private $number_grams;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_filament(use_id,filament_id, number_grams) VALUES (:use_id,:filament_id,:number_grams)');

        $query->execute([
            'use_id' => $this->use_id,
            'filament_id' => $this->filament_id,
            'number_grams' => $this->number_grams
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

    public function getFilamentId()
    {
        return $this->filament_id;
    }

    public function setFilamentId($filament_id)
    {
        $this->filament_id = $filament_id;
    }

    public function getNumberGrams()
    {
        return $this->number_grams;
    }

    public function setNumberGrams($number_grams)
    {
        $this->number_grams = $number_grams;
    }

    
}