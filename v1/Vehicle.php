<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 15/07/20
 * Time: 07:24
 */

require_once "includes.php";

class Vehicle
{
    public  $db = null;
    public $log = null;

    public function __construct()
    {
        $this->db = new DB();
        $configs = parse_ini_file("config/config.ini", true);
        $configs = json_decode(json_encode($configs));
        $this->logger = new MenuLogger($configs->log);
    }

    /**
     * @param object $json
     * @return string
     * @throws Exception
     */

    public function addData($json)  {

        $fields = ["ref_number","date","registration_number","registration_date","chassis_number","customs_entry_number","type_of_vehicle","body_type","date_of_manufacture","body_colour","make","vehicle_model","number_of_axles","engine_number","fuel_type","rating","tare_weight","load_capacity","number_of_passengers","vehicle_under_caveat","conditions","drive_side","logbook_no","logbook_serial_no","created_by"];
        $params = [];

        $json->created_by = isset($json->created_by) ? $json->created_by : "Not Set";

        foreach ($fields as $v) {

            if(!isset($json->$v)) {

                throw new Exception("Missing $v field ",422);

            }

            if($v == "registration_number") {

                $vv = strtoupper(str_replace(" ","",$json->$v));

            } else {

                $vv = $json->$v;
            }

            $params[":$v"] = $vv;
        }

        $fields[] = "created";

        $sql_fields = implode(",",$fields);
        $sql_valus = implode(",",array_keys($params));

        $sql = "INSERT INTO pel_vehicle_check ($sql_fields) VALUES ($sql_valus,now())";

        $lastInsertID = $this->db->insert($sql,$params);

        // insert previous onwers

        foreach ($json->previous_owners as $v) {

            $id_number = $v->id_number;
            $pin = $v->pin;
            $name = $v->name;
            $email = $v->email;

            $sql = "INSERT IGNORE INTO vehicle_owners (id_number,pin,name,email,created) VALUE (:id_number,:pin,:name,:email,now())";
            $params = array(
                ':id_number' => $id_number,
                ':pin' => $pin,
                ':name' => $name,
                ':email' => $email
            );

            $this->db->insert($sql,$params);

            $sql = "INSERT INTO previous_owners (pel_vehicle_check_id,vehicle_owners_id,created) SELECT :pel_vehicle_check_id,id,now() FROM vehicle_owners WHERE id_number = :id_number ";

            $params = array(
                ':id_number' => $id_number,
                ':pel_vehicle_check_id' => $lastInsertID
            );

            $this->db->insert($sql,$params);

        }

        foreach ($json->current_owners as $v) {

            $id_number = $v->id_number;
            $pin = $v->pin;
            $name = $v->name;
            $email = $v->email;

            $sql = "INSERT IGNORE INTO vehicle_owners (id_number,pin,name,email,created) VALUE (:id_number,:pin,:name,:email,now())";
            $params = array(
                ':id_number' => $id_number,
                ':pin' => $pin,
                ':name' => $name,
                ':email' => $email
            );

            $this->db->insert($sql,$params);

            $sql = "INSERT INTO current_owners (pel_vehicle_check_id,vehicle_owners_id,created) SELECT :pel_vehicle_check_id,id,now() FROM vehicle_owners WHERE id_number = :id_number ";

            $params = array(
                ':id_number' => $id_number,
                ':pel_vehicle_check_id' => $lastInsertID
            );

            $this->db->insert($sql,$params);

        }

        return $lastInsertID;
    }

    public function getVehicle($registration_number)  {

        $sql = "SELECT * FROM pel_vehicle_check WHERE registration_number = :registration_number ";
        $params = [':registration_number' => $registration_number];

        $data = $this->db->fetchOne($sql,$params);

        if(!$data) {

            $data = new stdClass();
            $data->id = 0;
        }

        $id = $data->id;

        // get current owners
        $sql = "SELECT id_number,pin,name,email FROM vehicle_owners WHERE id IN (SELECT vehicle_owners_id FROM previous_owners WHERE pel_vehicle_check_id = :id ) ";
        $params = [':id'=>$id];
        $previous_owners = $this->db->fetch($sql,$params);

        $sql = "SELECT id_number,pin,name,email FROM vehicle_owners WHERE id IN (SELECT vehicle_owners_id FROM current_owners WHERE pel_vehicle_check_id = :id ) ";
        $current_owners = $this->db->fetch($sql,$params);

        $data->current_owners = $current_owners;
        $data->previous_owners = $previous_owners;

        return $data;
    }

    public function update($json)  {

        $fields = ["ref_number","date","registration_number","registration_date","chassis_number","customs_entry_number","type_of_vehicle","body_type","date_of_manufacture","body_colour","make","vehicle_model","number_of_axles","engine_number","fuel_type","rating","tare_weight","load_capacity","number_of_passengers","vehicle_under_caveat","condition","drive_side","logbook_no","logbook_serial_no"];
        $params = [];
        $updates = [];

        foreach ($fields as $v) {

            if(!isset($json->$v)) {

                throw new Exception("Missing $v field ",422);

            }

            if($v == "") {

                $vv = strtoupper(str_replace(" ","",$json->$v));

            } else {

                $vv = $json->$v;
            }

            $params[":$v"] = $vv;
            $updates[] = "$v = :$vv ";
        }

        $params[":id"] = $json->id;

        $sql_fields = implode(" , ",$updates);

        $sql = "UPDATE pel_vehicle_check SET $sql_fields WHERE id = :id ";

        $this->db->update($sql,$params);

        $lastInsertID = $json->id;

        // update previous onwers

        $sql = "DELETE FROM previous_owners WHERE pel_vehicle_check_id = :id ";
        $this->db->update($sql,[":id"=>$lastInsertID]);

        foreach ($json->previous_owners as $v) {

            $id_number = $v->id_number;
            $pin = $v->pin;
            $name = $v->name;
            $email = $v->email;

            $sql = "INSERT IGNORE INTO vehicle_owners (id_number,pin,name,email,created) VALUE (:id_number,:pin,:name,:email,now())";
            $params = array(
                ':id_number' => $id_number,
                ':pin' => $pin,
                ':name' => $name,
                ':email' => $email
            );

            $this->db->insert($sql,$params);

            $sql = "INSERT INTO previous_owners (pel_vehicle_check_id,vehicle_owners_id,created) SELECT :pel_vehicle_check_id,id,now() FROM vehicle_owners WHERE id_number = :id_number ";

            $params = array(
                ':id_number' => $id_number,
                ':pel_vehicle_check_id' => $lastInsertID
            );

            $this->db->insert($sql,$params);

        }

        $sql = "DELETE FROM current_owners WHERE pel_vehicle_check_id = :id ";
        $this->db->update($sql,[":id"=>$lastInsertID]);

        foreach ($json->current_owners as $v) {

            $id_number = $v->id_number;
            $pin = $v->pin;
            $name = $v->name;
            $email = $v->email;

            $sql = "INSERT IGNORE INTO vehicle_owners (id_number,pin,name,email,created) VALUE (:id_number,:pin,:name,:email,now())";
            $params = array(
                ':id_number' => $id_number,
                ':pin' => $pin,
                ':name' => $name,
                ':email' => $email
            );

            $this->db->insert($sql,$params);

            $sql = "INSERT INTO current_owners (pel_vehicle_check_id,vehicle_owners_id,created) SELECT :pel_vehicle_check_id,id,now() FROM vehicle_owners WHERE id_number = :id_number ";

            $params = array(
                ':id_number' => $id_number,
                ':pel_vehicle_check_id' => $lastInsertID
            );

            $this->db->insert($sql,$params);

        }

        return $lastInsertID;
    }

    public function removeData($id) {

        $sql = "DELETE FROM pel_vehicle_check WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function approve($id, $approved_by) {

        $sql = "UPDATE FROM pel_vehicle_check SET status = 1 AND approved_by = :approved_by WHERE id = :id";
        $params = [
            ':id' => $id,
            ':approved_by' => $approved_by
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function calculateTotalPages($total, $per_page) {

        $totalPages = (int)($total / $per_page);

        if (($total % $per_page) > 0) {

            $totalPages = $totalPages + 1;
        }

        return $totalPages;

    }

    public function getTable() {

        // get inputs
        $post = $this->input(null);

        $sort = isset($post['sort']) ? $post['sort']  : false;
        $page = isset($post['page']) ? $post['page'] :  1;
        $per_page = isset($post['per_page']) ? $post['per_page'] : 50;
        $filter = isset($post['filter']) ? $post['filter'] :  false;

        $status = isset($post['status']) ? $post['status'] :  -1;


        $andWhere = array();
        $orWhere = array();
        $params = array();
        $where = array();

        if($filter && strlen($filter) > 1 ) {

            $orWhere[] = "pel_vehicle_check.registration_number regexp :filter ";
            $params[':filter'] = $filter;
        }

        if($status && intval($status) > -1 ) {

            $orWhere[] = "pel_vehicle_check.status = :status ";
            $params[':status'] = $status;
        }

        $fields = array();

        $joins = array();

        $table = "pel_vehicle_check";
        $pk = "id";

        if(count($orWhere)) {

            $andWhere[] = "(".implode(" OR ",$orWhere).")";
        }

        if (count($andWhere) > 0) {

            $where = implode(" AND ",$andWhere);
        }
        else
        {
            $where = 1;
        }

        if (count($joins) > 0)
        {
            $join = implode(" ",$joins);
        }
        else
        {
            $join = '';
        }

        $fields_array = $fields;

        if (count($fields) > 0)
        {
            $fields = implode(",",$fields);
        }
        else
        {
            $fields = " * ";
        }

        if($sort)
        {
            list($sortByColumn,$sortBy) = explode('|',$sort);
            $orderBy = "ORDER BY $sortByColumn $sortBy";
        }
        else
        {
            $orderBy = "";
        }

        $groupBy[] = "$pk";

        if(count($groupBy) > 0){

            $group = "GROUP BY ".implode(",",$groupBy);
        }
        else{
            $group = "";
        }

        $countQuery = "SELECT COUNT(DISTINCT $pk) AS id FROM `$table` $join WHERE $where ";

        try
        {
            $total = $this->db->fetchOne($countQuery,$params);
        }
        catch (Exception $e)
        {
            $this->logger->ERROR(__FUNCTION__." Error: $countQuery " .$e->getMessage()." trace ".$e->getTraceAsString());
            return false;
        }

        $total = isset($total->id) ? $total->id : 0;

        $last_page = $this->calculateTotalPages($total,$per_page);

        $current_page = $page - 1;

        if ($current_page)
        {

            $offset = $per_page * $current_page;
        }
        else
        {
            $current_page = 0;
            $offset       = 0;
        }

        if ($offset > $total)
        {

            $offset = $total - ($current_page * $per_page);
        }

        $from = $offset + 1;

        $current_page++;

        $left_records = $total - ($current_page * $per_page);

        $sql = "SELECT $fields "
            . "FROM $table $join "
            . "WHERE $where "
            . "$group "
            . "$orderBy "
            . "LIMIT $offset,$per_page";

        $next_page_url = $left_records > 0 ? "student/table" : null;

        $prev_page_url = ($left_records + $per_page) < $total ? "student/table" : null;

        try {

            $transactions = $this->db->fetch($sql,$params);

        }
        catch (Exception $e) {

            $this->logger->ERROR(__FUNCTION__." SQL: $sql \n params ".json_encode($params)."\n Error " .$e->getMessage()." trace ".$e->getTraceAsString());
            return false;
        }

        if ($transactions) {

            $tableData['total']         = $total;
            $tableData['per_page']      = $per_page;
            $tableData['next_page_url'] = $next_page_url;
            $tableData['prev_page_url'] = $prev_page_url;
            $tableData['current_page']  = $current_page;
            $tableData['last_page']     = $last_page;
            $tableData['from']          = $from;
            $tableData['to']            = $offset + count($transactions);
            $tableData['data'] = $transactions;

            return $tableData;
        }
        else {

            $tableData['data'] = [];
            return $tableData;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function input($name = null ) {

        $post = $_POST;
        $get = $_GET;

        $json = file_get_contents('php://input');
        $json = json_decode($json);;


        if(is_null($name) || $name == "") {

            return array_merge($post,$get,(array)$json);
        }

        return isset($post[$name]) ? $post[$name] : isset($get[$name]) ? $get[$name] : isset($json->$name) ? $json->$name :false;

    }

}