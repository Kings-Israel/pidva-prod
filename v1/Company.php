<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 15/07/20
 * Time: 07:24
 */

require_once "includes.php";

class Company
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

    public function getBusinessType($request_id) {

        $sql = "select company_reg_id from pel_company_registration WHERE search_id = (SELECT request_ref_number FROM pel_psmt_request WHERE request_id = :i)";
        $paramas = [':i'=>$request_id];

        $dt = $this->db->fetchOne($sql,$paramas);

        return $dt->company_reg_id;
    }

    public function getCompanyRegID($request_id) {

        $sql = "select company_reg_id from pel_company_registration WHERE search_id = (SELECT request_ref_number FROM pel_psmt_request WHERE request_id = :i)";
        $paramas = [':i'=>$request_id];

        $dt = $this->db->fetchOne($sql,$paramas);

        return $dt->company_reg_id;
    }

    public function shareholding($request_id) {

        $sql = "SELECT * FROM pel_company_shareholding WHERE company_reg_id = :r";
        $params = [':r'=>$this->getCompanyRegID($request_id)];

        $data = $this->db->fetch($sql,$params);

        return $data;
    }

    public function addShareHolding($request_id,$name,$citizenship,$shares,$share_value,$share_percentage,$description) {

        $sql = "INSERT INTO pel_company_shareholding (company_reg_id,name,citizenship,shares,share_value,share_percentage,description,created) VALUE (:request_id,:name,:citizenship,:shares,:share_value,:share_percentage,:description,now())";
        $params = [
            ':request_id' => $this->getCompanyRegID($request_id),
            ':name' => $name,
            ':citizenship' => $citizenship,
            ':shares' => $shares,
            ':description' => $description,
            ':share_value' => $share_value,
            ':share_percentage' => $share_percentage,
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;
    }

    public function updateShareHolding($id,$name,$citizenship,$shares,$share_value,$share_percentage,$description) {

        $sql = "UPDATE pel_company_shareholding SET name = :name,citizenship = :citizenship,shares = :shares,share_value = :share_value,share_percentage =:share_percentage,description = :description WHERE id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':citizenship' => $citizenship,
            ':shares' => $shares,
            ':description' => $description,
            ':share_value' => $share_value,
            ':share_percentage' => $share_percentage
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function removeShareHolding($id) {

        $sql = "DELETE FROM pel_company_shareholding WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function addEncumbrances($request_id,$date,$description) {

        $sql = "INSERT INTO pel_encumbrances (company_reg_id,date,description,created) VALUE (:request_id,:date,:description,now())";
        $params = [
            ':request_id' => $this->getCompanyRegID($request_id),
            ':date' => $date,
            ':description' => $description
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;

    }

    public function removeEncumbrances($id) {

        $sql = "DELETE FROM pel_encumbrances WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $affectedRows = $this->db->update($sql,$params);

        $sql = "DELETE FROM pel_encumbrances_amount WHERE encumbrances_id = :id";
        $params = [
            ':id' => $id
        ];
        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function updateEncumbrances($id,$date,$description) {

        $sql = "UPDATE pel_encumbrances SET date = :date,description = :description WHERE id = :id";
        $params = [
            ':id' => $id,
            ':date' => $date,
            ':description' => $description
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function encumbrances($request_id) {

        $sql = "SELECT * FROM pel_encumbrances WHERE company_reg_id = :r";
        $params = [':r'=>$this->getCompanyRegID($request_id)];

        $data = $this->db->fetch($sql,$params);

        foreach ($data as $k=>$v) {

            $i = $v->id;
            $sql = "SELECT * FROM pel_encumbrances_amount WHERE encumbrances_id = :i ";
            $params = [':i'=>$i];
            $v->amount = $this->db->fetch($sql,$params);
            $data[$k] = $v;
        }

        return $data;
    }

    public function removeEmcumbraceAmount($id) {

        $sql = "DELETE FROM pel_encumbrances_amount WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function addEncumbrancesAmount($id,$currency,$amount) {

        $sql = "INSERT INTO pel_encumbrances_amount (encumbrances_id,currency,amount,created) VALUE (:id,:currency,:amount,now()) ";

        $params = [
            ':id' => $id,
            ':currency' => $currency,
            ':amount' => $amount
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;
    }


    // business ownership
    public function businessOwnership($request_id) {

        $sql = "SELECT * FROM pel_business_ownership WHERE company_reg_id = :r";
        $params = [':r'=>$this->getCompanyRegID($request_id)];

        $data = $this->db->fetch($sql,$params);

        return $data;
    }

    public function addBusinessOwnership($request_id,$name,$idnumber,$citizenship,$description) {

        $sql = "INSERT INTO pel_business_ownership (company_reg_id,name,idnumber,citizenship,description,created) VALUE (:request_id,:name,:idnumber,:citizenship,:description,now())";
        $params = [
            ':request_id' => $this->getCompanyRegID($request_id),
            ':name' => $name,
            ':citizenship' => $citizenship,
            ':idnumber' => $idnumber,
            ':description' => $description,
        ];

        $lastInsertID = $this->db->insert($sql,$params);

        return $lastInsertID;
    }

    public function updateBusinessOwnership($id,$name,$idnumber,$citizenship,$description) {

        $sql = "UPDATE pel_business_ownership SET name = :name,citizenship = :citizenship,idnumber = :idnumber,description = :description WHERE id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':citizenship' => $citizenship,
            ':idnumber' => $idnumber,
            ':description' => $description
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }

    public function removeBusinessOwnership($id) {

        $sql = "DELETE FROM pel_business_ownership WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $affectedRows = $this->db->update($sql,$params);

        return $affectedRows;
    }


}