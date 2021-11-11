<?php

class Consumable extends Model implements IModel
{

    public function save(...$args)
    {
        // TODO: Implement save() method.
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM consumables');

        $areas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $areas;
    }

    public function get($id)
    {
        // TODO: Implement get() method.
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