<?php

/**
 * Created by PhpStorm.
 * User: phil
 * Date: 01/04/20
 * Time: 10:04
 */
session_start();

require_once "includes.php";
require_once "vendor/autoload.php";
require_once "./notify_on_upload.php";


class Uploads
{
    public $requestID;
    public $db;
    public $config;
    public $logger;
    private $mailer;


    public function __construct()
    {
        $configs = parse_ini_file("config/config.ini", true);
        $configs = json_decode(json_encode($configs));

        $this->config = $configs;
        $this->db = new DB();
        $this->mailer = $GLOBALS['mailer'];
    }

    public function getReport()
    {

        $sql = "SELECT * FROM pel_psmt_request WHERE request_id = :request_id";
        $params = [':request_id' => $this->requestID];

        try {

            $data = $this->db->fetchOne($sql, $params);

            $response = [];

            if ($data && isset($data->request_ref_number)) {

                $search_ref = $data->request_ref_number;
                $response['pel_psmt_request'] = $data;
                $type = $data->request_type;
            } else {

                $re = array(
                    'status' => 404,
                    'message' => $response
                );

                return Library::setResponse($re, 404, "Failed");
            }

            $params = ['search_id' => $search_ref];

            if ($type == "COMPANY") {

                $reports = $this->companyDataSet;
            } else if ($type == "INDIVIDUAL") {

                $reports = $this->individualDataSet;
            }

            foreach ($reports as $table) {

                $this->getData($response, $table, $params);
            }

            return Library::setResponse($response, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    private function getData(&$response, $table, $params)
    {

        $where = "1";
        $placeholders = [];
        $wheres = [];

        foreach ($params as $key => $value) {

            $wheres[] = "$key = :$key ";
            $placeholders[":$key"] = $value;
        }

        if (count($placeholders) > 0) {

            $where = implode(" AND ", $wheres);
        }

        $sql = "SELECT * FROM $table WHERE $where ";

        try {

            $data = $this->db->fetch($sql, $placeholders);
            $response[$table] = $data;
        } catch (Exception $e) {
        }
    }

    public function getStats($login_id)
    {

        $sql = "SELECT count(request_id) as count,status FROM pel_psmt_request WHERE client_login_id = '$login_id' GROUP BY status ";


        try {

            $data = $this->db->fetch($sql);

            $stats = new stdClass();

            $pending = 0;

            foreach ($data as $key => $row) {

                switch ($row->status) {

                    case "44":
                        $stats->progress = $row->count;
                        break;

                    case "00":
                        $stats->new_request = $row->count;
                        break;

                    case "11":
                        $stats->final_report = $row->count;
                        break;

                    case "33":
                        $stats->interim = $row->count;
                        break;

                    case "22":
                        $stats->no_data = $row->count;
                        $pending = +$row->count;
                        break;

                    case "55":
                        $stats->awaiting_quotation = $row->count;
                        $pending = +$row->count;
                        break;

                    case "66":
                        $stats->awaiting_payment = $row->count;
                        $pending = +$row->count;
                        break;
                }

                $stats->pending = $pending;
            }

            return Library::setResponse($stats, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    public function getPackages($client_id)
    {

        $sql = "SELECT package_id, package_name, client_id FROM pel_client_package where client_id='$client_id' ";

        error_log("got sql $sql");

        try {

            $data = $this->db->fetch($sql);

            if (!$data) {

                $query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
                $data = $this->db->fetch($query_getpackagegeneral);
            }

            return Library::setResponse($data, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    public function getPackage($package_id)
    {

        $sql = "SELECT * FROM pel_package WHERE package_id ='$package_id' ";

        try {

            $data = $this->db->fetchOne($sql);
            $sql = "SELECT pel_module.module_name, pel_packages_module.cost_currency,pel_packages_module.module_cost, pel_packages_module.module_id,pel_packages_module.package_id FROM pel_packages_module Inner Join pel_module ON  pel_packages_module.module_id = pel_module.module_id WHERE pel_packages_module.package_id = '$package_id' ORDER BY pel_module.module_name ASC ";

            $modules = $this->db->fetch($sql);
            $data->modules = $modules;

            return Library::setResponse($data, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    public function getCitizenship()
    {

        $sql = "SELECT * FROM pel_countries ORDER BY country_nationality ASC ";

        try {

            $data = $this->db->fetch($sql);

            return Library::setResponse($data, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    public function getDocuments($module_id)
    {

        $sql = "SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id,pel_packages_module.module_name, pel_packages_module.module_id FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id WHERE pel_packages_module.module_id IN ($module_id)";
        try {

            error_log("GOT DOCUMENTS QUERY $sql ");
            $data = $this->db->fetch($sql);

            $res = [];

            $mids = [];

            foreach ($data as $k => $v) {

                $mid = $v->module_id;
                $res[$mid][] = $v;

                $s = new stdClass();
                $s->module_name = $v->module_name;
                $s->module_id = $v->module_id;

                $mids[$mid] = $s;
            }

            $results = [];

            foreach ($mids as $k => $v) {

                $v->documents = $res[$k];
                $results[] = $v;
            }

            return Library::setResponse($results, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }


    public function getReference()
    {

        $key = "reference";
        if (isset($_SESSION[$key])) {

            return $_SESSION[$key];
        }

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $value = substr(str_shuffle($permitted_chars), 0, 10);

        $_SESSION[$key] = $value;

        return $value;
    }

    public function unsetReference()
    {

        $key = "reference";
        unset($_SESSION[$key]);
    }

    public function postData()
    {

        if (isset($_POST['request_type'])) {

            $package_id = $_POST['package_id'];
            $client_id = $_POST['client_id'];
            $user_id = $_POST['user_id'];
            $client_company_id = $_POST['client_company_id'];
            $request_ref_number = $_POST['request_ref_number'];
            $staff_id = $_POST['staff_id'];
            $uploaded_by = $_POST['uploaded_by'];
            $request_plan = $_POST['request_plan'];
            $request_id = $_POST['request_id'];

            switch ($_POST['request_type']) {

                case "general":

                    $bg_dataset_name = $_POST['bg_dataset_name'];

                    $bg_dataset_mobile = isset($_POST['bg_dataset_mobile']) ? $_POST['bg_dataset_mobile'] : "";
                    $bg_dataset_email = isset($_POST['bg_dataset_email']) ? $_POST['bg_dataset_email'] : "";
                    $bg_dataset_idnumber = isset($_POST['bg_dataset_idnumber']) ? $_POST['bg_dataset_idnumber'] : "";


                    $dataset_citizenship = $_POST['dataset_citizenship'];
                    $cost = $_POST['cost'];

                    $file_name = $_POST['file_name'];
                    $key = $this->getReference();

                    // get consent form
                    $consent_form = $this->uploadFile($file_name);
                    $general = array(
                        'bg_dataset_name' => $bg_dataset_name,
                        'bg_dataset_email' => $bg_dataset_email,
                        'bg_dataset_mobile' => $bg_dataset_mobile,
                        'dataset_citizenship' => $dataset_citizenship,
                        'bg_dataset_idnumber' => $bg_dataset_idnumber,
                        'request_ref_number' => $request_ref_number,
                        'package_id' => $package_id,
                        'client_id' => $client_id,
                        'user_id' => $user_id,
                        'client_company_id' => $client_company_id,
                        'consent_form' => $consent_form,
                        'staff_id' => $staff_id,
                        'uploaded_by' => $uploaded_by,
                        'request_plan' => $request_plan,
                        'request_id' => $request_id,
                        'type' => 'general',
                        'cost' => $cost
                    );

                    $vals = isset($_SESSION[$key]) ? $_SESSION[$key] : [];
                    $vals['general'] = $general;

                    $_SESSION[$key] = $vals;
                    break;

                case "module":

                    $data_type = $_POST['data_type'];

                    $document_name = isset($_POST['document_name']) ? $_POST['document_name'] : "";
                    $module_name = isset($_POST['module_name']) ? $_POST['module_name'] : "";
                    $module_id = isset($_POST['module_id']) ? $_POST['module_id'] : "";
                    $module_doc_id = isset($_POST['module_doc_id']) ? $_POST['module_doc_id'] : "";

                    if ($data_type == "file") {

                        $file_name = $_POST['file_name'];
                        $value = $this->uploadFile($file_name);
                    } else if ($data_type == "none") {

                        $value = "none";
                    } else {

                        $value = $_POST['value'];
                    }


                    $key = $this->getReference();

                    // get consent form
                    $general = array(
                        'document_name' => $document_name,
                        'module_name' => $module_name,
                        'module_id' => $module_id,
                        'module_doc_id' => $module_doc_id,
                        'request_ref_number' => $request_ref_number,
                        'package_id' => $package_id,
                        'client_id' => $client_id,
                        'user_id' => $user_id,
                        'client_company_id' => $client_company_id,
                        'value' => $value,
                        'type' => 'module',
                        'data_type' => $data_type,
                        'staff_id' => $staff_id,
                        'uploaded_by' => $uploaded_by,
                        'request_plan' => $request_plan,
                        'request_id' => $request_id,
                    );

                    $vals = isset($_SESSION[$key]) ? $_SESSION[$key] : [];
                    $vals['module'][] = $general;

                    $_SESSION[$key] = $vals;
            }

            Library::setResponse(['status' => 200, 'message' => "Request created successfully"], 200, 'success');
            return;
        }

        Library::setResponse(['status' => 422, 'message' => "missing required fields"], 422, 'aborted');
        return;
    }

    public function submit()
    {

        $key = $this->getReference();
        // get all values
        $vals = $_SESSION[$key];
        $general = $vals['general'];
        $package_id = $general['package_id'];
        $request_plan = $general['request_plan'];
        $request_id = $general['request_id'];
        $bg_dataset_name = $general['bg_dataset_name'];
        $request_ref_number = $general['request_ref_number'];
        $MM_client_id = $general['client_id'];
        $dataset_citizenship = $general['dataset_citizenship'];
        $MM_client_parent_company = $general['client_company_id'];
        $uploadedby = $general['uploaded_by'];

        $bg_dataset_mobile = isset($general['bg_dataset_mobile']) ? $general['bg_dataset_mobile'] : "";
        $bg_dataset_email = isset($general['bg_dataset_email']) ? $general['bg_dataset_email'] : "";
        $bg_dataset_idnumber = isset($general['bg_dataset_idnumber']) ? $general['bg_dataset_idnumber'] : "";

        $MM_client_login_id = $general['user_id'];
        $staff_id = $general['staff_id'];
        $consent_form = $general['consent_form'];
        $cost = $general['cost'];
        $uploaded_by = $general['uploaded_by'];

        $BLOCKCHAIN = "TRACK" . date('U') . "" . $staff_id . "" . $request_ref_number;;

        // create generate request
        //get package id
        $sql = "SELECT pel_dataset.dataset_name, pel_dataset.dataset_type 
        FROM pel_package 
        Inner Join pel_dataset ON pel_dataset.dataset_id = pel_package.dataset_id 
        WHERE pel_package.package_id = '$package_id'";

        $results = $this->db->fetchOne($sql);

        $dataset_name = $results->dataset_name;
        $dataset_type = $results->dataset_type;

        $params = array(
            ':request_plan' => $request_plan,
            ':bg_dataset_name' => $bg_dataset_name,
            ':request_ref_number' => $request_ref_number,
            ':client_id' => $MM_client_id,
            ':dataset_citizenship' => $dataset_citizenship,
            ':client_parent_company' => $MM_client_parent_company,
            ':uploadedby' => $uploadedby,
            ':bg_dataset_email' => $bg_dataset_email,
            ':bg_dataset_mobile' => $bg_dataset_mobile,
            ':BLOCKCHAIN' => $BLOCKCHAIN,
            ':package_id' => $package_id,
            ':dataset_name' => $dataset_name,
            ':dataset_type' => $dataset_type,
            ':client_login_id' => $MM_client_login_id,
            ':bg_dataset_idnumber' => $bg_dataset_idnumber,
            ':cost' => $cost,
            ':user_id' => $MM_client_login_id,
            ':user_name' => $uploaded_by
        );

        $sql = "INSERT INTO pel_psmt_request (request_plan,bg_dataset_name,request_ref_number,client_id,request_date,dataset_citizenship,request_dataset_cat,company_name, client_name, bg_dataset_email, bg_dataset_mobile, file_tracker, request_package, dataset_name, request_type, client_login_id,bg_dataset_idnumber,package_cost,user_id,user_name)
                                        VALUES (:request_plan,:bg_dataset_name,:request_ref_number,:client_id,now(),:dataset_citizenship,
                                        :request_plan,:client_parent_company,:uploadedby,:bg_dataset_email,:bg_dataset_mobile,:BLOCKCHAIN,
                                        :package_id, :dataset_name,:dataset_type,:client_login_id,:bg_dataset_idnumber,:cost,:user_id,:user_name)";

        try {

            $_request_id = $this->db->insert($sql, $params);
            $this->mailer->send_upload_notification($MM_client_parent_company, isset($_request_id) ? $_request_id : '#');
        } catch (Exception $e) {

            error_log("got error executing query $sql\nerror " . $e->getTraceAsString());
            Library::setResponse(['status' => 500, 'message' => $e->getTraceAsString()], 500, 'internal server error');
            return;
        }

        // upload consent form
        $sql = "INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                        VALUES ('$consent_form','Consent Form','$BLOCKCHAIN','$_request_id','$MM_client_id','file')";

        try {

            $this->db->insert($sql);
        } catch (Exception $e) {

            error_log("got error executing query $sql\nerror " . $e->getMessage() . " trace " . $e->getTraceAsString());

            Library::setResponse(['status' => 500, 'message' => $e->getMessage()], 500, 'internal server error');
            return;
        }

        //create modules
        $modules = isset($vals['module']) ? $vals['module'] : [];

        foreach ($modules as $module) {

            $module_id = $module['module_id'];
            $module_name = $module['module_name'];

            $params = array(
                ':request_ref_number' => $request_ref_number,
                ':MM_client_id' => $MM_client_id,
                ':package_id' => $package_id,
                ':request_plan' => $request_plan,
                ':module_name' => $module_name,
                ':dataset_type' => $dataset_type,
                ':module_id' => $module_id,

            );

            $sql = "INSERT INTO pel_psmt_request_modules (request_ref_number,status,client_id,package_id,package_name, module_name, request_type, module_id) 
                                                VALUES (:request_ref_number,'00',:MM_client_id,:package_id,:request_plan,:module_name,:dataset_type,:module_id)";

            try {

                $this->db->insert($sql, $params);
            } catch (Exception $e) {

                error_log("got error executing query $sql\nerror " . $e->getMessage() . " trace " . $e->getTraceAsString());

                Library::setResponse(['status' => 500, 'message' => $e->getMessage()], 500, 'internal server error');
                return;
            }

            $document_name = $module['document_name'];
            $data_type = $module['data_type'];
            $datafile = "datafile_" . $module['module_doc_id'];

            if ($data_type == 'file') {
                $value = $module['value'];

                $sql = "INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                                    VALUES ('$value','$document_name','$BLOCKCHAIN',$_request_id,'$MM_client_id','file')";

                try {

                    $this->db->insert($sql);
                } catch (Exception $e) {

                    error_log("got error executing query $sql\nerror " . $e->getMessage() . " trace " . $e->getTraceAsString());

                    Library::setResponse(['status' => 500, 'message' => $e->getMessage()], 500, 'internal server error');
                    return;
                }
            } else if ($data_type == 'text') {
                $value = $module['value'];

                $sql = "INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                                    VALUES ('$value','$document_name','$BLOCKCHAIN',$_request_id,'$MM_client_id','text')";

                try {

                    $this->db->insert($sql);
                } catch (Exception $e) {

                    error_log("got error executing query $sql\nerror " . $e->getMessage() . " trace " . $e->getTraceAsString());

                    Library::setResponse(['status' => 500, 'message' => $e->getMessage()], 500, 'internal server error');
                    return;
                }
            }
        }

        $this->unsetReference();

        Library::setResponse(['status' => 200, 'message' => "Request created successfully"], 200, 'success');
        return;
    }

    public function uploadFile($name)
    {
        // remove here
        if (isset($_FILES[$name])) {

            $errors = array();
            $file_name = $_FILES[$name]['name'];
            $file_size = $_FILES[$name]['size'];
            $file_tmp = $_FILES[$name]['tmp_name'];
            $file_type = $_FILES[$name]['type'];

            error_log("PATH $file_name");

            $path_parts = pathinfo($file_name);
            //file extension
            $file_ext = $path_parts['extension'];

            //$file_ext = strtolower(end(explode('.', $_FILES[$name]['name'])));

            $extensions = array("jpeg", "jpg", "png", "pdf", "doc", "docx");

            if (in_array($file_ext, $extensions) === false) {

                // $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152 * 5) {

                $errors[] = 'File size must be less than 10 MB';
            }

            if (empty($errors) == true) {

                $filename = str_replace("-", "_", $name) . "_" . microtime(true);

                $filename = "$filename." . $file_ext;

                $region = $this->config->spaces->region;
                $spaceName = $this->config->spaces->individualRequests;
                $endpoint = $this->config->spaces->endpoint;
                $bucket = $this->config->spaces->bucket;

                $space = new SpacesConnect($this->config->spaces->key, $this->config->spaces->secret, $bucket, $region);
                $space->UploadFile($file_tmp, "public", "$spaceName/$filename", mime_content_type($file_tmp));
                $filepath = "$endpoint/" . $spaceName . "/" . $filename;

                //move_uploaded_file($file_tmp,"images/".$file_name);
                return $filepath;
            } else {

                error_log(print_r($errors, 1));
                return false;
            }
        }

        return false;
    }
}

if (isset($_POST['request_ref_number'])) {

    $report = new Uploads();
    $report->postData();
} else if (isset($_POST['submit'])) {

    $report = new Uploads();
    $report->submit();
} else {

    Library::setResponse(['status' => 200, 'message' => 'missing required fields'], 200, 'not found');
}
