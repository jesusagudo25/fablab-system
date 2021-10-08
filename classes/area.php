<?php


class Area extends Model implements IModel
{
    private $reason_id;
    private $name;
    private $status;

    /**
     * Area constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getReasonId()
    {
        return $this->reason_id;
    }

    /**
     * @param mixed $reason_id
     */
    public function setReasonId($reason_id): void
    {
        $this->reason_id = $reason_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getAll(){

        $query = $this->query('SELECT * FROM areas');

        $areas = $query->fetchAll();

        return $areas;
    }


    public function save(...$args)
    {
        // TODO: Implement save() method.
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