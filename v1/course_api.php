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

    $company = new Course();
    $status = 200;

    if($data_type == "create") {

        $res = $company->addData($json);
        Library::setResponse(['status'=>201,'message'=>"Course added successfully"],201,'OK');
        return;
    }

    else if($data_type == "update") {

        $res = $company->updateData($json);
        Library::setResponse(['status'=>201,'message'=>"Course updated successfully"],201,'OK');
        return;
    }

    else if($data_type == "delete") {

        $student_id = isset($data['faculty_id']) ? $data['faculty_id'] : false;

        if(!$student_id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeData($student_id);
        Library::setResponse(['status'=>201,'message'=>"Course data removed successfully"],201,'OK');
        return;

    }

    Library::setResponse(['status'=>421,'message'=>"Missing fields for $data_type got ".print_r($data,1)],421,'unprocessible entity');

}
else {

    $company = new Course();

    $res = $company->getTable();

    Library::setResponse($res,200,'not found');
}
