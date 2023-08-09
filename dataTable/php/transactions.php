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
$table = 'transaction';
 
// Table's primary key
$primaryKey = 'transaction_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`t`.`transaction_id`', 'dt' => 0, 'field' => 'transaction_id' ),
	array( 'db' => '`p`.`msisdn`',  'dt' => 1, 'field' => 'msisdn' ),
	array( 'db' => '`t`.`transaction_type_id`',   'dt' => 2, 'field' => 'transaction_type_id' ),
	array( 'db' => '`t`.`transaction_amount`',     'dt' => 3, 'field' => 'transaction_amount'),
	array( 'db' => '`t`.`transaction_reference_id`',     'dt' => 4, 'field' => 'transaction_reference_id'),
	array( 'db' => '`t`.`status`',     'dt' => 5, 'field' => 'status' ),
	array( 'db' => '`t`.`created`',     'dt' => 6, 'field' => 'created' )
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

$joinQuery = "FROM `transaction` AS `t` JOIN `profile` AS `p` ON (`t`.`profile_id` = `p`.`profile_id`) ";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);