<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 15/07/20
 * Time: 07:24
 */

require_once "includes.php";

class Education
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

    public function token(){

        $chars = "ABCDEFGHKMNPQRSTUVWXYZ23456789";
        $length = 8;
        return "FT".substr(str_shuffle(strtoupper($chars)), 0, $length);
    }

    public function getInstitutions() {

        $sql = "select inst_id,inst_name,inst_code from pel_edu_institution ";

        $dt = $this->db->fetch($sql);

        return $dt;
    }

    public function getFaculty() {

        $sql = "select * FROM pel_edu_faculty ORDER BY faculty_name ASC ";
        //$sql = "select inst_id,inst_name from pel_edu_institution ";

        $dt = $this->db->fetch($sql);

       // error_log(__FUNCTION__." GOT PAYLOAD ".json_encode($dt));

        return $dt;
    }

    public function getCourse($faculty_name) {

        $sql = "SELECT course_id,course_name,course_code,faculty_name FROM pel_edu_courses WHERE faculty_name = :faculty_name ORDER BY course_name ASC ";

        $this->logger->INFO("GOT sql $sql :faculty_name $faculty_name ");

        $dt = $this->db->fetch($sql,[':faculty_name'=>$faculty_name]);

        return $dt;
    }

    public function getCourseSpecialization($course_id,$course_name) {

        $sql = "SELECT DISTINCT specialization_name,specialization_id,specialization_code,faculty_name,course_name FROM pel_course_specialization WHERE course_id = '$course_id' OR course_name = '$course_name' ORDER BY specialization_name ASC ";

        error_log(__FUNCTION__." SQL $sql ");
        $dt = $this->db->fetch($sql);

        //error_log(__FUNCTION__." Course $course_name results ".json_encode($dt));


        return $dt;
    }

    public function getAwards() {

        $sql = "select award_id,award_name,award_code from pel_edu_awards ";

        $dt = $this->db->fetch($sql);

        return $dt;
    }

    public function getLevels() {

        $sql = "select level_id,level_name,level_code from pel_edu_levels ";

        $dt = $this->db->fetch($sql);

        return $dt;
    }

    public function correct(){

        $sql = "select student_id,student_first_name,student_second_name,student_third_name from pel_edu_data WHERE student_second_name IS NULL";
        $data = $this->db->fetch($sql);
        $x = 0;

        foreach ($data as $row) {

            $student_id = $row->student_id;
            $student_first_name = $row->student_first_name;

            $parts = explode(" ",$student_first_name,3);

            if (count($parts) > 1) {

                $x++;
                $sql = "UPDATE pel_edu_data SET student_first_name = :student_first_name, student_second_name = :student_second_name,student_third_name = :student_third_name WHERE student_id = :student_id ";
                $params = [
                    ':student_first_name' => $parts[0],
                    ':student_second_name' => isset($parts[1]) ? $parts[1] : "",
                    ':student_third_name' => isset($parts[2]) ? $parts[2] : "",
                    ':student_id' => $student_id
                ];

                $rc  = $this->db->update($sql,$params);
                echo "Record $x Row Count $rc \n";
            }


        }
    }

    public function addData($json) {

        $student_reg_number = isset($json->student_reg_number) ? $json->student_reg_number : "";
        $student_first_name = isset($json->student_first_name) ? $json->student_first_name : "";
        $student_second_name = isset($json->student_second_name) ? $json->student_second_name : "";
        $student_third_name = isset($json->student_third_name) ? $json->student_third_name : "";

        $added_by = isset($json->added_by) ? $json->added_by : "";

        //$student_first_name = "$student_first_name $student_second_name $student_third_name ";

        $student_token = $this->token();
        
        $verified_by = $added_by;
        $approved_by = $added_by;

        $graduation_date = isset($json->graduation_date) ? $json->graduation_date : "";
        $data_source = isset($json->data_source) ? $json->data_source : "";
        $graduation_congregation = isset($json->graduation_congregation) ? $json->graduation_congregation : "";

        $inst_code = isset($json->inst_code) ? $json->inst_code : "";
        $institution_name = isset($json->institution_name) ? $json->institution_name : "";
        $faculty_code = isset($json->faculty_code) ? $json->faculty_code : "";
        $faculty_name = isset($json->faculty_name) ? $json->faculty_name : "";
        $course_code = isset($json->course_code) ? $json->course_code : "";
        $course_name = isset($json->course_name) ? $json->course_name : "";

        $specialization_code = isset($json->specialization_code) ? $json->specialization_code : "";
        $student_specialization = isset($json->student_specialization) ? $json->student_specialization : "";
        $award_code = isset($json->award_code) ? $json->award_code : "";
        $award = isset($json->award) ? $json->award : "";
        $level_code = isset($json->level_code) ? $json->level_code : "";
        $course_level = isset($json->course_level) ? $json->course_level : "";


        $sql = "INSERT INTO pel_edu_data (student_reg_number,student_status, student_first_name, student_second_name,student_third_name,student_date_uploaded,"
            . "student_token, added_by, verified_by, approved_by,  graduation_date,data_source,"
            . "graduation_congregation, inst_code,institution_name,faculty_code,faculty_name,course_code,"
            . "course_name,specialization_code, student_specialization,award_code, award, level_code, course_level) VALUES "
            . "(:student_reg_number,11,:student_first_name,:student_second_name,:student_third_name,now(),:student_token,:added_by,:verified_by,:approved_by,"
            . ":graduation_date,:data_source,:graduation_congregation,:inst_code,:institution_name,:faculty_code,:faculty_name,"
            . ":course_code,:course_name,:specialization_code,:student_specialization,:award_code,:award,:level_code,:course_level)";

        $params = [
            ':student_reg_number' => $student_reg_number,
            ':student_first_name' => $student_first_name,
            ':student_second_name' => $student_second_name,
            ':student_third_name' => $student_third_name,
            ':student_token' => $student_token,
            ':added_by' => $added_by,
            ':verified_by' => $verified_by,
            ':approved_by' => $approved_by,
            ':graduation_date' => $graduation_date,
            ':data_source' => $data_source,
            ':graduation_congregation' => $graduation_congregation,
            ':inst_code' => $inst_code,
            ':institution_name' => $institution_name,
            ':faculty_code' => $faculty_code,
            ':faculty_name' => $faculty_name,
            ':course_code' => $course_code,
            ':course_name' => $course_name,
            ':specialization_code' => $specialization_code,
            ':student_specialization' => $student_specialization,
            ':award_code' => $award_code,
            ':award' => $award,
            ':level_code' => $level_code,
            ':course_level' => $course_level
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;
    }

    public function removeData($id) {

        $sql = "DELETE FROM pel_edu_data WHERE student_id = :id";
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

    public function getStudents() {

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

            $orWhere[] = "pel_edu_data.student_reg_number regexp :filter ";
            $orWhere[] = "pel_edu_data.student_first_name regexp :filter ";
            $params[':filter'] = $filter;
        }

        $fields = array();

        $joins = array();

        $table = "pel_edu_data";

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

        $groupBy[] = "student_id";

        if(count($groupBy) > 0){

            $group = "GROUP BY ".implode(",",$groupBy);
        }
        else{
            $group = "";
        }

        $countQuery = "SELECT COUNT(DISTINCT pel_edu_data.student_id) AS id FROM `$table` $join WHERE $where ";

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