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

        $res = $company->encumbrances($request_id);
        Library::setResponse(['status'=>200,'message'=>$res],$status,'not found');
        return;

    }
    else if($data_type == "create") {

        $date = isset($data['date']) ? $data['date'] : false;
        $amount = isset($data['amount']) ? $data['amount'] : false;
        $description = isset($data['description']) ? $data['description'] : "";

        if(!$date || !$amount || !$citizenship || !$amount ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->addEncumbrances($request_id,$date,$description);
        Library::setResponse(['status'=>201,'message'=>"Encumbrances added successfully"],201,'OK');
        return;
    }
    else if($data_type == "create_amount") {

        $currency = isset($data['currency']) ? $data['currency'] : false;
        $amount = isset($data['amount']) ? $data['amount'] : false;

        if(!$currency || !$amount || !$id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->addEncumbrancesAmount($id,$currency,$amount);
        Library::setResponse(['status'=>201,'message'=>"Encumbrances Amount added successfully"],201,'OK');
        return;
    }
    else if($data_type == "update") {

        $date = isset($data['date']) ? $data['date'] : false;
        $description = isset($data['description']) ? $data['description'] : "";

        if(!$id || !$date || !$description ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->updateEncumbrances($id,$date,$description);
        Library::setResponse(['status'=>201,'message'=>"Encumbrances updated successfully"],201,'OK');
        return;

    }
    else if($data_type == "delete") {

        if(!$id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeEncumbrances($id);
        Library::setResponse(['status'=>201,'message'=>"Encumbrances deleted successfully"],201,'OK');
        return;

    }
    else if($data_type == "delete_amount") {

        if(!$id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeEmcumbraceAmount($id);
        Library::setResponse(['status'=>201,'message'=>"Encumbrance Amount deleted successfully"],201,'OK');
        return;

    }

    Library::setResponse(['status'=>421,'message'=>"Missing fields for $data_type got ".print_r($data,1)],421,'unprocessible entity');

}
else {

    Library::setResponse(['status'=>422,'message'=>'missing required fields got '.json_encode($data)],422,'not found');
}
