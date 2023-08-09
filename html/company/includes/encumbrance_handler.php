<?php

require_once('../../Connections/connect.php');
if (!isset($_SESSION)) {
    session_start();
}

$post = $_POST;
$get = $_GET;
$request_id = isset($get['request_id']) ? $get['request_id'] : null;
$request_id = GetSQLValueString($request_id, "int");
$module_id = isset($get['moduleid']) ? $get['moduleid'] : null;
$module_id = GetSQLValueString($module_id, "int");

$get_request_sql = "SELECT * FROM pel_psmt_request WHERE request_id=$request_id";
$get_request_sql_result = $connect->query($get_request_sql);

$request = $get_request_sql_result->fetch_assoc();
global $request;

$ref_number = GetSQLValueString($request['request_ref_number'], 'text');
$current_user = $_SESSION['MM_full_names'];
$form_action = $_SERVER['PHP_SELF'];


if (isset($post['addencumbrance'])) {
    $search_id = $post['search_id'];
    $status = '11';
    $description = GetSQLValueString($post['description'], 'text');
    $date = GetSQLValueString($post['date'], 'text');
    $amount_secured = GetSQLValueString($post['amount_secured'], 'text');
    $added_by =  GetSQLValueString($current_user, 'text');

    $addencumrance_sql = sprintf("INSERT INTO pel_company_encumbrances (`search_id`, `status`, `description`, `date`, `amount_secured`, `added_by`) VALUES ($search_id,$status, $description, $date, $amount_secured, $added_by)");
    $query = $connect->query($addencumrance_sql);

    set_module_status('44', $ref_number);
} elseif (isset($post['updateencumbrance']) && isset($post['id'])) {
    $id = $post['id'];
    $description = $post['description'];
    $date = $post['date'];
    $amount_secured = $post['amount_secured'];

    $sql = sprintf(
        "UPDATE pel_company_encumbrances SET description=%s, amount_secured=%s,status='22', date=%s WHERE id=%s",
        GetSQLValueString($description, 'text'),
        GetSQLValueString($amount_secured, 'text'),
        GetSQLValueString($date, 'text'),
        GetSQLValueString($id, 'int')
    );

    $connect->query($sql);
    set_module_status('22', $ref_number);
} elseif (isset($post['setstatus'], $post['id'])) {
    $status = $get['setstatus'];
    $id = $get['id'];
    if ($status == '22') {
        $sql = sprintf("UPDATE pel_company_encumbrances SET (`status`=$status,review_status=NULL)  WHERE `search_id`=$ref_number AND id=$id ");
    } else {
        $sql = sprintf("UPDATE pel_company_encumbrances SET `status`=$status  WHERE `search_id`=$ref_number AND id=$id ");
    }
    $connect->query($sql);
} elseif (isset($post['approve']) && $post['approve'] == 'true') {
    call_callback($ref_number);
    $approve_sql = sprintf(
        "UPDATE pel_psmt_request_modules SET status=%s WHERE module_id=%s AND request_ref_number=%s",
        GetSQLValueString('11', "text"),
        GetSQLValueString($module_id, "text"),
        $ref_number
    );
    $approve_sql1 = sprintf(
        "UPDATE pel_company_encumbrances SET status=%s, review_status='APPROVED', verified_by=%s, verified_date=now(), review_notes=NULL WHERE search_id=%s",
        GetSQLValueString('11', "text"),
        GetSQLValueString($current_user, 'text'),
        $ref_number
    );


    $approve_sql2 = sprintf(
        "UPDATE pel_psmt_request_modules SET status=%s WHERE module_id=%s AND request_ref_number=%s",
        GetSQLValueString('11', "text"),
        GetSQLValueString($module_id, "text"),
        $ref_number
    );

    $connect->query($approve_sql);
    $connect->query($approve_sql1);
    $connect->query($approve_sql2);
} elseif (isset($post['reject']) && $post['reject'] == 'true') {
    $reject_sql_1 = sprintf(
        "UPDATE pel_company_encumbrances SET status=%s, review_status='REJECTED', verified_by=%s, verified_date=now(), review_notes=%s WHERE search_id=%s",
        GetSQLValueString('00', "text"),
        GetSQLValueString($current_user, 'text'),
        GetSQLValueString($_POST['review_notes'], "text"),
        $ref_number
    );


    $reject_sql_2 = sprintf(
        "UPDATE pel_psmt_request_modules SET status=%s WHERE module_id=%s AND request_ref_number=%s",
        GetSQLValueString('00', "text"),
        GetSQLValueString($module_id, "text"),
        $ref_number
    );

    if (!$connect->query($reject_sql_1)) {
        echo 'ERROR 1 <br>';
        echo $connect->error;
    }
    if (!$connect->query($reject_sql_2)) {
        echo 'ERROR 2 <br>';
        echo $connect->error;
    }
}
