<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 07/04/20
 * Time: 16:32
 */

require_once "Notifier.php";
require_once "Education.php";
require_once "Library.php";

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = $_POST;// json_decode($json);

if(empty($data) || count($data) == 0 ) {

    $data = json_decode($json,1);
    $json = json_decode($json);

}

if( isset($data['data_type'])) {

    $data_type = $data['data_type'];

    $company = new Education();
    $status = 200;

    if($data_type == "create") {

        $res = $company->addData($json);
        Library::setResponse(['status'=>201,'message'=>"Student added successfully"],201,'OK');
        return;
    }

    else if($data_type == "delete") {

        $student_id = isset($data['student_id']) ? $data['student_id'] : false;

        if(!$student_id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }

        $res = $company->removeData($student_id);
        Library::setResponse(['status'=>201,'message'=>"Student data removed successfully"],201,'OK');
        return;

    }

    else if($data_type == "institution") {

        $res = $company->getInstitutions();
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    else if($data_type == "faculty") {

        $res = $company->getFaculty();
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    else if($data_type == "award") {

        $res = $company->getAwards();
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    else if($data_type == "level") {

        $res = $company->getLevels();
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    else if($data_type == "course") {

        $faculty_name = isset($data['faculty_name']) ? $data['faculty_name'] : false;

        if(!$faculty_name ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');

        }
        $res = $company->getCourse($faculty_name);
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    else if($data_type == "specialization") {

        $faculty_name = isset($data['course_name']) ? $data['course_name'] : false;
        $course_id = isset($data['course_id']) ? $data['course_id'] : false;

        if(!$course_id ) {

            Library::setResponse(['status'=>421,'message'=>"Missing fields"],421,'unprocessible entity');
            return;
        }
        $res = $company->getCourseSpecialization($course_id,$faculty_name);
        Library::setResponse(['status'=>201,'message'=>$res],201,'OK');
        return;
    }

    Library::setResponse(['status'=>421,'message'=>"Missing fields for $data_type got ".print_r($data,1)],421,'unprocessible entity');

}
else {

    $company = new Education();

    $res = $company->getStudents();

    Library::setResponse($res,200,'not found');
}
