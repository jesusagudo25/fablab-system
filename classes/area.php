<?php


class Area extends Model implements IModel
{
    private $area_id;
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
    public function getAreaId()
    {
        return $this->area_id;
    }

    /**
     * @param mixed $area_id
     */
    public function setAreaId($area_id): void
    {
        $this->area_id = $area_id;
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

        $query = $this->query('SELECT area_id AS id, name,status FROM areas');

        $areas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $areas;
    }

    public function getAjax(){

        $query = $this->query('SELECT area_id AS id, name,status FROM areas WHERE status = 1 ORDER BY name ASC');

        $areas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $areas;
    }

    public function save(...$args)
    {
        $nuevaArea = $this->prepare('INSERT INTO areas(name) VALUES (:name)');

        $nuevaArea->execute([
            'name' => $this->name,
        ]);
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM areas WHERE area_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $area = $query->fetch(PDO::FETCH_ASSOC);

        return $area;
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE areas SET status = :status WHERE area_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE areas SET name = :name WHERE area_id = :id;");
        $actualizarDatos->execute([
            'name' => $this->name,
            'id'=>$this->area_id
        ]);
    }

    public function getLastID(){
        $consultarIDArea = $this->query('SELECT area_id FROM areas ORDER BY area_id DESC LIMIT 1');
        $areaResultado = $consultarIDArea->fetch();
        $this->area_id = $areaResultado['area_id'];
    }
}