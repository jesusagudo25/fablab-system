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
        // TODO: Implement getAll() method.
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

    public function getForDistrict(){
        $township = $this->prepare('SELECT * FROM townships WHERE district_id = :id');
        $township->execute([
            'id'=>$this->district_id
        ]);
        $townships = $township->fetchAll(PDO::FETCH_ASSOC);

        return $townships;
    }

    /**
     * @param mixed $district_id
     */
    public function setDistrictId($district_id): void
    {
        $this->district_id = $district_id;
    }


}