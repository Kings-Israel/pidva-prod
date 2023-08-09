<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 10/05/20
 * Time: 14:24
 */

require_once '/var/www/html/pidva-dev/uploads.php';
require_once "/var/www/html/psmt-dev/v1/api/vendor/autoload.php";
require_once "/var/www/html/psmt-dev/v1/api/MenuLogger.php";
require_once "/var/www/html/psmt-dev/v1/api/DB.php";
require_once "/var/www/html/psmt-dev/v1/api/Library.php";

$configs = parse_ini_file("/var/www/html/pidva-dev/config/config.ini", true);
$config = json_decode(json_encode($configs));

$region = $config->spaces->region;
$endpoint = $config->spaces->endpoint;
//$bucket = $config->spaces->bucket;

$db = new DB();
$logger = new MenuLogger($config->log);
$x = 0;
$y = 0;
/*
$sql1 = "SELECT psmtfile_id,psmtfile_name FROM pel_psmt_files WHERE psmtfile_name NOT LIKE '/var/www/html/media/%' AND psmtfile_name NOT LIKE 'https://peleza.fra1.digitaloceanspaces.com/%';";
$folders = $db->fetch($sql1);
$y = count($folders);

$sql1 = "SELECT psmtfile_id,psmtfile_name FROM pel_psmt_files WHERE psmtfile_name NOT LIKE 'https://peleza.fra1.digitaloceanspaces.com/%';";
$folders1 = $db->fetch($sql1);
$y = $y + count($folders1);
*/
$sql1 = "SELECT psmtfile_id,psmtfile_name FROM pel_psmt_files WHERE psmtfile_name NOT LIKE 'https://peleza.fra1.digitaloceanspaces.com/%';";
$folders = $db->fetch($sql1);
$y = $y + count($folders);

///
///
$bucket = "psmt-requests";
$query = "UPDATE pel_psmt_files SET psmtfile_name = :url WHERE psmtfile_id = :id ";

/*
foreach ($folders as $folder) {

    $x++;
    $path = urlencode("https://psmt.pidva.africa/datafiles/".$folder->psmtfile_name);

    $logger->INFO("$bucket ==> No. #$x/$y path $path ");

    $n = preg_replace('/[^a-zA-Z0-9_.]/', '', $folder->psmtfile_name);

    $destination = "/var/www/html/pidva-dev/v1/tmp/".$n;

    $dt = file_get_contents($path);

    if(strlen($dt) == 0 ) {

        $i = $folder->psmtfile_id;
        $logger->INFO("$bucket ==>empty file for id $i path $path ");
        continue;
    }

    $status = file_put_contents($destination, $dt);

    if($status == false ) {

        $i = $folder->psmtfile_id;
        $logger->INFO("$bucket ==> empty file for id $i path $path ");
        continue;
    }

    if($status == 0 ) {

        $i = $folder->psmtfile_id;
        $logger->INFO("$bucket ==> empty file for id $i path $path ");
        continue;
    }

    if(filesize($destination) == 0 ) {

         $i = $folder->psmtfile_id;
         $logger->INFO("$bucket ==> empty file for id $i path $path ");
         continue;
     }

    // upload
    try {

        $logger->INFO("$bucket ==> No. #$x/$y migrating $path ");

        $location = uploadFileFromPath($destination, $bucket);

        if($location != false && strlen($location) > 0 ) {

            $params = array(':url' => $location, ':id' => $folder->psmtfile_id);

            try {

                $db->update($query, $params);
                unlink($destination);

            } catch (Exception $e) {

                $logger->ERROR("got errors here " . $e->getMessage());
            }
        }

        $logger->INFO("$bucket ==> No. #$x/$y done migrating $path to  $location ");

    }
    catch (Exception $e) {

        $logger->ERROR("got errors here " . $e->getMessage());

    }

}
*/
foreach ($folders as $folder) {

    $x++;
    $destination = "/home/phil/datafiles/".$folder->psmtfile_name; //str_replace('/var/www/html/media/company/','/home/phil/company/',$folder->psmtfile_name);

    //$path = "https://psmt.pidva.africa/".$parts[0]; //$folder->psmtfile_name; //str_replace("/var/www/html/","https://psmt.pidva.africa/",$folder->psmtfile_name);

    $logger->INFO("$bucket ==> No. #$x/$y path $destination ");

    //$n = preg_replace('/[^a-zA-Z0-9_.]/', '', $folder->psmtfile_id);

    //$destination = "/var/www/html/pidva-dev/v1/tmp/".$n.".png";

    //$dt = file_get_contents(urlencode($path));

   // if(filesize($destination) == 0 ) {

        $i = $folder->psmtfile_id;
   //     $logger->INFO("$bucket ==> empty file for id $i path $destination ");
        //continue;
  //  }


    // upload
    try {

        $logger->INFO("$bucket ==> No. #$x/$y migrating $destination ");

        $location = uploadFileFromPath($destination, $bucket);

        if($location != false && strlen($location) > 0 ) {

            $params = array(':url' => $location, ':id' => $folder->psmtfile_id);

            try {

                $db->update($query, $params);
               // unlink($destination);

            } catch (Exception $e) {

                $logger->ERROR("got errors here " . $e->getMessage());
            }
        }

        $logger->INFO("$bucket ==> No. #$x/$y done migrating $destination to  $location ");

    }
    catch (Exception $e) {

        $logger->ERROR("got errors here " . $e->getMessage());

    }

}