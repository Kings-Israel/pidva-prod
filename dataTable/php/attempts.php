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
$table = 'attempt';
 
// Table's primary key
$primaryKey = 'attempt_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`a`.`attempt_id`', 'dt' => 0, 'field' => 'attempt_id' ),
	array( 'db' => '`p`.`msisdn`',  'dt' => 1, 'field' => 'msisdn' ),
	array( 'db' => '`q`.`question`',   'dt' => 2, 'field' => 'question' ),
	array( 'db' => '`a`.`answer`',     'dt' => 3, 'field' => 'answer'),
	array( 'db' => '`a`.`is_correct`',     'dt' => 4, 'field' => 'is_correct','formatter' => function( $d, $row ) {
																	return $d == 1 ? "YES" : "NO";
																}),
	array( 'db' => '`a`.`points`',     'dt' => 5, 'field' => 'points' ),
	array( 'db' => '`a`.`bonus_points`', 'dt' => 6, 'field' => 'bonus_points'),
	array('db'  => '`a`.`modified`',     'dt' => 7, 'field' => 'modified' )
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

$joinQuery = "FROM `attempt` AS `a` JOIN `profile` AS `p` ON (`a`.`profile_id` = `p`.`profile_id`) JOIN `question` AS `q` ON (`a`.`question_id` = `q`.`question_id` )";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);