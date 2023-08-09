<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 15/07/20
 * Time: 07:24
 */

require_once "includes.php";

class Faculty
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

    public function addData($json) {

        $faculty_name = isset($json->faculty_name) ? $json->faculty_name : "";
        $faculty_code = isset($json->faculty_code) ? $json->faculty_code : "";
        $added_by = isset($json->added_by) ? $json->added_by : "";
        $verified_by = $added_by;

        $sql = "INSERT INTO pel_edu_faculty (faculty_name,faculty_code,added_date,verified_date,added_by,verified_by) VALUES "
            . "(:faculty_name,:faculty_code,now(),now(),:added_by,:verified_by)";

        $params = [
            ':faculty_name' => $faculty_name,
            ':faculty_code' => $faculty_code,
            ':verified_by' => $verified_by,
            ':added_by' => $added_by
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;
    }

    public function updateData($json) {

        $faculty_id = isset($json->faculty_id) ? $json->faculty_id : "";
        $faculty_name = isset($json->faculty_name) ? $json->faculty_name : "";
        $faculty_code = isset($json->faculty_code) ? $json->faculty_code : "";
        $added_by = isset($json->added_by) ? $json->added_by : "";
        $verified_by = $added_by;

        $sql = "UPDATE pel_edu_faculty SET faculty_name = :faculty_name,faculty_code = :faculty_code, verified_by = :verified_by WHERE faculty_id = :faculty_id ";
        $params = [
            ':faculty_id' => $faculty_id,
            ':faculty_name' => $faculty_name,
            ':faculty_code' => $faculty_code,
            ':verified_by' => $verified_by,
        ];

        $lastInsertID = $this->db->update($sql,$params);

        return $lastInsertID;
    }

    public function removeData($id) {

        $sql = "DELETE FROM pel_edu_faculty WHERE faculty_id = :id";
        $params = [
            ':id' => $id
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


        $andWhere = array();
        $orWhere = array();
        $params = array();
        $where = array();

        if($filter && strlen($filter) > 1 ) {

            $orWhere[] = "pel_edu_faculty.faculty_name regexp :filter ";
            $params[':filter'] = $filter;
        }

        $fields = array();

        $joins = array();

        $table = "pel_edu_faculty";
        $pk = "faculty_id";

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