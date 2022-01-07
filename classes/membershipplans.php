<?php

class MembershipPlans extends Model implements IModel
{

    private $membership_id;
    private $name;
    private $price;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevoPlan = $this->prepare('INSERT INTO membership_plans(name,price) VALUES (:name,:price)');

        $nuevoPlan->execute([
            'name' => $this->name,
            'price' => $this->price
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT membership_id AS id,name,price,status FROM membership_plans');
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);

        return $plans;
    }

    public function getAjax()
    {
        $query = $this->query('SELECT membership_id AS id,name,price,status FROM membership_plans WHERE status = 1');
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);

        return $plans;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM membership_plans WHERE membership_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $category = $query->fetch(PDO::FETCH_ASSOC);

        return $category;
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE membership_plans SET status = :status WHERE membership_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE membership_plans SET name = :name, price = :price WHERE membership_id = :id;");
        $actualizarDatos->execute([
            'name' => $this->name,
            'price' => $this->price,
            'id'=>$this->membership_id
        ]);
    }

        /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

        /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

        /**
     * @param mixed $membership_id
     */
    public function setMembershipID($membership_id): void
    {
        $this->membership_id = $membership_id;
    }
}