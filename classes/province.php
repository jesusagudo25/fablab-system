<?php

class Province extends Model implements IModel
{

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
        $province = $this->query('SELECT * FROM provinces');
        $provinces = $province->fetchAll(PDO::FETCH_ASSOC);

        return $provinces;
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

    public function getProvinceForName($name)
    {
        $query = $this->prepare('SELECT * FROM provinces WHERE name = :name');
        $query->execute([':name' => $name]);
        $province = $query->fetch(PDO::FETCH_ASSOC);
        return $province;
    }

    public function verifyProvince($name)
    {
        $query = $this->prepare('SELECT * FROM provinces WHERE name = :name');
        $query->execute([':name' => $name]);
        $province = $query->fetch(PDO::FETCH_ASSOC);
        if ($province) {
            return true;
        } else {
            return false;
        }
    }
}