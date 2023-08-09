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

$folders = array(
    "company-logoimages" => array(
        "path"=>"/var/www/html/pidva/html/clients/logoimages/",
        "query" => "UPDATE pel_client_co SET company_logo = :new_name WHERE company_logo = :old_name "),
    "company-licensephotos" => array(
        "path"=>"/var/www/html/pidva/html/company/licensephotos/",
        "query" => "UPDATE pel_company_license SET license_photo = :new_name WHERE license_photo = :old_name "),
    "company-customerreference" => array(
        "path"=>"/var/www/html/pidva/html/company/customerreference/",
        "query" => "UPDATE pel_company_customer_ref SET reference_document = :new_name WHERE reference_document = :old_name "),
    "company-membershipcertificate" => array(
        "path"=>"/var/www/html/pidva/html/company/membershipcertificate/",
        "query" => "UPDATE pel_data_proff_membership SET membership_certificate = :new_name WHERE membership_certificate = :old_name "),
    "company-sitevisit" => array(
        "path"=>"/var/www/html/pidva/html/company/sitevisit/",
        "query" => "UPDATE pel_data_residence SET building_photo = :new_name WHERE building_photo = :old_name "),
    "company-socialmediaphotos" => array(
        "path"=>"/var/www/html/pidva/html/company/socialmediaphotos/",
        "query" => "UPDATE pel_data_social_media SET photo = :new_name WHERE photo = :old_name "),
    "company-taxcompliance" => array(
        "path"=>"/var/www/html/pidva/html/company/taxcompliance/",
        "query" => "UPDATE pel_company_tax_data SET tax_photo = :new_name WHERE tax_photo = :old_name "),
    "company-watchlistphotos" => array(
        "path"=>"/var/www/html/pidva/html/company/watchlistphotos/",
        "query" => "UPDATE pel_company_watchlist_data SET photo = :new_name WHERE photo = :old_name "),
    "institution-logoimages" => array(
        "path"=>"/var/www/html/pidva/html/education/logoimages/",
        "query" => "UPDATE pel_edu_institution SET inst_logo = :new_name WHERE inst_logo = :old_name "),
    "individual-fingerprint" => array(
        "path"=>"//var/www/html/pidva/html/individual/fingerprint/",
        "query" => array(
            "UPDATE pel_individual_fprint_data SET finger_print_pel = :new_name WHERE finger_print_pel = :old_name ",
            "UPDATE pel_individual_fprint_data SET finger_print_src = :new_name WHERE finger_print_src = :old_name ",
            "UPDATE pel_individual_criminal_data SET finger_print_pel = :new_name WHERE finger_print_pel = :old_name ",
            "UPDATE pel_individual_criminal_data SET finger_print_src = :new_name WHERE finger_print_src = :old_name "),
    ),
    "individual-dlphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/dlphotos/",
        "query" => "UPDATE pel_individual_dl_data SET dl_photo = :new_name WHERE dl_photo = :old_name "),
    "individual-educationcertificates" => array(
        "path"=>"/var/www/html/pidva/html/individual/educationcertificates/",
        "query" => "UPDATE pel_psmt_edu_data SET certificate_photo = :new_name WHERE certificate_photo = :old_name "),
    "individual-employementreference" => array(
        "path"=>"/var/www/html/pidva/html/individual/employementreference/",
        "query" => "UPDATE pel_psmt_employ_data SET employment_reference_photo = :new_name WHERE employment_reference_photo = :old_name "),
    "individual-identityphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/identityphotos/",
        "query" => array(
            "UPDATE pel_individual_id SET identity_holder_photo = :new_name WHERE identity_holder_photo = :old_name ",
            "UPDATE pel_individual_id SET identity_photo = :new_name WHERE identity_photo = :old_name ",)
    ),
    "individual-individualpassportphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/individualpassportphotos/",
        "query" => array(
            "UPDATE pel_individual_id SET identity_holder_photo = :new_name WHERE identity_holder_photo = :old_name ",
            "UPDATE pel_individual_id SET identity_photo = :new_name WHERE identity_photo = :old_name ",)
    ),
    "individual-membershipcertificate" => array(
        "path"=>"/var/www/html/pidva/html/individual/individualpassportphotos/",
        "query" => "UPDATE pel_data_proff_membership SET membership_certificate = :new_name WHERE membership_certificate = :old_name "),
    "individual-psvphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/psvphotos/",
        "query" => "UPDATE pel_individual_psv_data SET psv_photo = :new_name WHERE psv_photo = :old_name "),
    "individual-residencephotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/residencephotos/",
        "query" => "UPDATE pel_data_residence SET building_photo = :new_name WHERE building_photo = :old_name "),
    "individual-socialmediaphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/socialmediaphotos/",
        "query" => "UPDATE pel_data_social_media SET photo = :new_name WHERE photo = :old_name "),
    "individual-taxcompliance" => array(
        "path"=>"/var/www/html/pidva/html/individual/taxcompliance/",
        "query" => "UPDATE pel_individual_tax_data SET tax_photo = :new_name WHERE tax_photo = :old_name "),
    "individual-taxpinphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/taxpinphotos/",
        "query" => "UPDATE pel_individual_pin_data SET tax_photo = :new_name WHERE tax_photo = :old_name "),
    "individual-watchlistphotos" => array(
        "path"=>"/var/www/html/pidva/html/individual/watchlistphotos/",
        "query" => "UPDATE pel_individual_watchlist_data SET photo = :new_name WHERE photo = :old_name "),
    "searches-reportfiles" => array(
        "path"=>"/var/www/html/pidva/html/searches/reportfiles/",
        "query" => "UPDATE pel_psmt_request SET report_file = :new_name WHERE report_file = :old_name "),
    "user-userimages" => array(
        "path"=>"/var/www/html/pidva/html/user/userimages/",
        "query" => "UPDATE pel_users SET USR_PHOTO = :new_name WHERE USR_PHOTO = :old_name "),
);

$folders1 = array(
    "searches-photos" => array(
        "path"=>"/var/www/html/pidva/html/searches/photos/",
        "query" => "UPDATE pel_psmt_files SET psmtfile_name = :new_name WHERE psmtfile_name = :old_name "
    )
);

$folders = array(
    "searches-datafiles" => array(
        "path"=>"/var/www/html/pidva/html/searches/",
        "query" => "UPDATE pel_psmt_files SET psmtfile_name = :new_name WHERE psmtfile_name = :old_name "
    ),
);


$configs = parse_ini_file("/var/www/html/pidva-dev/config/config.ini", true);
$config = json_decode(json_encode($configs));

$region = $config->spaces->region;
$endpoint = $config->spaces->endpoint;
//$bucket = $config->spaces->bucket;

$db = new DB();
$logger = new MenuLogger($config->log);
$x = 0;

foreach ($folders as $bucket=>$folder) {

    $path = $folder['path'];
    $query = $folder['query'];

    $filepath = "$endpoint/" . $bucket;

    $files = scandir($path);

    foreach($files as $file) {

        $x++;

        if (is_file($path . $file)) {

            // upload
            try {

                $logger->INFO("$bucket ==> No. #$x migrating $path$file ");

                $location = uploadFileFromPath($path . $file, $bucket);

                if($location != false && strlen($location) > 0 ) {
                 
                    $params = array(':old_name' => $file, ':new_name' => $location);

                    try {

                        if (is_array($query)) {

                            foreach ($query as $sql) {

                                $db->update($sql, $params);

                            }
                        } else {

                            $db->update($query, $params);
                        }

                    } catch (Exception $e) {

                        $logger->ERROR("got errors here " . $e->getMessage());
                    }
                }

                $logger->INFO("$bucket ==> No. #$x done migrating $path.$file to  $location ");

            }
            catch (Exception $e) {

                $logger->ERROR("got errors here " . $e->getMessage());

            }
        }
    }


}