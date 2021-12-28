<?php

class District extends Model implements IModel
{

    private $district_id;
    private $province_id;
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
        $query = $this->query('SELECT * FROM districts');
        $provinces = $query->fetchAll(PDO::FETCH_ASSOC);

        return $provinces;
    }

    public function get($id)
    {
        //
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
     * @param mixed $province_id
     */
    public function setProvinceId($province_id): void
    {
        $this->province_id = $province_id;
    }


}