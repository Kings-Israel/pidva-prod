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
$table = 'profile';
 
// Table's primary key
$primaryKey = 'profile_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'profile_id',  'dt' => 0 ),
    array( 'db' => 'msisdn', 'dt' => 1 ),
    array( 'db' => 'first_name',  'dt' => 2 ),
    array( 'db' => 'middle_name', 'dt' => 3 ),
    array( 'db' => 'last_name', 'dt' => 4 ),
    array( 'db' => 'status',  'dt' => 5 ),
    array( 'db' => 'network', 'dt' => 6 ),
    array( 'db' => 'created', 'dt' => 7 ),
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
