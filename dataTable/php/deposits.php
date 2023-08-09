<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

require '../../../config/config.php';
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'deposit';
 
// Table's primary key
$primaryKey = 'deposit_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`d`.`deposit_id`', 'dt' => 0, 'field' => 'deposit_id' ),
	array( 'db' => '`p`.`msisdn`',  'dt' => 1, 'field' => 'msisdn' ),
	array( 'db' => '`d`.`reference`',   'dt' => 2, 'field' => 'reference' ),
	array( 'db' => '`d`.`deposit_amount`',     'dt' => 3, 'field' => 'deposit_amount'),
	array( 'db' => '`d`.`status`',     'dt' => 4, 'field' => 'status'),
	array( 'db' => '`d`.`modified`',     'dt' => 5, 'field' => 'modified' )
);

// SQL server connection information
// SQL server connection information
$sql_details = array(
    'user' => Config::dbUser,
    'pass' => Config::dbPassword,
    'db'   => Config::dbName,
    'host' => Config::dbHost
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `deposit` AS `d` JOIN `profile` AS `p` ON (`d`.`profile_id` = `p`.`profile_id`) ";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);