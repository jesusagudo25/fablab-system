<?php

class Thread extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM threads');

        $components = $query->fetchAll(PDO::FETCH_ASSOC);

        return $components;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM threads WHERE thread_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $thread = $query->fetch(PDO::FETCH_ASSOC);

        return $thread;
    }

    public function getAjax($thread){

        $query = $this->prepare("SELECT *  FROM threads c
        WHERE name LIKE CONCAT('%',:thread,'%') AND status = 1 LIMIT 3");
        $query->execute([
            'thread' => $thread
        ]);

        $datos = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = array("label" => $row['name'], "id" => $row['thread_id'],"purchased_amount" => $row['purchased_amount'],"current_amount" => $row['current_amount']);
        }

        return $datos;
    }

    public function deleteSave(...$args){

    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function getStock($id){
        $query = $this->prepare('SELECT current_amount FROM threads WHERE thread_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $stock = $query->fetch(PDO::FETCH_ASSOC);

        return $stock['current_amount'];
    }

    public function updateStock($id, $current_amount){
        $query = $this->prepare('UPDATE threads SET current_amount = :current_amount WHERE thread_id = :id');
        $query->execute([
            'id' => $id,
            'current_amount' => $current_amount
        ]);
    }
}