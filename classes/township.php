<?php

class Township extends Model implements IModel
{
    private $township_id;
    private $district_id;
    private $name;

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
        $query = $this->query('SELECT * FROM townships');
        $townships = $query->fetchAll(PDO::FETCH_ASSOC);

        return $townships;
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

    /**
     * @param mixed $district_id
     */
    public function setDistrictId($district_id): void
    {
        $this->district_id = $district_id;
    }

    public function getTownshipForName($name)
    {
        $query = $this->prepare('SELECT * FROM townships WHERE name = :name');
        $query->execute([':name' => $name]);
        $township = $query->fetch(PDO::FETCH_ASSOC);
        return $township;
    }

    public function verifyTownship($name)
    {
        $query = $this->prepare('SELECT * FROM townships WHERE name = :name');
        $query->execute([':name' => $name]);
        $township = $query->fetch(PDO::FETCH_ASSOC);
        if($township) {
            return true;
        } else {
            return false;
        }
    }
}