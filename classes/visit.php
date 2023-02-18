<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Visit extends Model implements IModel
{
    private $visit_id;
    private $customer_id;
    private $reason_id;
    private $date;
    private $observation;
    private $status;
    private $isAttended;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAll($decoded)
    {
        $filename = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;
        $newCustomer = isset($decoded['newCustomer']) ? json_decode($decoded['newCustomer'], true) : '';
        $customer_id = isset($decoded['id_cliente']) ? $decoded['id_cliente'] : '';

        $customerVisit = new CustomerVisit();
        $customer = new Customer();
        $customers = [];

        //Visitante captura
        if (!empty($filename)) {

            $province = new Province();
            $district = new District();
            $township = new Township();

            $allowedExts = array("xls", "xlsx");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if (in_array($extension, $allowedExts)) {
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

                if (count($sheetData) > 1) {
                    for ($i = 1; $i < count($sheetData); $i++) {

                        //Validar si el cliente existe en la base de datos
                        if (!$customer->verifyCustomer($sheetData[$i][1])) {
                            //Validar los campos obligatorios
                            $errors = [];
                            if (empty($sheetData[$i][0])) {
                                $errors['documentType'] = 'El tipo de documento es obligatorio';
                            }

                            if (empty($sheetData[$i][1])) {
                                $errors['document'] = 'El documento es obligatorio';
                            }

                            if (empty($sheetData[$i][2])) {
                                $errors['name'] = 'El nombre es obligatorio';
                            }

                            if (empty($sheetData[$i][3])) {
                                $errors['sexo'] = 'El sexo es obligatorio';
                            }

                            if (empty($sheetData[$i][4])) {
                                $errors['ageRange'] = 'La edad es obligatoria';
                            }

                            if (empty($sheetData[$i][7])) {
                                $errors['province'] = 'La provincia es obligatoria';
                            }
                            else if(!$province->verifyProvince($sheetData[$i][7])){
                                $errors['province'] = 'La provincia no existe';
                            }

                            if (empty($sheetData[$i][8])) {
                                $errors['city'] = 'La ciudad es obligatorio';
                            }
                            elseif(!$district->verifyDistrict($sheetData[$i][8])){
                                $errors['city'] = 'La ciudad no existe';
                            }

                            if (empty($sheetData[$i][9])) {
                                $errors['township'] = 'El barrio es obligatorio';
                            }
                            elseif(!$township->verifyTownship($sheetData[$i][9])){
                                $errors['township'] = 'El barrio no existe';
                            }

                            if (empty($errors)) {
                                //Crear el cliente si no existe
                                $customer->setDocumentType($sheetData[$i][0]); //C
                                $customer->setDocument($sheetData[$i][1]); //Document
                                $customer->setName($sheetData[$i][2]); //Name
                                $customer->setSexo($sheetData[$i][3]); //F O M
                                if ($sheetData[$i][4] <= 18) {
                                    $customer->setAgeRange('1'); //1 - 18
                                } else if ($sheetData[$i][4] > 18 && $sheetData[$i][4] <= 26) {
                                    $customer->setAgeRange('2'); //19 - 26
                                } else if ($sheetData[$i][4] > 26 && $sheetData[$i][4] <= 35) {
                                    $customer->setAgeRange('3'); //26 - 35
                                } else {
                                    $customer->setAgeRange('4'); //36 +
                                }
                                $customer->setTelephone($sheetData[$i][5]);
                                $customer->setEmail($sheetData[$i][6]);

                                $resul = $province->getProvinceForName($sheetData[$i][7]);
                                $customer->setProvince(9);

                                $resul = $district->getDistrictForName($sheetData[$i][8]);
                                $customer->setCity($resul['district_id']);

                                $resul = $township->getTownshipForName($sheetData[$i][9]);
                                $customer->setTownship($resul['township_id']);

                                $customer->save();
                                $customers[] = $customer->getLastID();
                            }
                        }
                        else{
                            $customers[] = $customer->getCustomerID($sheetData[$i][1]);
                        }

                        //Asignar el cliente al visitante
                    }
                }
            } else {
                $filename = '';
            }
        } else {
            if (!empty($newCustomer)) {
                $customer->setDocumentType($newCustomer['tipo_documento']);
                $customer->setDocument($newCustomer['documento']);
                $customer->setName($newCustomer['nombre']);
                $customer->setEmail(empty($newCustomer['email']) ? NULL : $newCustomer['email']);
                $customer->setTelephone(empty($newCustomer['telefono']) ? NULL : $newCustomer['telefono']);
                $customer->setAgeRange($newCustomer['edad']);
                $customer->setSexo($newCustomer['sexo']);
                $customer->setProvince($newCustomer['provincia']);
                $customer->setCity($newCustomer['distrito']);
                $customer->setTownship($newCustomer['corregimiento']);
                $customer->save();
                $customers[] = $customer->getLastID();
            } else {
                $customers[] = $customer_id;
            }
        }

        $this->reason_id = empty($decoded['id_razonVisita']) ? '' : $decoded['id_razonVisita'];
        $areasChecked = empty(json_decode($decoded['areas'], true)) ? NULL : json_decode($decoded['areas'], true);
        $this->observation = empty($decoded['observacion']) ? NULL : $decoded['observacion'];

        $this->save();
        $this->getLastID();

        $customerVisit->setVisitId($this->getLastID());
        $customerVisit->save($customers);

         if (!empty($decoded['booking_id'])) {

            //Se borran las areas de la reserva
            if (!empty($areasChecked)) {
                $booking_areas = new BookingArea();
                $booking_areas->delete($decoded['booking_id']);
            }

            //Se borra la reserva <- se puede evitar con cascade
            $booking = new Booking();
            $booking->delete($decoded['booking_id']);
        }

        if (!empty($areasChecked)) {
            $visits_areas = new VisitArea();
            return $visits_areas->save($this->getLastID(), $areasChecked);
        }

        //Se deben delegar responsabilidades a las clases implicadas -> crear metodos para accion
    }

    public function save(...$args) //Save solo table_visit
    {
        $nuevaVisita = $this->prepare('INSERT INTO visits(reason_id, observation) VALUES (:razonvisita ,:observacion)');

        $nuevaVisita->execute([
            'razonvisita' => $this->reason_id,
            'observacion' => $this->observation
        ]);
    }

    public function getAll()
    {
        $query = $this->query('SELECT v.visit_id, c.name AS customer_id, r.name AS reason_id,r.time ,v.date, CONCAT(SUBSTRING(v.observation ,1,20),"...") as observation, v.status FROM visits v
        INNER JOIN customers c ON c.customer_id = v.customer_id
        INNER JOIN reason_visits r ON r.reason_id = v.reason_id');

        $visits = $query->fetchAll(PDO::FETCH_ASSOC);

        return $visits;
    }

    public function getVisitsForMachine($start_date, $end_date)
    {

        $query = $this->prepare("SELECT COUNT(*) AS total FROM visits v 
        INNER JOIN reason_visits r ON v.reason_id = r.reason_id 
        WHERE (v.date BETWEEN :start_date AND :end_date)
        AND (r.time = 1) AND (v.status = 1)");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $visits = $query->fetch(PDO::FETCH_ASSOC);

        return $visits['total'];
    }

    public function getVisitsForService($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COUNT(*) AS total FROM visits v 
        INNER JOIN reason_visits r ON v.reason_id = r.reason_id 
        WHERE (v.date BETWEEN :start_date AND :end_date)                                       
        AND (r.time = 0) AND (v.status = 1)");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $visitasFree = $query->fetch(PDO::FETCH_ASSOC);

        return $visitasFree['total'];
    }

    public function getTimeDifAreas($start_date, $end_date)
    {

        $query = $this->prepare("SELECT a.name, COALESCE(x.diferencia,0) AS diferencia FROM
        (
            SELECT SUM(HOUR(TIMEDIFF(v.departure_time,v.arrival_time))) AS diferencia, a.area_id FROM visits_areas v 
            INNER JOIN visits s ON v.visit_id = s.visit_id 
            RIGHT JOIN areas a ON v.area_id = a.area_id
            WHERE (s.date BETWEEN :start_date AND :end_date)
            AND (s.status = 1)
            GROUP BY a.area_id
        ) as x
        RIGHT JOIN areas a ON x.area_id = a.area_id
        GROUP BY a.area_id;");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $areastime = $query->fetchAll(PDO::FETCH_ASSOC);

        return $areastime;
    }

    public function getTimeDifRazones($start_date, $end_date)
    {

        $query = $this->prepare("SELECT r.name, COALESCE(x.diferencia,0) AS diferencia, r.time FROM
        (
            SELECT SUM(HOUR(TIMEDIFF(va.departure_time,va.arrival_time))) AS diferencia, r.reason_id FROM visits v
            INNER JOIN visits_areas va ON v.visit_id = va.visit_id
            RIGHT JOIN reason_visits r ON v.reason_id = r.reason_id
            WHERE (v.date BETWEEN :start_date AND :end_date)
            AND (v.status = 1)
            AND (r.name != 'Servicio')
            GROUP BY r.reason_id
        ) as x
        RIGHT JOIN reason_visits r ON x.reason_id = r.reason_id
        GROUP BY r.reason_id;");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $razonestime = $query->fetchAll(PDO::FETCH_ASSOC);

        return $razonestime;
    }

    public function getTimeDifTotal($start_date, $end_date)
    {
        $query = $this->prepare("SELECT COALESCE(SUM(HOUR(TIMEDIFF(v.departure_time,v.arrival_time))),0) AS total FROM visits_areas v 
        INNER JOIN visits s ON v.visit_id = s.visit_id
        WHERE (s.date BETWEEN :start_date AND :end_date)
        AND (s.status = 1)");

        $query->execute([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $areastimeTotal = $query->fetch(PDO::FETCH_ASSOC);

        return $areastimeTotal['total'];
    }


    public function getLastID()
    {
        $query = $this->query('SELECT MAX(visit_id) AS last_id FROM visits');
        $last_id = $query->fetch();
        return $last_id['last_id'];
    }

    public function get($id)
    {
        $query = $this->prepare('SELECT v.visit_id, rv.reason_id, rv.time, rv.name AS reason, v.date, v.observation, v.status, c.name, c.customer_id, c.document, c.document_type FROM visits v 
        INNER JOIN reason_visits rv ON rv.reason_id = v.reason_id
        INNER JOIN customer_visit cv ON cv.visit_id = v.visit_id
        INNER JOIN customers c ON c.customer_id = cv.customer_id
        WHERE v.visit_id = :id');
        $query->execute([
            'id' => $id
        ]);

        $visit = $query->fetch(PDO::FETCH_ASSOC);

        return $visit;
    }

    public function delete($id)
    {
        $actualizarDatos = $this->prepare("UPDATE visits SET status = :status WHERE visit_id = :id;");
        $actualizarDatos->execute([
            'status' => $this->status,
            'id' => $id
        ]);
    }

    public function update()
    {
        $query = $this->prepare('UPDATE visits SET customer_id = :customer_id,reason_id = :reason_id,date = :date,observation = :observation WHERE (visit_id = :visit_id)');

        $query->execute([
            'customer_id' => $this->customer_id,
            'reason_id' => $this->reason_id,
            'date' => $this->date,
            'observation' => $this->observation,
            'visit_id' => $this->visit_id
        ]);
    }

    public function setAttended($isAttended, $id){
        $query = $this->prepare('UPDATE visits SET isAttended = :isAttended WHERE (visit_id = :visit_id)');

        $query->execute([
            'isAttended' => $isAttended,
            'visit_id' => $id
        ]);
    }

    /**
     * @param mixed $customer_id
     */
    public function setCustomerId($customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    /**
     * @param mixed $reason_id
     */
    public function setReasonId($reason_id): void
    {
        $this->reason_id = $reason_id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $observation
     */
    public function setObservation($observation): void
    {
        $this->observation = $observation;
    }

    /**
     * @param mixed $visit_id
     */
    public function setVisitId($visit_id): void
    {
        $this->visit_id = $visit_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
