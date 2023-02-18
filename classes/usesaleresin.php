<?php

class UseSaleResin extends Model implements IModel
{
    private $use_id;
    private $resin_id;
    private $number_grams;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_resin(use_id,resin_id, number_grams) VALUES (:use_id,:resin_id,:number_grams)');

        $query->execute([
            'use_id' => $this->use_id,
            'resin_id' => $this->resin_id,
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

    public function setUseId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function setResinId($resin_id)
    {
        $this->resin_id = $resin_id;
    }

    public function setNumberGrams($number_grams)
    {
        $this->number_grams = $number_grams;
    }
}