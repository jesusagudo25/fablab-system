<?php

class Model
{
    public function __construct(){
        $this->db = new Database();
    }

    public function query($query)
    {
        return $this->db->connect()->query($query);
    }

    public function prepare($query)
    {
        return $this->db->connect()->prepare($query);
    }


}