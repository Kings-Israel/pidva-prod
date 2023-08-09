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

$current_user = $_SESSION['MM_full_names'];
$formAction = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

$taxpin_sql = "SELECT * FROM pel_company_taxpin_data WHERE request_ref=$ref_number LIMIT 1";
$taxpin_sql_result = $connect->query($taxpin_sql);
$taxpin_object = $taxpin_sql_result->fetch_assoc();
$rows = $taxpin_sql_result->num_rows;

if ($rows > 0) {
    $tax_obligation_sql = "SELECT * FROM  pel_company_taxpin_obligations WHERE pin_id=" . $taxpin_object['id'] . "";
    $tax_obligation_sql_result = $connect->query($tax_obligation_sql);
    $obligation_rows = $tax_obligation_sql_result->num_rows;
}



function done()
{
    header("Location:" . $GLOBALS['REDIRECT_URL'] . "");
}


if (isset($post['add_taxpayer'])) {
    $sql = sprintf(
        "INSERT INTO pel_company_taxpin_data (pin, taxpayer_name, pin_status,itax_status,status,added_by, request_ref) VALUES (%s,%s,%s,%s,%s,%s,$ref_number)",
        GetSQLValueString($post['pin'], 'text'),
        GetSQLValueString($post['taxpayer_name'], 'text'),
        GetSQLValueString($post['pin_status'], 'text'),
        GetSQLValueString($post['itax_status'], 'text'),
        GetSQLValueString('22', 'text'),
        GetSQLValueString($current_user, 'text')
    );

    $connect->query($sql);
    set_module_status('22', $ref_number);
    echo $ref_number;
    // done();
}

if (isset($post['add_obligation'])) {
    $object = $tax_obligation_sql_result->fetch_assoc();

    $sql = sprintf(
        "INSERT INTO pel_company_taxpin_obligations (obligation_name,effective_from,current_status,effective_to, pin_id, date_added) VALUES (%s,%s,%s,%s,%s,now())",
        GetSQLValueString($post['obligation_name'], 'text'),
        GetSQLValueString($post['effective_from'], 'text'),
        GetSQLValueString($post['current_status'], 'text'),
        GetSQLValueString($post['effective_to'], 'text'),
        $taxpin_object['id']

    );

    set_module_status('22', $ref_number);
    $connect->query($sql);
    done();
} elseif (isset($post['update_taxpayer'])) {
    $sql = sprintf(
        "UPDATE pel_company_taxpin_data SET pin=%s, taxpayer_name=%s, pin_status=%s,itax_status=%s,status=%s WHERE id=" . GetSQLValueString($post['id'], "int") . "",
        GetSQLValueString($post['pin'], 'text'),
        GetSQLValueString($post['taxpayer_name'], 'text'),
        GetSQLValueString($post['pin_status'], 'text'),
        GetSQLValueString($post['itax_status'], 'text'),
        GetSQLValueString('22', 'text')

    );

    $connect->query($sql);

    set_module_status('22', $ref_number);
} elseif (isset($post['addreject'])) {
    $sql = sprintf(
        "UPDATE pel_company_taxpin_data  SET review_notes=%s, review_status='REJECTED', verified_date=%s, verified_by=%s,status='00' WHERE request_ref=$ref_number",
        GetSQLValueString($post['review_notes'], 'text'),
        GetSQLValueString($post['verified_date'], 'text'),
        GetSQLValueString($current_user, 'text')

    );
    if (!$connect->query($sql)) {
        echo $connect->error;
    }
    set_module_status('00', $ref_number);
    done();
} elseif (isset($post['approve'])) {
    $sql = sprintf(
        "UPDATE pel_company_taxpin_data  SET review_status='APPROVED', verified_date=now(), review_notes='Approved',verified_by=%s,status='11' WHERE request_ref=$ref_number",
        GetSQLValueString($current_user, 'text')

    );
    if (!$connect->query($sql)) {
        echo $connect->error;
    }
    set_module_status('11', $ref_number);
    done();
}
