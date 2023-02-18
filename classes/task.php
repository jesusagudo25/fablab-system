<?php

class Task extends Model implements IModel
{

    private $task_id;
    private $customer_id;
    private $name;
    private $description;
    private $date_delivery;
    private $status;


    public function save(...$args){
        $query = $this->prepare('INSERT INTO tasks(customer_id, name,description,date_delivery) VALUES (:customer_id, :name ,:description, :date_delivery)');

        $query->execute([
            'customer_id'=>$this->customer_id,
            'name'=>$this->name,
            'description'=> $this->description,
            'date_delivery'=> $this->date_delivery
        ]);
    }

    public function getAll()
    {

    }

    public function get($id)
    {
        
    }

    public function getAjax($component){

    }

    public function deleteSave(...$args){

    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        
    }

    public function interruptor($status, $task_id){
        $query = $this->prepare('UPDATE tasks SET status = :status WHERE task_id = :task_id');
        $query->execute([
            'status'=>$status,
            'task_id'=>$task_id
        ]);
    }

    public function getTaskId()
    {
        return $this->task_id;
    }

    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setDateDelivery($date_delivery)
    {
        $this->date_delivery = $date_delivery;
    }

    public function getDateDelivery()
    {
        return $this->date_delivery;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}