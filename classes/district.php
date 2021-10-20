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
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        //
    }

    public function getForProvince(){
        $province = $this->prepare('SELECT * FROM districts WHERE province_id = :id');
        $province->execute([
            'id'=>$this->province_id
        ]);
        $provinces = $province->fetchAll(PDO::FETCH_ASSOC);

        return $provinces;
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