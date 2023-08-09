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
$table = 'profile';
 
// Table's primary key
$primaryKey = 'profile_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => '`d`.`profile_id`', 'dt' => 0, 'field' => 'profile_id' ),
    array( 'db' => '`d`.`msisdn`',  'dt' => 1, 'field' => 'msisdn' ),
    array( 'db' => '`d`.`first_name`',   'dt' => 2, 'field' => 'first_name' ),
    array( 'db' => '`d`.`middle_name`',     'dt' => 3, 'field' => 'middle_name'),
    array( 'db' => '`d`.`last_name`',     'dt' => 4, 'field' => 'last_name'),
    array( 'db' => '`d`.`status`',     'dt' => 5, 'field' => 'status' ),
    array( 'db' => '`b`.`profile_balance`',     'dt' => 6, 'field' => 'profile_balance' ),
    array( 'db' => '`b`.`profile_points`',     'dt' => 7, 'field' => 'profile_points' ),
    array( 'db' => '`d`.`network`',     'dt' => 8, 'field' => 'network' ),
    array( 'db' => '`b`.`modified`',     'dt' => 9, 'field' => 'modified' )
    
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

$joinQuery = "FROM `profile` AS `d` JOIN `profile_balance` AS `b` ON (`d`.`profile_id` = `b`.`profile_id`) ";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);