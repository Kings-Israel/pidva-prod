<?php



$table = 'pel_individual_id';

// Table's primary key
$primaryKey = 'identity_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
    array('db' => '`a`.`identity_id`', 'dt' => 0, 'field' => 'identity_id'),
    array('db' => '`a`.`token_number`', 'dt' => 1, 'field' => 'token_number'),
    array('db' => '`a`.`identity_number`', 'dt' => 2, 'field' => 'identity_number'),
    array('db' => '`a`.`identity_type`', 'dt' => 3, 'field' => 'identity_type'),
    array('db' => '`a`.`identity_name`', 'dt' => 4, 'field' => 'identity_name'),
    array('db' => '`a`.`date_of_birth`', 'dt' => 5, 'field' => 'date_of_birth'),
    array('db' => '`a`.`gender`', 'dt' => 6, 'field' => 'gender'),
    array('db' => '`a`.`individual_id`', 'dt' => 7, 'field' => 'individual_id'),
    array('db' => '`a`.`date_added`', 'dt' => 8, 'field' => 'date_added'),
    array('db' => 'CONCAT(`a`.`iprs_photo`,"__",`a`.`identity_number`) as photo', 'dt' => 9, 'field' => 'photo', 'formatter' => function ($d, $row) {

        $parts = explode("__", $d);
        if ($d == "" || strlen($d) == 0) {
            return 'No Photo';
        } elseif (isset($parts[0]) && $parts[0] != '' && isset($parts[1]) && $parts[1] != '') {

            $img_prefix = 'https://peleza.fra1.digitaloceanspaces.com/searches-photos/';
            $identity_number = $parts[1];

            return "<a href='#modal-viewimage-" . $identity_number . " data-toggle='modal' >
                        <img src='" . $img_prefix . trim($identity_number) . ".png' width='80px' height='80px' alt='Failed to load'/>
                    </a>";
        }

        return 'No Photo';
    }),
    array('db' => '`a`.`status`', 'dt' => 10, 'field' => 'status', 'formatter' => function ($d, $row) {

        switch ($d) {

            case "44":
                return '<span class="label label-sm label-warning">In Progress</span>';
                break;

            case "00":
                return '<span class="label label-sm label-purple">New Request</span>';
                break;

            case "11":
                return '<span class="label label-sm label-success">Final</span>';
                break;

            case "33":
                return '<span class="label label-sm label-success">Interim</span>';
                break;


            case "22":
                return '<span class="label label-sm label-danger">NO DATA</span>';
                break;
        }

        return $d;
    }),
);


//SELECT * FROM pel_individual_id WHERE status IN ('00','33','44','22','11') and identity_id > 3500 ORDER BY identity_id DESC

// SQL server connection information
// SQL server connection information
$sql_details = array(
    'user' => "root",
    'pass' => "p3l3z@1234",
    'db' => "peleza_db_local",
    'host' => "46.101.16.235"
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php');

$joinQuery = "FROM `attempt` AS `a` JOIN `profile` AS `p` ON (`a`.`profile_id` = `p`.`profile_id`) JOIN `question` AS `q` ON (`a`.`question_id` = `q`.`question_id` )";
$joinQuery = "FROM $table a";

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery)
);
