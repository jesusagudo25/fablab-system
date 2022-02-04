<?php

class Report extends Model implements IModel
{

     private $report_id;
     private $month;
     private $user_id;
     private $start_date;
     private $end_date;

    public function __construct()
    {
        parent::__construct();
    }

    public function save(...$args)
    {

        $nuevoReporte = $this->prepare("INSERT INTO reports(month, user_id,start_date,end_date) VALUES (:month, :user_id,:start_date,:end_date)");

        $nuevoReporte->execute([
            'month'=> $this->month,
            'user_id'=> $this->user_id,
            'start_date'=> $this->start_date,
            'end_date'=> $this->end_date,
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT r.report_id, r.month, CONCAT(u.name," ",u.lastname) AS autor ,r.start_date ,r.end_date FROM reports r
INNER JOIN users u ON r.user_id = u.user_id ');
        $reports = $query->fetchAll(PDO::FETCH_ASSOC);

        return $reports;
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT * FROM reports WHERE report_id = :report_id');

        $query->execute([
            'report_id' => $id
        ]);

        $report = $query->fetch();

        $this->month = $report['month'];
        $this->user_id = $report['user_id'];
        $this->start_date = $report['start_date'];
        $this->end_date = $report['end_date'];
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

    public function getLastID(){
        $query = $this->query('SELECT report_id FROM reports ORDER BY report_id DESC LIMIT 1');
        $reporte = $query->fetch();

        $this->report_id = $reporte['report_id'];
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date): void
    {
        $this->start_date = $start_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date): void
    {
        $this->end_date = $end_date;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @return mixed
     */
    public function getReportId()
    {
        return $this->report_id;
    }






}