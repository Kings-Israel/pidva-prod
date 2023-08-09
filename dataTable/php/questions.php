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

require '../../../config/config.php';
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'question';
 
// Table's primary key
$primaryKey = 'question_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'question_id',  'dt' => 0 ),
    array( 'db' => 'question', 'dt' => 1 ),
    array( 'db' => 'answer_one',  'dt' => 2 ),
    array( 'db' => 'answer_two', 'dt' => 3 ),
    array( 'db' => 'correct_answer',  'dt' => 4 ),
    array( 'db' => 'created',  'dt' => 5 )
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
