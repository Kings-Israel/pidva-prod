<?php

require_once "vendor/autoload.php";

function uploadFile($key,$location,$name = null)
{

    //error_log("Wants to upload $name to $location/$name ");

    //if (isset($_FILES[$name])) {

        $configs = parse_ini_file("/var/www/html/pidva-dev/config/config.ini", true);
        $config = json_decode(json_encode($configs));

        $errors = array();
        $file_name = $_FILES[$key]['name'];
        $file_size = $_FILES[$key]['size'];
        $file_tmp = $_FILES[$key]['tmp_name'];
        $file_type = $_FILES[$key]['type'];

        $ps = explode(".",$file_name);
        $ext = $ps[count($ps) - 1 ];

        error_log("got $ext _FILES ".json_encode($_FILES[$key]));

        if(!is_null($name) && $name != "") {

            //$file_name = $name;
        }

       //$file_name = preg_replace("/[^A-Za-z0-9 ]/", '_', $file_name);

        //$file_name = str_replace("'",'_',$file_name);
       // $file_name = str_replace('@','_',$file_name);
        //$file_name = str_replace(" ",'_',$file_name);

   // error_log("NAME $file_name");
    error_log("PATH $file_tmp");

        //$path_parts = pathinfo($file_name);
        //file extension
        //$file_ext = $path_parts['extension'];

        $file_ext = strtolower($ext);

        //$file_ext = strtolower(end(explode('.', $_FILES[$name]['name'])));

        $extensions = array("jpeg", "jpg", "png", "pdf", "doc", "docx");

        if (in_array($file_ext, $extensions) === false) {

            // $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152 * 20 ) {

            $errors[] = 'File size must be less than 40 MB';
        }

        if (empty($errors) == true) {

            $name = preg_replace("/[^A-Za-z0-9]/", '_', $name);

            //$filename = str_replace("-", "_", $name) . "_" . microtime(true);

            $filename =microtime(true). "_".$name.".".$file_ext;

            $region = $config->spaces->region;
            $spaceName = $config->spaces->individualRequests;
            $endpoint = $config->spaces->endpoint;
            $bucket = $config->spaces->bucket;

            $location = str_replace(" ","-",$location);

            $fna = "/var/www/html/ttt/".microtime(true)."_$filename";

            move_uploaded_file($file_tmp, $fna);
            error_log("GOT path $fna ");

            $space = new SpacesConnect($config->spaces->key, $config->spaces->secret, $bucket, $region);
            $space->UploadFile($fna, "public", "$location/$filename", mime_content_type($fna));
            $filepath = "$endpoint/" . $location . "/" . $filename;
            unlink($fna);

            return $filepath;

        } else {

            error_log(print_r($errors, 1));
            return false;

        }
    //}

    return false;

}

function uploadFileFromPath($path,$location)
{

    $path_parts = pathinfo($path);
    $file_ext = $path_parts['extension'];
    $file_name = rand(1,100000000).$path_parts['basename'];

    //error_log("Wants to upload $path to $location/$file_name ");

    $configs = parse_ini_file("/var/www/html/pidva-dev/config/config.ini", true);
    $config = json_decode(json_encode($configs));

    $errors = array();

    $file_name = str_replace("'",'_',$file_name);
    $file_name = str_replace('@','_',$file_name);
    $file_name = str_replace(' ','_',$file_name);

   // error_log("PATH $file_name");


    if (empty($errors) == true) {

        $region = $config->spaces->region;
        $endpoint = $config->spaces->endpoint;
        $bucket = $config->spaces->bucket;

        $location = str_replace(" ","-",$location);

        try {

            $space = new SpacesConnect($config->spaces->key, $config->spaces->secret, $bucket, $region);
            $space->UploadFile($path, "public", "$location/$file_name", mime_content_type($path));
            $filepath = "$endpoint/" . $location . "/" . $file_name;
            return $filepath;
        }
        catch (Exception $e) {

            error_log("got error here ".$e->getMessage());
            return false;
        }

    } else {

        error_log(print_r($errors, 1));
        return false;

    }
}

?>