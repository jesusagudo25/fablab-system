<?php

class UseSaleThread extends Model implements IModel
{

    private $use_id;
    private $thread_id;
    private $number_stitches;

    public function save(...$args)
    {
        
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

    public function getThreadId()
    {
        return $this->thread_id;
    }

    public function setThreadId($thread_id)
    {
        $this->thread_id = $thread_id;
    }

    public function getNumberStitches()
    {
        return $this->number_stitches;
    }

    public function setNumberStitches($number_stitches)
    {
        $this->number_stitches = $number_stitches;
    }

    
}