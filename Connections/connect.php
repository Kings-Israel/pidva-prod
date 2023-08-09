<?php

$configs = parse_ini_file("/var/www/html/pidva/config/config.ini", true);
$configs = $configs['database'];
date_default_timezone_set('Africa/Nairobi');

$hostname_connect = $configs['host'];
$database_connect = $configs['dbname'];
$username_connect = $configs['username'];
$password_connect = $configs['password'];
$port = $configs['port'];

$connect = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect);


if ($GLOBALS['connect']) {
    $connect = $GLOBALS['connect'];
} else {

    $host = gethostname();
    header("Location: error/");
}

function mysqli_query_ported($string, $sss = null)
{

    $connect = $GLOBALS['connect'];

    return $connect->query($string);
}

function set_module_status($status, $ref_number = null)
{
    $module_id = $_GET['moduleid'];
    $connect = $GLOBALS['connect'];
    if ($ref_number) {
        $sql = sprintf("UPDATE pel_psmt_request_modules SET status='$status' WHERE request_ref_number=$ref_number AND module_id='$module_id'");
        $connect->query($sql);
    }
}

function call_callback($ref_number)
{
    // $url = sprintf(
    //     'https://boya.pidva.africa/psmt/callback/?request_ref=%s',
    //     trim($ref_number, '\'"')
    // );
    // $curl = curl_init($url);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // curl_exec($curl);
    // curl_close($curl);
    // return;
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $connect = $GLOBALS['connect'];

    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect, $theValue) : mysqli_escape_string($connect, $theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

// http://localhost/html/company/companyregcheck.php?fullnames=MARITA%20MUTEMI%20(marita@peleza.com)&status=11&search_id_approve=BOYA-RQ-C46D3Z2-102603&request_id=2606&moduleid=47
$ref_number = GetSQLValueString(isset($request['request_ref_number']) ? $request['request_ref_number'] : $_GET['search_id_approve'], 'text');



function startsWith($string, $startString)
{
    return strpos($string, $startString) === 0;
}
