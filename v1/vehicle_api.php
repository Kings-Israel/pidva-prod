<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 07/04/20
 * Time: 16:32
 */

require_once "includes.php";

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = $_POST;// json_decode($json);

if(empty($data) || count($data) == 0 ) {

    $data = json_decode($json,1);
    $json = json_decode($json);

}

if( isset($data['data_type'])) {

    $data_type = $data['data_type'];

    $company = new Vehicle();
    $status = 200;

    if($data_type == "create") {

        try {

            $res = $company->addData($json);
            Library::setResponse(['status' => 201, 'message' => "Vehicle added successfully"], 201, 'OK');
            return;

        }
        catch (Exception $e) {

            Library::setResponse(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode(), 'Failed');
            return;
        }
    }

    else if($data_type == "update") {

        try {

            $res = $company->update($json);
            Library::setResponse(['status' => 201, 'message' => "Vehicle updated successfully"], 201, 'OK');
            return;

        }
        catch (Exception $e) {

            Library::setResponse(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode(), 'Failed');
            return;
        }
    }

    else if($data_type == "delete") {

        $student_id = isset($data['id']) ? $data['id'] : false;

        if(!$student_id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeData($student_id);
        Library::setResponse(['status'=>201,'message'=>"Vehicle data removed successfully"],201,'OK');
        return;

    }

    else if($data_type == "read") {

        $student_id = isset($data['registration_number']) ? $data['registration_number'] : false;

        if(!$student_id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->getVehicle($student_id);
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;

    }

    Library::setResponse(['status'=>421,'message'=>"Missing fields for $data_type got ".print_r($data,1)],421,'unprocessible entity');

}
else {

    $company = new Vehicle();

    $res = $company->getTable();

    Library::setResponse($res,200,'not found');
}
