<?php

class Software extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM softwares');

        $softwares = $query->fetchAll(PDO::FETCH_ASSOC);

        return $softwares;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM softwares WHERE software_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
    }

    public function getAjax($software){

        $query = $this->query("SELECT *  FROM softwares WHERE status = 1");

        $softwares = $query->fetchAll(PDO::FETCH_ASSOC);

        return $softwares;
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
}