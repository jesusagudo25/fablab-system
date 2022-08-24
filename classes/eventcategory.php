<?php

class EventCategory extends Model implements IModel
{

    private $category_id;
    private $name;
    private $price;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $nuevaCategoria = $this->prepare('INSERT INTO event_category(name,price) VALUES (:name,:price)');

        $nuevaCategoria->execute([
            'name' => $this->name,
            'price' => $this->price
        ]);
    }

    public function getAjax()
    {
        $query = $this->query('SELECT category_id AS id, name, status FROM event_category WHERE status = 1');
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }

    public function getAll()
    {
        $query = $this->query('SELECT category_id AS id, name,price, status FROM event_category');
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM event_category WHERE category_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $category = $query->fetch(PDO::FETCH_ASSOC);

        return $category;
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE event_category SET status = :status WHERE category_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE event_category SET name = :name, price = :price WHERE category_id = :id;");
        $actualizarDatos->execute([
            'name' => $this->name,
            'price' => $this->price,
            'id'=>$this->category_id
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
     * @param mixed $category_id
     */
    public function setCategoryID($category_id): void
    {
        $this->category_id = $category_id;
    }
}