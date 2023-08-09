<?php
if (!$connect) {
    require('../../../Connections/connect.php');
}

$request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
$moduleid = isset($_GET['moduleid']) ? $_GET['moduleid'] : '';


$request = $connect->query("SELECT * FROM pel_psmt_request WHERE request_id=" . $request_id . "")->fetch_assoc();
$request_ref_number = $request['request_ref_number'];

if ((isset($_POST["invalid"]))) {
    $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET valid='0',status='11' WHERE request_ref_number=%s AND module_id=%s",
        GetSQLValueString($request_ref_number, 'text'),
        $moduleid
    );
    $connect->query($sql);
} elseif ((isset($_POST["valid"]))) {
	
    $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET valid='1' WHERE request_ref_number=%s AND module_id!=%s",
        GetSQLValueString($request_ref_number, 'text'),
        $moduleid
    );
    $connect->query($sql);

  /* $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET valid=NULL, status='00' WHERE request_ref_number=%s AND module_id!=%s",
        GetSQLValueString($request_ref_number, 'text'),
        $moduleid
    );
    $connect->query($sql);*/
}
// check if modules can be accessed
$invalids = $connect->query(
    sprintf(
        "SELECT * FROM pel_psmt_request_modules WHERE request_ref_number=%s AND valid=%s",
        GetSQLValueString($request_ref_number, 'text'),
        GetSQLValueString('0', 'text')
    )
);
$invalid_count = $invalids->num_rows;
$invalid_module_ids = array();

while ($row =  $invalids->fetch_assoc()) {
    $invalid_module_ids[] = $row['module_id'];
}


function show($moduleid)
{
    $invalid_count = $GLOBALS['invalid_count'];
    $invalid_module_ids = $GLOBALS['invalid_module_ids'];
    //  and !($moduleid == '47' or $moduleid == 47)
    if ($invalid_count == 0) {
        return true;
    } elseif ($moduleid == '47' or $moduleid == 47) {
        return true;
    } elseif (in_array($moduleid, $invalid_module_ids)) {
        return true;
    } else {
        return false;
    }
}
function approve_modules($true = true)
{
    $connect = $GLOBALS['connect'];
    $request_ref_number = $GLOBALS['request_ref_number'];
    $moduleid = $GLOBALS['moduleid'];

    $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET status='11',valid='1' WHERE request_ref_number=%s AND module_id!=%s",
        GetSQLValueString($request_ref_number, "text"),
        $moduleid
    );
    if (!$connect->query($sql)) {
        echo $connect->error;
    };
}

function update_module_status($status = '22')
{
    $connect = $GLOBALS['connect'];
    $request_ref_number = $GLOBALS['request_ref_number'];

    $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET status=%s,valid='1' WHERE request_ref_number=%s",
        GetSQLValueString($status, "text"),
        GetSQLValueString($request_ref_number, "text")
    );
    $connect->query($sql);
}

function required_module_data_added()
{
    $connect = $GLOBALS['connect'];
    $request_ref_number = $GLOBALS['request_ref_number'];
    $moduleid = $GLOBALS['moduleid'];

    $sql = sprintf(
        "UPDATE pel_psmt_request_modules SET status=%s,valid='1',valid='1' WHERE request_ref_number=%s AND module_id!=%s",
        GetSQLValueString('11', "text"),
        GetSQLValueString($request_ref_number, "text"),
        $moduleid
    );
    $connect->query($sql);
    // echo $sql;
}
