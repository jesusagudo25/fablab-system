<?php

class CategoryComponent extends Model implements IModel
{

    public function save(...$args)
    {
        
    }

    public function getAll()
    {
        $query = $this->query('SELECT * FROM categories_components');

        $categories_components = $query->fetchAll(PDO::FETCH_ASSOC);

        return $categories_components;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM categories_components WHERE category_component = :id');
        $query->execute([
            'id' => $id
        ]);

        $component = $query->fetchAll(PDO::FETCH_ASSOC);

        return $component;
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
}