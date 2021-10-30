<?php

class Observation extends Model implements IModel
{
    private $observation_id;
    private $user_id;
    private $description;
    private $date;

    public function __construct()
    {
        parent::__construct();
    }


    public function save(...$args)
    {
        $nuevaObservacion = $this->prepare("INSERT INTO observations(user_id,description,date) VALUES (:user_id,:description,:date)");
        $nuevaObservacion->execute([
            'user_id'=>$this->user_id,
            'description'=> $this->description,
            'date'=>$this->date
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT CONCAT(u.name," ",u.lastname) AS Autor, CONCAT(SUBSTRING(o.description,1,40),"...") AS Descripción, o.date AS Fecha, o.observation_id AS Acciones FROM observations o
INNER JOIN users u ON o.user_id = u.user_id');
        $observations = $query->fetchAll(PDO::FETCH_ASSOC);

        return $observations;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT description, date FROM observations WHERE observation_id = :id');
        $query->execute([
            'id' => $id
        ]);

                $observation = $query->fetch();
        
                return $observation;
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("DELETE FROM observations WHERE observation_id = :id;");
        $actualizarDatos->execute([
            'id'=>$id
        ]);
    }

    public function update()
    {
        $actualizarDatos = $this->prepare("UPDATE observations SET description = :description, date = :date WHERE observation_id = :id;");
        $actualizarDatos->execute([
            'description'=> $this->description,
            'date'=>$this->date,
            'id'=>$this->observation_id

        ]);
    }

    public function getObsMonth($start_date,$end_date){

        $query = $this->prepare("SELECT CONCAT(u.name,' ',u.lastname) AS name,o.description, o.date  FROM observations o 
INNER JOIN users u ON o.user_id = u.user_id 
WHERE (o.date BETWEEN :start_date AND :end_date);");

        $query->execute([
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]);

        $observations = $query->fetchAll(PDO::FETCH_ASSOC);

        return $observations;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $observation_id
     */
    public function setObservationId($observation_id): void
    {
        $this->observation_id = $observation_id;
    }





}