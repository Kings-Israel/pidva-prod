<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 07/04/20
 * Time: 16:32
 */

require_once "Notifier.php";
require_once "Company.php";
require_once "Library.php";

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = $_POST;// json_decode($json);

if(empty($data) || count($data) == 0 ) {

    $data = json_decode($json,1);
}

if( isset($data['data_type'])) {

    $data_type = $data['data_type'];
    $request_id = $data['request_id'];
    $id = isset($data['id']) ? $data['id'] : false;

    $company = new Company();
    $status = 200;

    if($data_type == "read") {

        $res = $company->businessOwnership($request_id);
        Library::setResponse(['status'=>200,'message'=>$res],$status,'not found');
        return;

    }
    else if($data_type == "create") {

        $name = isset($data['name']) ? $data['name'] : false;
        $idnumber = isset($data['idnumber']) ? $data['idnumber'] : false;
        $citizenship = isset($data['citizenship']) ? $data['citizenship'] : false;
        $description = isset($data['description']) ? $data['description'] : "";

        if(!$name || !$idnumber || !$citizenship || !$description ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->addBusinessOwnership($request_id,$name,$idnumber,$citizenship,$description);
        Library::setResponse(['status'=>201,'message'=>"Business added successfully"],201,'OK');
        return;
    }
    else if($data_type == "update") {

        $name = isset($data['name']) ? $data['name'] : false;
        $idnumber = isset($data['idnumber']) ? $data['idnumber'] : false;
        $citizenship = isset($data['citizenship']) ? $data['citizenship'] : false;
        $description = isset($data['description']) ? $data['description'] : "";

        if(!$name || !$idnumber || !$citizenship || !$description ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->updateBusinessOwnership($id,$name,$idnumber,$citizenship,$description);
        Library::setResponse(['status'=>201,'message'=>"Business updated successfully"],201,'OK');
        return;

    }
    else if($data_type == "delete") {

        if(!$id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeBusinessOwnership($id);
        Library::setResponse(['status'=>201,'message'=>"Business deleted successfully"],201,'OK');
        return;

    }

    Library::setResponse(['status'=>421,'message'=>"Missing fields for $data_type got ".print_r($data,1)],421,'unprocessible entity');

}
else {

    Library::setResponse(['status'=>422,'message'=>'missing required fields got '.json_encode($data)],422,'not found');
}
