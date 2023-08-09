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

require '../../config/config.php';
 
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
	array( 'db' => 'attempt_id',  'dt' => 0 ),
    array( 'db' => 'profile_id', 'dt' => 1 ),
    array( 'db' => 'question_id',  'dt' => 2 ),
    array( 'db' => 'answer', 'dt' => 3 ),
    array( 'db' => 'is_correct',  'dt' => 4 ),
    array( 'db' => 'points', 'dt' => 5 ),
    array( 'db' => 'bonus_points',  'dt' => 6 ),
    array( 'db' => 'modified', 'dt' => 7 )
);
 
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
 
require('ssp.class.php');
 
$dd = json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

//$myfile = fopen("log.txt", "w");
//fwrite($myfile, $dd);

echo $dd;
