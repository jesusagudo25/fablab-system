<?php

class UseSaleComponent extends Model implements IModel
{
    private $use_id;
    private $component_id;
    private $number_components;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_components(use_id,component_id,number_components) VALUES (:use_id,:component_id,:number_components)');

        $query->execute([
            'use_id' => $this->use_id,
            'component_id' => $this->component_id,
            'number_components' => $this->number_components
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

    public function getUseId()
    {
        return $this->use_id;
    }

    public function setComponentId($component_id)
    {
        $this->component_id = $component_id;
    }

    public function getComponentId()
    {
        return $this->component_id;
    }

    public function setNumberComponents($number_components)
    {
        $this->number_components = $number_components;
    }

    public function getNumberComponents()
    {
        return $this->number_components;
    }
    
}