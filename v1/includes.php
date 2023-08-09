<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 01/04/20
 * Time: 10:29
 */

ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$root = "/var/www/html/pidva-dev";

require_once "$root/vendor/autoload.php";
require_once "$root/v1/MenuLogger.php";
require_once "$root/v1/DB.php";
require_once "$root/v1/Library.php";
require_once "$root/v1/Searches.php";
require_once "$root/v1/Notifier.php";
require_once "$root/v1/Institution.php";
require_once "$root/v1/Education.php";
require_once "$root/v1/faculty.php";
require_once "$root/v1/Course.php";
require_once "$root/v1/Specilalization.php";
require_once "$root/v1/Vehicle.php";