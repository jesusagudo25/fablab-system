<?php

class Observation extends Model implements IModel
{
    private $observation_id;
    private $user_id;
    private $description;
    private $date;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }


    public function save(...$args)
    {
        // TODO: Implement save() method.
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM observations');
        $observations = $query->fetchAll(PDO::FETCH_ASSOC);

        return $observations;
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