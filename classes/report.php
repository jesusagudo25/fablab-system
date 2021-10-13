<?php

class Report extends Model implements IModel
{

     private $report_id;
     private $name;
     private $user_id;
     private $start_date;
     private $end_date;
     private $document;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {
        $this->name= $args[0];
        $this->user_id = $args[1];
        $this->document = $args[2];

        $nuevoReporte = $this->prepare("INSERT INTO reports(month, user_id,start_date,end_date, document) VALUES (:month, :user_id,CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE),CURDATE(),:document)");

        $nuevoReporte->execute([
            'month'=>$this->name,
            'user_id'=> $this->user_id,
            'document'=>$this->document
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT r.month AS Mes, CONCAT(u.name," ",u.lastname) AS Autor ,r.start_date AS "Fecha de inicio" ,r.end_date AS "Fecha final", r.report_id AS Acciones, r.document AS documento FROM reports r
INNER JOIN users u ON r.user_id = u.user_id ');
        $reports = $query->fetchAll(PDO::FETCH_ASSOC);

        return $reports;
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function delete($id)
    {
        $deleteReport = $this->prepare('DELETE FROM reports WHERE report_id = :report_id');

        $deleteReport->execute([
            'report_id' => $id
        ]);
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

}