<?php

class UseSaleVinilo extends Model implements IModel
{

    private $use_id;
    private $vinilo_id;
    private $width;
    private $height;

    public function save(...$args)
    {
        $query = $this->prepare('INSERT INTO use_sale_vinilos(use_id,vinilo_id, width, height) VALUES (:use_id,:vinilo_id,:width, :height)');

        $query->execute([
            'use_id' => $this->use_id,
            'vinilo_id' => $this->vinilo_id,
            'width' => $this->width,
            'height' => $this->height
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

    public function getViniloId()
    {
        return $this->vinilo_id;
    }

    public function setViniloId($vinilo_id)
    {
        $this->vinilo_id = $vinilo_id;
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
}