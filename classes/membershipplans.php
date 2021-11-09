<?php

class MembershipPlans extends Model implements IModel
{


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
        $query = $this->query('SELECT membership_id AS id,name,price,status FROM membership_plans');
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);

        return $plans;
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