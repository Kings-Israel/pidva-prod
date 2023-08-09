<?php require('../../Connections/connect.php');
ini_set('display_errors', '1');

include_once('./vendor/spaces-api/spaces.php');

$img_prefix = 'https://peleza.fra1.digitaloceanspaces.com/';
$img_long_prefix = $img_prefix . 'individual-educationcertificates/';

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
	{
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

		$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect, $theValue) : mysqli_escape_string($connect, $theValue);

		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$colname_getrequestid = "-1";
if (isset($_GET['request_id'])) {
	$colname_getrequestid = $_GET['request_id'];
}
$colname_getmoduleid = "-1";
if (isset($_GET['moduleid'])) {
	$colname_getmoduleid = $_GET['moduleid'];
}

$errorcode = '';
$certificate_photo_url = '';


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "checkindb")) {

	// 
	if (is_uploaded_file($_FILES['certificate_photo']['tmp_name'])) {
		date_default_timezone_set('Africa/Nairobi');
		$date_insert = date('dmYhis');
		$a = "ED-" . $_POST['student_token'] . "-" . $_POST['search_id'] . "-" . $date_insert;
		$file_name = $a . "_" . $_FILES["certificate_photo"]["name"];

		$spaceName = 'individual-educationcertificates';

		$space = Spaces('L5GDPLSXS7VOIBRHDPXP', 'tQ2tBJg6kx5saEIyalSxCx1u39StDracsbPBMRGoOOE')->space('peleza', 'fra1');
		$space->uploadFile(
			$_FILES["certificate_photo"]["tmp_name"],
			$spaceName . '/' . $file_name,
			'public'
		);
		$filenameuploaded = $img_long_prefix . $file_name;
	} else {
		$filenameuploaded = isset($row_getstudent['certificate_photo']) ? $row_getstudent['certificate_photo'] : '';
	}

	if (isset($_POST['student_token'])) {
		$student_token = strtoupper($_POST['student_token']);

		// mysqli_select_db($connect, $database_connect);

		$datetoday = date('Y-m-d');
		// if (isset())

		$query_getstudent = "SELECT * FROM pel_edu_data WHERE student_token = '$student_token' and student_status = '11'";
		$getstudent = mysqli_query_ported($query_getstudent, $connect) or die(mysqli_error($connect));
		$row_getstudent = mysqli_fetch_assoc($getstudent);
		$totalRows_getstudent = mysqli_num_rows($getstudent);

		if ($totalRows_getstudent > 0) {
			$name = $row_getstudent['student_first_name'] . " " . $row_getstudent['student_second_name'] . " " . $row_getstudent['student_third_name'];

			$updateSQL = sprintf(
				"UPDATE pel_psmt_edu_data SET edu_name=%s, edu_institution=%s, status=%s, date_added=%s, added_by=%s, edu_course=%s, edu_specialization=%s,data_source=%s, edu_award=%s, edu_graduation_year=%s, certificate_photo=%s, data_notes=%s, student_token=%s WHERE edu_id=%s",
				GetSQLValueString($name, "text"),
				GetSQLValueString(strtoupper($row_getstudent['institution_name']), "text"),
				GetSQLValueString($_POST['status'], "text"),
				GetSQLValueString($_POST['date_added'], "text"),
				GetSQLValueString($_POST['added_by'], "text"),
				GetSQLValueString($row_getstudent['course_name'], "text"),
				GetSQLValueString($row_getstudent['student_specialization'], "text"),
				GetSQLValueString($row_getstudent['data_source'], "text"),
				GetSQLValueString($row_getstudent['award'], "text"),
				GetSQLValueString($row_getstudent['graduation_date'], "text"),
				GetSQLValueString($filenameuploaded ? $filenameuploaded : $certificate_photo_url, "text"),
				GetSQLValueString($_POST['data_notes'], "text"),
				GetSQLValueString($student_token, "text"),
				GetSQLValueString($_POST['edu_id'], "int")
			);


			$colname_getrequestid = $_POST['request_id'];
			$colname_getmoduleid = $_POST['moduleid'];

			if (!$connect->query($updateSQL)) {
				echo $connect->error . '<br>';
				echo $updateSQL;
				return
					$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the Student Data havent been added.
											<br />
										</div>';
			} else {
				$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
				header(sprintf("Location: %s", $updateGoTo));
			}
		} else {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 No Details of Student found Kindly go and Upload Education Details
											<br />
										</div>';
		}
	} else {
		$sql = sprintf(
			"UPDATE pel_psmt_edu_data SET edu_name=NULL, edu_institution=NULL, status=NULL, date_added=NULL, added_by=NULL, edu_course=NULL, edu_specialization=NULL,data_source=NULL, edu_award=NULL, edu_graduation_year=NULL, certificate_photo=%s, data_notes=%s, student_token=NULL WHERE edu_id=%s",
			GetSQLValueString($filenameuploaded ? $filenameuploaded : $certificate_photo_url, "text"),
			GetSQLValueString($_POST['data_notes'], "text"),
			GetSQLValueString($_POST['edu_id'], "int")
		);

		if (!$connect->query($sql)) {
			echo $connect->error;
			return;
		}
	}
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editmatchdetails")) {

	$updateSQL = sprintf(
		"UPDATE pel_psmt_edu_data SET match_status_name=%s, status=%s, date_added=now(), added_by=%s, match_status_insititution=%s, match_status_course=%s,match_status_specialization=%s, match_status_award=%s, match_status_year=%s WHERE edu_id=%s",
		GetSQLValueString(strtoupper($_POST['match_status_name']), "text"),
		GetSQLValueString($_POST['status'], "text"),
		GetSQLValueString($_POST['added_by'], "text"),
		GetSQLValueString(strtoupper($_POST['match_status_insititution']), "text"),
		GetSQLValueString(strtoupper($_POST['match_status_course']), "text"),
		GetSQLValueString(strtoupper($_POST['match_status_specialization']), "text"),
		GetSQLValueString(strtoupper($_POST['match_status_award']), "text"),
		GetSQLValueString(strtoupper($_POST['match_status_year']), "text"),
		GetSQLValueString($_POST['edu_id'], "int")
	);

	mysqli_select_db($connect, $database_connect);
	mysqli_query_ported($updateSQL, $connect);

	$colname_getrequestid = $_POST['request_id'];
	$colname_getmoduleid = $_POST['moduleid'];

	if (mysqli_error($connect)) {
		$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Details of the Data Provided were not updated succesfully.
											<br />
										</div>';
	} else {

		$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editdetails")) {

 $updateSQL = sprintf(
		"UPDATE pel_psmt_edu_data SET name_provided=%s, status=%s, date_added=now(), added_by=%s, education_level=%s,institution_provided=%s, data_source_provided=%s, course_provided=%s, specialization_provided=%s,award_provided=%s, year_provided=%s, country=%s WHERE edu_id=%s",
		GetSQLValueString(strtoupper($_POST['name_provided']), "text"),
		GetSQLValueString($_POST['status'], "text"),
		GetSQLValueString($_POST['added_by'], "text"),
		GetSQLValueString($_POST['education_level'], "text"),
		GetSQLValueString(strtoupper($_POST['institution_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['data_source_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['course_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['specialization_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['award_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['year_provided']), "text"),
		GetSQLValueString($_POST['country'], "text"),
		GetSQLValueString($_POST['edu_id'], "int")
	);

	mysqli_select_db($connect, $database_connect);
	mysqli_query_ported($updateSQL, $connect);



	$colname_getrequestid = $_POST['request_id'];
	$colname_getmoduleid = $_POST['moduleid'];

	if (mysqli_error($connect)) {
		$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Details of the Data Provided were not updated succesfully.
											<br />
										</div>';
	} else {

		$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newdetails")) {

	$insertSQL = sprintf(
		"INSERT INTO pel_psmt_edu_data (name_provided, status, date_added, added_by, institution_provided, data_source_provided, course_provided, specialization_provided,award_provided, year_provided, country, search_id) VALUES (%s, %s, now(), %s, %s,%s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString(strtoupper($_POST['name_provided']), "text"),
		GetSQLValueString($_POST['status'], "text"),
		GetSQLValueString($_POST['added_by'], "text"),
		GetSQLValueString(strtoupper($_POST['institution_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['data_source_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['course_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['specialization_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['award_provided']), "text"),
		GetSQLValueString(strtoupper($_POST['year_provided']), "text"),
		GetSQLValueString($_POST['country'], "text"),
		GetSQLValueString($_POST['search_id'], "text")
	);

	mysqli_select_db($connect, $database_connect);
	mysqli_query_ported($insertSQL, $connect);
	$colname_getrequestid = $_POST['request_id'];
	$colname_getmoduleid = $_POST['moduleid'];

	//echo $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
	if (mysqli_error($connect)) {
		$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the Provided Data havent been added.
											<br />
										</div>';
	} else {
		$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addreject")) {

	$deleteSQLreject = sprintf(
		"UPDATE pel_psmt_edu_data SET status=%s, review_status=%s, verified_by=%s, verified_date=now(), review_notes=%s WHERE search_id=%s and edu_id=%s",
		GetSQLValueString('00', "text"),
		GetSQLValueString($_POST['review_status'], "text"),
		GetSQLValueString($_POST['verified_by'], "text"),
		GetSQLValueString($_POST['review_notes'], "text"),
		GetSQLValueString($_POST['search_id'], "int"),
		GetSQLValueString($_POST['edu_id'], "int")
	);
	mysqli_select_db($connect, $database_connect);
	$Result1reject = mysqli_query_ported($deleteSQLreject, $connect) or die(mysqli_error($connect));


	$deleteSQL3 = sprintf(
		"UPDATE pel_psmt_request_modules SET status=%s WHERE module_id=%s AND request_ref_number=%s",
		GetSQLValueString('00', "text"),
		GetSQLValueString($colname_getmoduleid, "text"),
		GetSQLValueString($_POST['search_id'], "int")
	);
	mysqli_select_db($connect, $database_connect);
	$Result3 = mysqli_query_ported($deleteSQL3, $connect) or die(mysqli_error($connect));


	$colname_getrequestid = $_POST['request_id'];
	$colname_getmoduleid = $_POST['moduleid'];

	//echo $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
	if (mysqli_error($connect)) {
		$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Reject Review details not added Error!.
											<br />
										</div>';
	} else {
		$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}




if ((isset($_GET['search_id_approve'])) && ($_GET['search_id_approve'] != "")) {
	if ($_GET['status'] == '11') {


		$deleteSQL2 = sprintf(
			"UPDATE pel_psmt_edu_data SET status=%s, review_status=%s, verified_by=%s, verified_date=now() WHERE search_id=%s and edu_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString("APPROVED", "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['search_id_approve'], "text"),
			GetSQLValueString($_GET['edu_id'], "int")
		);
		mysqli_select_db($connect, $database_connect);
		$Result2 = mysqli_query_ported($deleteSQL2, $connect) or die(mysqli_error($connect));



		$deleteSQL3 = sprintf(
			"UPDATE pel_psmt_request_modules SET status=%s WHERE module_id=%s AND request_ref_number=%s",
			GetSQLValueString('11', "text"),
			GetSQLValueString($colname_getmoduleid, "text"),
			GetSQLValueString($_GET['search_id_approve'], "text")
		);
		mysqli_select_db($connect, $database_connect);
		$Result3 = mysqli_query_ported($deleteSQL3, $connect) or die(mysqli_error($connect));

		if (mysqli_error($connect)) {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the data havent been approved
											<br />
										</div>';
		} else {
			$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/


			header(sprintf("Location: %s", $updateGoTo));
		}
	}
} elseif ((isset($_GET['mark_as_negative'])) && (isset($_GET['edu_id']))) {
	$mark_as_negative = $_GET['mark_as_negative'];
	$extra_sql = "";
	$verification_status = '';

	if ($mark_as_negative == '1') {
		$verification_status = '-1';
	} elseif ($mark_as_negative == '2') {
		$verification_status = '-2';
	} else {
		$verification_status = '1';
	}

	$deleteSQL2 = sprintf(
		"UPDATE pel_psmt_edu_data SET verification_status=%s %s WHERE edu_id=%s",
		GetSQLValueString($verification_status, 'int'),
		'',
		GetSQLValueString($_GET['edu_id'], 'int')
	);
	echo $deleteSQL2 . " " . $verification_status . "\n" . $mark_as_negative . " " . boolval($mark_as_negative);
	$Result1 = mysqli_query_ported($deleteSQL2, $connect) or die(mysqli_error($connect));
	// if (!$connect->query($deleteSQL2)) {
	// 	echo 'error';
	// }
	$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
	header(sprintf("Location: %s", $updateGoTo));
} elseif ((isset($_GET['edu_id'])) && ($_GET['edu_id'] != "")) {
	if ($_GET['status'] == '00') {
		$deleteSQL = sprintf(
			"UPDATE pel_psmt_edu_data SET status=%s, added_by=%s, date_added=now() WHERE edu_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['edu_id'], "int")
		);
		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));



		if (mysqli_error($connect)) {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the Status were not updated havent been added.
											<br />
										</div>';
		} else {
			$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			header(sprintf("Location: %s", $updateGoTo));
		}
	}

	if ($_GET['status'] == '22') {
		$deleteSQL = sprintf(
			"UPDATE pel_psmt_edu_data SET status=%s, added_by=%s, date_added =now() WHERE edu_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['edu_id'], "int")
		);
		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

		if (mysqli_error($connect)) {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the DL data havent been added.
											<br />
										</div>';
		} else {
			$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";

			header(sprintf("Location: %s", $updateGoTo));
		}
	}
} elseif ((isset($_GET['edu_id'])) && ($_GET['edu_id'] != "")) {
	if ($_GET['status'] == '00') {
		$deleteSQL = sprintf(
			"UPDATE pel_psmt_edu_data SET status=%s, added_by=%s, date_added=now() WHERE edu_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['edu_id'], "int")
		);
		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

		if (mysqli_error($connect)) {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the comments have not been updated.
											<br />
										</div>';
		} else {
			$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}

	if ($_GET['status'] == '22') {
		$deleteSQL = sprintf(
			"UPDATE pel_psmt_edu_data SET status=%s, added_by=%s, date_added=now() WHERE edu_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['edu_id'], "int")
		);
		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

		if (mysqli_error($connect)) {
			$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

										 Details of the data notes havenot been blocked
											<br />
										</div>';
		} else {
			$updateGoTo = "educationcheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}
} else {
	// echo 'none';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Individual Education Details Data Management - Peleza Admin</title>

	<meta name="description" content="Static &amp; Dynamic Tables" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="../../assets/css/bootstrap.css" />
	<link rel="stylesheet" href="../../assets/css/font-awesome.css" />

	<!-- page specific plugin styles -->
	<link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css" />
	<link rel="stylesheet" href="../../assets/css/chosen.css" />
	<link rel="stylesheet" href="../../assets/css/datepicker.css" />
	<link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css" />
	<link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
	<link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css" />
	<link rel="stylesheet" href="../../assets/css/colorpicker.css" />

	<link rel="stylesheet" href="../../assets/css/ace-fonts.css" />
	<link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
	<script src="../../assets/js/ace-extra.js"></script>

</head>

<body class="no-skin">
	<!-- #section:basics/navbar.layout -->
	<div id="navbar" class="navbar navbar-default">
		<script type="text/javascript">
			try {
				ace.settings.check('navbar', 'fixed')
			} catch (e) {}
		</script>
		<?php include('../header2.php'); ?>
	</div>

	<!-- /section:basics/navbar.layout -->
	<div class="main-container" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.check('main-container', 'fixed')
			} catch (e) {}
		</script>

		<!-- #section:basics/sidebar -->
		<div id="sidebar" class="sidebar responsive">
			<script type="text/javascript">
				try {
					ace.settings.check('sidebar', 'fixed')
				} catch (e) {}
			</script>
			<?php include('../sidebarmenu2.php'); ?>


			<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
				<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
			</div>

			<script type="text/javascript">
				try {
					ace.settings.check('sidebar', 'collapsed')
				} catch (e) {}
			</script>

		</div>

		<!-- /section:basics/sidebar -->
		<div class="main-content">
			<div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try {
							ace.settings.check('breadcrumbs', 'fixed')
						} catch (e) {}
					</script>

					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="#">Home</a>
						</li>

						<li>
							<a href="#">Peleza Modules</a>
						</li>

						<li>
							<a href="#">Individual</a>
						</li>

						<li class="active">Individual Education Details</li>
					</ul><!-- /.breadcrumb -->

					<div class="nav-search" id="nav-search">
					</div>

				</div>

				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-12">


									<h3 align="left" class="header smaller lighter blue">Add Individual Education Details</h3>
								</div>

								<?php
								$query_getstudent = "SELECT * FROM pel_psmt_request WHERE request_id = " . $colname_getrequestid . "";
								$getstudent = mysqli_query_ported($query_getstudent, $connect) or die(mysqli_error($connect));
								$row_getstudent = mysqli_fetch_assoc($getstudent);
								$totalRows_getstudent = mysqli_num_rows($getstudent);

								// 

								?>

								<h3 align="left" class=" smaller lighter blue"><strong>SEARCH REF: </strong> <?php echo $row_getstudent['request_ref_number']; ?></h3>

								<div>
									<table id="simple-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>

												<th>Dataset Name</th>
												<th>Client Name</th>

												<th>Request Package</th>
												<th>Request Date</th>

												<th class="hidden-480">Status</th>

											</tr>
										</thead>

										<tbody>
											<tr>
												<td>
													<a href="#"><?php echo $row_getstudent['bg_dataset_name']; ?></a>
												</td>
												<td><?php echo $row_getstudent['client_name']; ?></td>

												<td><?php echo $row_getstudent['request_plan']; ?></td>
												<td><?php echo $row_getstudent['request_date']; ?></td>

												<td class="hidden-480"> <?php

																		if ($row_getstudent['verification_status'] == '44') {
																		?>
														<span class="label label-sm label-warning">In Progress</span>
													<?php
																		}
																		if ($row_getstudent['verification_status'] == '00') {
													?>
														<span class="label label-sm label-purple">New Request</span>
													<?php
																		}
																		if ($row_getstudent['verification_status'] == '11') {
													?>
														<span class="label label-sm label-success">Final</span>
													<?php
																		}
																		if ($row_getstudent['verification_status'] == '22') {
													?>
														<span class="label label-sm label-warning">Not Reviewed</span>
													<?php
																		}
																		if ($row_getstudent['verification_status'] == '33') {
													?>
														<span class="label label-sm label-primary">Interim Data</span>
													<?php
																		}
													?>
												</td>



											</tr>



										</tbody>
									</table>


									<a href="individualdataentry.php?request_id=<?php echo $row_getstudent['request_id']; ?>" role="button" class="green">
										<button class="btn btn-xs btn-primary">
											<i class="ace-icon smaller-80 green"></i>Go Back
										</button></a>
									<hr />
									<div class="col-xs-12">
										<?php

										echo $errorcode;
										?>
									</div>

									<?php

									$search_ref = $row_getstudent['request_ref_number'];
									$query_getdetails = "SELECT * FROM pel_psmt_edu_data WHERE search_id = '" . $search_ref . "' order by edu_graduation_year ASC";
									$getdetails  = mysqli_query_ported($query_getdetails, $connect) or die(mysqli_error($connect));

									?>



									<div class="col-lg-12" align="center">
										<h3 align="left" class=" smaller lighter blue"><strong>EDUCATION CHECK DETAILS: </strong>
										</h3>

										<?php
										while ($row_getdetails = mysqli_fetch_assoc($getdetails)) {
											$student_token = $row_getdetails['student_token'];
											$edu_id = $row_getdetails['edu_id'];
											$certificate_photo_url = $row_getdetails['certificate_photo'];
											$certificate_photo = $row_getdetails['certificate_photo'];
											$verification_status = $row_getdetails['verification_status'];
											$is_negative = 'default';
											if ($verification_status == '-1' || $verification_status == -1) {
												$is_negative = 'negative';
											} elseif ($verification_status == '-2' || $verification_status == -2) {
												$is_negative = 'ongoing';
											}
										?>


											<h2 align="left" class=" smaller lighter blue"><strong>COURSE NAME: </strong> <?php echo $row_getdetails['edu_course']; ?>
											</h2>


											<table id="simple-table" class="table table-bordered table-hoverx <?php echo $is_negative; ?>
											">
												<thead>
													<tr>

														<td colspan="2"><strong>Status</strong></td>

														<td colspan="2"><?php

																		if ($row_getdetails['status'] == '11') {
																		?>

																<span class="label label-sm label-success">Valid Data</span>
															<?php
																		}
																		if ($row_getdetails['status'] == '00') {
															?>
																<span class="label label-sm label-danger">Not Correct Data</span>
															<?php
																		}
																		if ($row_getdetails['status'] == '22') {
															?>
																<span class="label label-sm label-warning">Not Reviewed</span>
															<?php
																		}
																		if ($row_getdetails['status'] == '33') {
															?>
																<span class="label label-sm label-primary">Raw Data</span>
															<?php
																		}
																		if ($row_getdetails['status'] == '44') {
															?>
																<span class="label label-sm label-primary">Match Status Not Updated</span>
															<?php
																		}
															?>
														</td>

													</tr>
													<tr>
														<td><strong>Data Set</strong></td>
														<td><strong>Details Provided</strong>&nbsp;&nbsp;<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-editdetails-<?php echo $row_getdetails['edu_id']; ?>" role="button" class="green" data-toggle="modal">
																<button class="btn btn-xs btn-info">
																	<i class="ace-icon fa fa-pencil bigger-120"></i> </button></a>

															<div id="modal-editdetails-<?php echo $row_getdetails['edu_id']; ?>" class="modal fade" tabindex="-1">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header no-padding">
																			<div class="table-header">
																				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																					<span class="white">&times;</span> </button>
																				Edit Details
																			</div>
																		</div>

																		<div class="modal-body padding">
																			<form enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editdetails">
																				<input type="hidden" id="request_id" name="request_id" value="<?php echo $colname_getrequestid; ?>" />
																				<input type="hidden" id="search_id" name="search_id" value="<?php echo $search_ref; ?>" />

																				<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $colname_getmoduleid; ?>" />
																				<input type="hidden" id="edu_id" name="edu_id" value="<?php echo $row_getdetails['edu_id']; ?>" />

																				<input type="hidden" id="status" name="status" value="33" />
																				<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
																				<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />

																				<div class="space-10"></div>


																				<label class="col-sm-4">Education Level</label>

																				<div class="col-sm-8"><span id="sprytextfield1">
																						<input type="text" class="form-control" id="education_level" name="education_level" value="<?php $education_level = $row_getdetails['education_level'];
																																													echo strlen($education_level) > 0 ? $education_level : 'HIGHEST EDUCATION' ?>" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>


																				<br />
																				<div class="space-10"></div>

																				<label class="col-sm-4">Name Provided</label>

																				<div class="col-sm-8"><span id="sprytextfield1">
																						<input type="text" class="form-control" id="name_provided" name="name_provided" value="<?php echo $row_getdetails['name_provided']; ?>" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>

																				<label class="col-sm-4">Institution Provided</label>

																				<div class="col-sm-8"><span id="sprytextfield4">
																						<input type="text" class="form-control" value="<?php echo $row_getdetails['institution_provided']; ?>" id="institution_provided" name="institution_provided" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>

																				<label class="col-sm-4">Course Provided</label>

																				<div class="col-sm-8"><span id="sprytextfield4">
																						<input type="text" class="form-control" value="<?php echo $row_getdetails['course_provided']; ?>" id="course_provided" name="course_provided" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>

																				<label class="col-sm-4">Course Specialization Provided</label>

																				<div class="col-sm-8"><span id="sprytextfield4">
																						<input type="text" class="form-control" value="<?php echo $row_getdetails['specialization_provided']; ?>" id="specialization_provided" name="specialization_provided" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Award Provided</label>

																				<div class="col-sm-7"><span id="sprytextfield5">
																						<input type="text" class="form-control" value="<?php echo $row_getdetails['award_provided']; ?>" id="award_provided" name="award_provided" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Graduation Year Provided</label>

																				<div class="col-sm-7"><span id="sprytextfield5">
																						<input type="text" class="form-control" value="<?php echo $row_getdetails['year_provided']; ?>" id="year_provided" name="year_provided" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Insitution Country</label>

																				<div class="col-sm-7"><span id="spryselect1">
																						<select class="form-control" name="country" id="country" data-placeholder="Choose Country...">
																							<!--     <select name="client_country" id="client_country">-->
																							<option value="<?php echo $row_getdetails['country']; ?>"><?php echo $row_getdetails['country']; ?></option>
																							<option value="000"></option>
																							<?php



																							$query_getcountries2 = "SELECT * FROM pel_countries ORDER BY country_name ASC";
																							$getcountries2 = mysqli_query_ported($query_getcountries2, $connect) or die(mysqli_error($connect));
																							$row_getcountries2 = mysqli_fetch_assoc($getcountries2);
																							$totalRows_getcountries2 = mysqli_num_rows($getcountries2);



																							do { ?>
																								<option value="<?php echo $row_getcountries2['country_name']; ?>"><?php echo $row_getcountries2['country_name']; ?></option>
																							<?php } while ($row_getcountries2 = mysqli_fetch_assoc($getcountries2)); ?>
																						</select>

																						<span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Data Source</label>

																				<div class="col-sm-7"><span id="sprytextfield8">
																						<input class="form-control" value="<?php echo $row_getdetails['data_source_provided']; ?>" type="text" id="data_source_provided" name="data_source_provided" />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>


																				<div class="clearfix form-actions">
																					<div class="col-md-offset-3 col-md-9">
																						<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
																							<!--<button onClick="submit" class="btn btn-info" type="button">-->
																							<i class="ace-icon fa fa-check bigger-110"></i>
																							Save
																						</button>

																						   
																						<button class="btn" type="reset">
																							<i class="ace-icon fa fa-undo bigger-110"></i>
																							Reset </button>
																					</div>
																				</div>

																				<input type="hidden" name="MM_update" value="editdetails">

																			</form>

																		</div>
																		<div class="modal-footer no-margin-top">
																			<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
																				<i class="ace-icon fa fa-times"></i>
																				Close </button>


																		</div>
																	</div>
																</div>

															</div><!-- PAGE CONTENT ENDS -->
														</td>
														<td><strong>Data Collected</strong> &nbsp;&nbsp; <?php
																											if ($row_getdetails['status'] == '11' || $row_getdetails['status'] == '22') {
																											?>
																<a href="educationcheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=00&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>"> <button class="btn btn-xs btn-danger">
																		<i class="ace-icon fa fa-trash-o bigger-120"></i> </button></a> <?php
																																	}
																																	if ($row_getdetails['status'] == '00') {

																																		?>
																<a href="educationcheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=22&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>"> <button class="btn btn-xs btn-success">
																		<i class="ace-icon fa fa-check bigger-120"></i> </button></a>
															<?php
																																	}
															?><i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-checkindb-<?php echo $edu_id ?>" role="button" class="green" data-toggle="modal">
																<button class="btn btn-xs btn-success">
																	<i class="ace-icon smaller-80 green"></i>Update Data </button></a>

															<!-- HERE IS WHERE I WANT -->
															<div id="modal-checkindb-<?php echo $edu_id ?>" class="modal fade" tabindex="-1">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header no-padding">
																			<div class="table-header">
																				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																					<span class="white">&times;</span> </button>
																				Fetch From Database
																			</div>
																		</div>

																		<div class="modal-body padding">
																			<form method="POST" enctype="multipart/form-data" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="checkindb">
																				<input type="hidden" id="request_id" name="request_id" value="<?php echo $colname_getrequestid; ?>" />
																				<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $colname_getmoduleid; ?>" />
																				<input type="hidden" id="search_id" name="search_id" value="<?php echo $search_ref; ?>" />
																				<input type="hidden" id="edu_id" name="edu_id" value="<?php echo $row_getdetails['edu_id']; ?>" />


																				<input type="hidden" id="status" name="status" value="44" />
																				<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
																				<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />

																				<div class="space-10"></div>


																				<?php if ($is_negative == 'default') { ?>
																					<label class="col-sm-4">Enter Student Token</label>

																					<div class="col-sm-8"><input type="text" class="form-control" id="student_token" name="student_token" class="form-control" required value="<?php echo $student_token; ?>" />
																					</div>
																					<br />
																				<?php } ?>



																				<div class="space-10"></div>

																				<label class="col-sm-4">Upload Certificate</label>


																				<div class="col-sm-8">
																					<input class="form-control" id="certificate_photo" name="certificate_photo" type="file" />
																				</div>
																				<br />
																				<div class="space-10"></div>
																				<label class="col-sm-4">Comments</label>
																				<div class="col-sm-12">
																					<div id="editparent">
																						<div id="editControls">
																							<div class="btn-group">
																								<a class="btn btn-xs btn-default" data-role="undo" href="#" title="Undo"><i class="fa fa-undo"></i></a>
																								<a class="btn btn-xs btn-default" data-role="redo" href="#" title="Redo"><i class="fa fa-repeat"></i></a>
																							</div>
																							<div class="btn-group">
																								<a class="btn btn-xs btn-default" data-role="bold" href="#" title="Bold"><i class="fa fa-bold"></i></a>
																								<a class="btn btn-xs btn-default" data-role="italic" href="#" title="Italic"><i class="fa fa-italic"></i></a>
																								<a class="btn btn-xs btn-default" data-role="underline" href="#" title="Underline"><i class="fa fa-underline"></i></a>
																								<a class="btn btn-xs btn-default" data-role="strikeThrough" href="#" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
																							</div>
																							<div class="btn-group">
																								<a class="btn btn-xs btn-default" data-role="indent" href="#" title="Blockquote"><i class="fa fa-indent"></i></a>
																								<a class="btn btn-xs btn-default" data-role="insertUnorderedList" href="#" title="Unordered List"><i class="fa fa-list-ul"></i></a>
																								<a class="btn btn-xs btn-default" data-role="insertOrderedList" href="#" title="Ordered List"><i class="fa fa-list-ol"></i></a>
																							</div>
																							<div class="btn-group">
																								<a class="btn btn-xs btn-default" data-role="h1" href="#" title="Heading 1"><i class="fa fa-header"></i><sup>1</sup></a>
																								<a class="btn btn-xs btn-default" data-role="h2" href="#" title="Heading 2"><i class="fa fa-header"></i><sup>2</sup></a>
																								<a class="btn btn-xs btn-default" data-role="h3" href="#" title="Heading 3"><i class="fa fa-header"></i><sup>3</sup></a>
																								<a class="btn btn-xs btn-default" data-role="p" href="#" title="Paragraph"><i class="fa fa-paragraph"></i></a>
																							</div>
																						</div>
																						<div id="editor" contenteditable>
																						</div>
																						<textarea name="data_notes" id="editorCopy" required="required" style="display:none;"><?php echo isset($row_getdetails['data_notes']) ? $row_getdetails['data_notes'] : '' ?>
																					</textarea>
																					</div>

																					<br />

																					<div class="space-10"></div>


																					<div class="clearfix form-actions">
																						<div class="col-md-offset-3 col-md-9">
																							<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
																								<!--<button onClick="submit" class="btn btn-info" type="button">-->
																								<i class="ace-icon fa fa-check bigger-110"></i>
																								Save
																							</button>

																							   
																							<button class="btn" type="reset">
																								<i class="ace-icon fa fa-undo bigger-110"></i>
																								Reset </button>
																						</div>
																					</div>

																					<input type="hidden" name="MM_insert" value="checkindb">

																			</form>

																		</div>
																		<div class="modal-footer no-margin-top">
																			<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
																				<i class="ace-icon fa fa-times"></i>
																				Close </button>


																		</div>
																	</div>
																</div>

															</div><!-- PAGE CONTENT ENDS -->
														</td>

														<?php if ($is_negative == 'default') { ?>
															<td><strong>Match Status</strong> &nbsp;&nbsp;
																<?php
																if ($row_getdetails['status'] == '11' || $row_getdetails['status'] == '22' || $row_getdetails['status'] == '44') {
																?>
																	<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-editmatch-<?php echo $row_getdetails['edu_id']; ?>" role="button" class="green" data-toggle="modal">
																		<button class="btn btn-xs btn-info">
																			<i class="ace-icon fa fa-pencil bigger-120"></i> </button></a> <?php
																																		}
																																			?>

																<div id="modal-editmatch-<?php echo $row_getdetails['edu_id']; ?>" class="modal fade" tabindex="-1">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-header no-padding">
																				<div class="table-header">
																					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																						<span class="white">&times;</span> </button>
																					Edit Match Status
																				</div>
																			</div>

																			<div class="modal-body padding">
																				<form enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editmatchdetails">
																					<input type="hidden" id="request_id" name="request_id" value="<?php echo $colname_getrequestid; ?>" />
																					<input type="hidden" id="search_id" name="search_id" value="<?php echo $search_ref; ?>" />

																					<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $colname_getmoduleid; ?>" />
																					<input type="hidden" id="edu_id" name="edu_id" value="<?php echo $row_getdetails['edu_id']; ?>" />

																					<input type="hidden" id="status" name="status" value="22" />
																					<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
																					<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />

																					<div class="space-10"></div>
																					<table id="simple-table" class="table table-bordered table-hover">
																						<tr>
																							<td style="border: none;"></td>
																							<td><strong>Details Provided</strong></td>
																							<td><strong>Data Collected </strong></td>
																							<td><strong>Match Status</strong></td>
																						</tr>

																						<tr>
																							<th>Name</th>
																							<td><?php echo $row_getdetails['name_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_name']; ?></td>
																							<td>
																								<?php $match_status_name = $row_getdetails['match_status_name'] ?>
																								<select class="chosen-select form-control" name="match_status_name" id="match_status_name" data-placeholder="Choose match status..." required>
																									<option value="<?php echo $match_status_name ? $match_status_name : '' ?>"><?php echo $match_status_name ? $match_status_name : 'select match status' ?> </option>
																									<?php echo $match_status_name !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_name : 'select match status' ?> </option>
																									<?php echo $match_status_name !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>


																						<tr>
																							<th>Institution</th>
																							<td><?php echo $row_getdetails['institution_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_institution']; ?></td>
																							<td>
																								<?php $match_status_insititution = $row_getdetails['match_status_insititution'] ?>
																								<select class="chosen-select form-control" name="match_status_insititution" id="match_status_insititution" data-placeholder="Choose match status..." required>

																									<option value="<?php echo $match_status_insititution ? $match_status_insititution : '' ?>"><?php echo $match_status_insititution ? $match_status_insititution : 'select match status' ?> </option>
																									<?php echo $match_status_insititution !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_insititution : 'select match status' ?> </option>
																									<?php echo $match_status_insititution !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>


																						<tr>
																							<th>Course</th>
																							<td><?php echo $row_getdetails['course_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_course']; ?></td>

																							<td>
																								<?php $match_status_course = $row_getdetails['match_status_course'] ?>
																								<select class="chosen-select form-control" name="match_status_course" id="match_status_course" data-placeholder="Choose match status..." required>
																									<option value="<?php echo $match_status_course ? $match_status_course : '' ?>"><?php echo $match_status_course ? $match_status_course : 'select match status' ?> </option>
																									<?php echo $match_status_course !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_course : 'select match status' ?> </option>
																									<?php echo $match_status_course !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>


																						<tr>
																							<th>Specialization</th>
																							<td><?php echo $row_getdetails['specialization_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_specialization']; ?></td>
																							<td>
																								<?php $match_status_specialization = $row_getdetails['match_status_specialization'] ?>
																								<select class="chosen-select form-control" name="match_status_specialization" id="match_status_specialization" data-placeholder="Choose match status..." required>
																									<option value="<?php echo $match_status_specialization ? $match_status_specialization : '' ?>"><?php echo $match_status_specialization ? $match_status_specialization : 'select match status' ?> </option>
																									<?php echo $match_status_specialization !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_specialization : 'select match status' ?> </option>
																									<?php echo $match_status_specialization !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>


																						<tr>
																							<th>Award</th>
																							<td><?php echo $row_getdetails['award_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_award']; ?></td>

																							<td>
																								<?php $match_status_award = $row_getdetails['match_status_award'] ?>
																								<select class="chosen-select form-control" name="match_status_award" id="match_status_award" data-placeholder="Choose match status..." required>
																									<option value="<?php echo $match_status_award ? $match_status_award : '' ?>"><?php echo $match_status_award ? $match_status_award : 'select match status' ?> </option>
																									<?php echo $match_status_award !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_award : 'select match status' ?> </option>
																									<?php echo $match_status_award !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>


																						<tr>
																							<th>Year</th>
																							<td><?php echo $row_getdetails['year_provided']; ?></td>
																							<td><?php echo $row_getdetails['edu_graduation_year']; ?></td>
																							<td>
																								<?php $match_status_year = $row_getdetails['match_status_year'] ?>
																								<select class="chosen-select form-control" name="match_status_year" id="match_status_year" data-placeholder="Choose match status..." required>
																									<option value="<?php echo $match_status_year ? $match_status_year : '' ?>"><?php echo $match_status_year ? $match_status_year : 'select match status' ?> </option>
																									<?php echo $match_status_year !== 'MATCH' ? '<option value="MATCH">MATCH</option>' : '' ?>

																									$match_status_year : 'select match status' ?> </option>
																									<?php echo $match_status_year !== 'DOESNT MATCH' ? '<option value="DOESNT MATCH">DOESNT MATCH</option>' : '' ?>
																								</select>
																							</td>
																						</tr>

																					</table>



																					<br />
																					<div class="space-10"></div>


																					<div class="clearfix form-actions">
																						<div class="col-md-offset-3 col-md-9">
																							<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
																								<!--<button onClick="submit" class="btn btn-info" type="button">-->
																								<i class="ace-icon fa fa-check bigger-110"></i>
																								Save
																							</button>

																							   
																							<button class="btn" type="reset">
																								<i class="ace-icon fa fa-undo bigger-110"></i>
																								Reset </button>
																						</div>
																					</div>

																					<input type="hidden" name="MM_update" value="editmatchdetails">

																				</form>

																			</div>
																			<div class="modal-footer no-margin-top">
																				<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
																					<i class="ace-icon fa fa-times"></i>
																					Close </button>


																			</div>
																		</div>
																	</div>

																</div><!-- PAGE CONTENT ENDS -->
															</td>
														<?php } ?>
													</tr>
												</thead>

												<tr>
													<td><strong>Name:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['name_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>

														<td>
															<a href="#"><?php echo $row_getdetails['edu_name']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_name']; ?> </a>
														</td>

													<?php } else { ?>
														<td colspan="2" rowspan="9" class="comments">
															<?php echo $row_getdetails['data_notes'] ? $row_getdetails['data_notes'] : "<br /><p class='text-muted text-center'>No Notes</p>" ?>
														</td>
													<?php } ?>

												</tr>
												<tr>
													<td><strong>Institution:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['institution_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>
														<td>
															<a href="#"><?php echo $row_getdetails['edu_institution']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_insititution']; ?> </a>
														</td>
													<?php } ?>
												</tr>
												<tr>

													<td><strong>Course:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['course_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>
														<td>
															<a href="#"><?php echo $row_getdetails['edu_course']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_course']; ?> </a>
														</td>
													<?php } ?>


												</tr>
												<tr>

													<td><strong>Course Specialization:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['specialization_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>
														<td>
															<a href="#"><?php echo $row_getdetails['edu_specialization']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_specialization']; ?> </a>
														</td>
													<?php } ?>


												</tr>
												<tr>

													<td><strong>Award:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['award_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>
														<td>
															<a href="#"><?php echo $row_getdetails['edu_award']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_award']; ?> </a>
														</td>
													<?php } ?>
												</tr>
												<tr>
													<td><strong>Graduation Date:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['year_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>
														<td>
															<a href="#"><?php echo $row_getdetails['edu_graduation_year']; ?> </a>
														</td>
														<td>
															<a href="#"><?php echo $row_getdetails['match_status_year']; ?> </a>
														</td>
													<?php } ?>
												</tr>
												<tr>
													<td><strong>Data Source:</strong></td>
													<td>
														<a href="#"><?php echo $row_getdetails['data_source_provided']; ?> </a>
													</td>
													<?php if ($is_negative == 'default') { ?>

														<td>
															<a href="#"><?php echo $row_getdetails['data_source']; ?> </a>
														</td>
														<td>
															- </td>
													<?php } ?>
												</tr>


												<thead>
													<tr>
														<td colspan="2">
															<strong>CERTIFICATE PHOTO:</strong>
														</td>
														<?php if ($is_negative == 'default') { ?>
															<td colspan="2">
																<strong>NOTES:</strong>
															</td>
														<?php } ?>

													</tr>
												</thead>
												<tr>
													<td colspan="2" width="50%">
														<?php if ($certificate_photo) { ?>
															<img width="100%" src="<?php
																					echo startsWith($certificate_photo, 'http') ? $certificate_photo : $img_long_prefix . $certificate_photo ?>">
														<?php } else {
															echo 'No certificate provided';
														} ?>

													</td>

													<?php if ($is_negative == 'default') { ?>
														<td colspan="2">
															<?php echo $row_getdetails['data_notes'] ? $row_getdetails['data_notes'] : "<br /><p class='text-muted text-center'>No Notes</p>" ?></td>
													<?php } ?>
												</tr>
											</table>

									</div>
									<?php
											if ($row_getdetails['review_status'] == 'REJECTED' || $row_getdetails['review_status'] == 'APPROVED') {
									?>


										<div class="col-lg-12" align="center">
											<table id="simple-table" class="table  table-striped  table-bordered table-hover">

												<tr>
													<td>
														<strong>REVIEW NOTES:</strong>
													</td>
													<td>
														<?php

														if ($row_getdetails['review_status'] == 'APPROVED') {
														?>

															<span class="label label-sm label-success">APPROVED</span>
														<?php
														}
														if ($row_getdetails['review_status'] == 'REJECTED') {
														?>
															<span class="label label-sm label-danger">REJECTED</span>
														<?php
														}
														if ($row_getdetails['status'] == '22') {
														?>
															<span class="label label-sm label-warning">Not Reviewed</span>
														<?php
														}
														?>
													</td>
													<td><strong>VERIFIED BY:</strong></td>
													<td> <?php echo $row_getdetails['verified_by']; ?></td>
													<td><strong>VERIFIED DATE:</strong></td>
													<td> <?php echo $row_getdetails['verified_date']; ?></td>
												</tr>
												<?php
												if ($row_getdetails['review_status'] == 'REJECTED') {
												?>
													<tr>
														<td colspan="6">
															<?php echo $row_getdetails['review_notes']; ?></td>
													</tr>
												<?php
												}
												?>
											</table>
										</div>


									<?php
											} ?>
									<?php

											// if ($row_getdetails['status'] == '11' || $row_getdetails['status'] == '22') {
									?>
									<?php if ($is_negative == 'negative') { ?>
										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="educationcheck.php?mark_as_negative=0&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>" class="btn btn-xs btn-outline-primary">
											<i class="ace-icon fa fa-times bigger-120">Marked as Negative</i></a>
									<?php } elseif ($is_negative == 'ongoing') { ?>
										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
										<a href="educationcheck.php?mark_as_negative=0&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>" role="button" class="btn btn-xs btn-outline-primary" data-toggle="modal">
											<i class="ace-icon smaller-80 green"></i>Marked as Ongoing
										</a>
									<?php } else { ?>
										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
										<a href="educationcheck.php?mark_as_negative=2&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>" role="button" class="btn btn-xs btn-primary" data-toggle="modal">
											<i class="ace-icon smaller-80 green"></i>Mark as Ongoing
										</a>
										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
										<a href="educationcheck.php?mark_as_negative=1&edu_id=<?php echo $row_getdetails['edu_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>" class="btn btn-xs btn-warning">
											<i class="ace-icon fa fa-times bigger-120">Mark As Negative</i></a>

									<?php } ?>


									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="educationcheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=11&search_id_approve=<?php echo $search_ref; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>&edu_id=<?php echo $row_getdetails['edu_id']; ?>"> <button class="btn btn-xs btn-success">
											<i class="ace-icon fa fa-check bigger-120">Approve</i> </button></a>

									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-addreject-<?php echo $row_getdetails['edu_id']; ?>" role="button" class="blue" data-toggle="modal">
										<button class="btn btn-xs btn-danger"> Reject With Reason </button></a>
									<?php
											// }
									?>
									<div id="modal-addreject-<?php echo $row_getdetails['edu_id']; ?>" class="modal fade" tabindex="-1">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header no-padding">
													<div class="table-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
															<span class="white">&times;</span> </button>
														Add Reject Reason
													</div>
												</div>

												<div class="modal-body padding">
													<form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="addreject">
														<input type="hidden" id="request_id" name="request_id" value="<?php echo $colname_getrequestid; ?>" />
														<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $colname_getmoduleid; ?>" />
														<input type="hidden" id="search_id" name="search_id" value="<?php echo $search_ref; ?>" />
														<input type="hidden" id="edu_id" name="edu_id" value="<?php echo $row_getdetails['edu_id']; ?>" />

														<input type="hidden" id="status" name="status" value="00" />
														<input type="hidden" id="review_status" name="review_status" value="REJECTED" />
														<input type="hidden" id="verified_by" name="verified_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
														<input type="hidden" id="verified_date" name="verified_date" value="<?php echo date('d-m-Y H:m:s'); ?>" />

														<div class="space-10"></div>
														<label class="col-sm-4">Reject Comments</label>
														<div class="col-sm-12">
															<div class="widget-box widget-color-green">
																<div class="widget-header widget-header-small"> </div>

																<div class="widget-body">
																	<div class="widget-main no-padding">
																		<textarea name="review_notes" data-provide="markdown" data-iconlibrary="fa" rows="10"></textarea>
																	</div>
																</div>
															</div>
														</div> <br />
														<div class="space-10"></div>


														<div class="clearfix form-actions">
															<div class="col-md-offset-3 col-md-9">
																<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
																	<!--<button onClick="submit" class="btn btn-info" type="button">-->
																	<i class="ace-icon fa fa-check bigger-110"></i>
																	Save
																</button>

																   
																<button class="btn" type="reset">
																	<i class="ace-icon fa fa-undo bigger-110"></i>
																	Reset </button>
															</div>
														</div>

														<input type="hidden" name="MM_insert" value="addreject">

													</form>

												</div>
												<div class="modal-footer no-margin-top">
													<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
														<i class="ace-icon fa fa-times"></i>
														Close </button>


												</div>
											</div>
										</div>

									</div><!-- PAGE CONTENT ENDS -->
								<?php

										}
										while ($row_getdetails = mysqli_fetch_assoc($getdetails));
								?>
								<hr />




								<div>

									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-adddetails-<?php echo $row_getdetails['edu_id']; ?>" role="button" class="green" data-toggle="modal">
										<button class="btn btn-xs btn-primary">
											<i class="ace-icon smaller-80 green"></i>Add Raw Given Data
										</button></a>


									<div id="modal-adddetails-<?php echo $row_getdetails['edu_id']; ?>" class="modal fade" tabindex="-1">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header no-padding">
													<div class="table-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
															<span class="white">&times;</span> </button>
														Add Details
													</div>
												</div>



												<div class="modal-body padding">
													<form enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="newdetails">
														<input type="hidden" id="request_id" name="request_id" value="<?php echo $colname_getrequestid; ?>" />
														<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $colname_getmoduleid; ?>" />
														<input type="hidden" id="search_id" name="search_id" value="<?php echo $search_ref; ?>" />


														<input type="hidden" id="status" name="status" value="33" />
														<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
														<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />

														<div class="space-10"></div>
														<label class="col-sm-4">Education Level</label>

														<div class="col-sm-8"><span id="sprytextfield1">
																<input type="text" class="form-control" id="education_level" name="education_level" value="HIGHEST EDUCATION" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>

														<label class="col-sm-4">Name Provided</label>

														<div class="col-sm-8"><span id="sprytextfield1">
																<input type="text" class="form-control" id="name_provided" name="name_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>


														<br />
														<div class="space-10"></div>

														<label class="col-sm-4">Institution Provided</label>

														<div class="col-sm-8"><span id="sprytextfield4">
																<input type="text" class="form-control" id="institution_provided" name="institution_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>

														<label class="col-sm-4">Course Provided</label>

														<div class="col-sm-8"><span id="sprytextfield4">
																<input type="text" class="form-control" id="course_provided" name="course_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>
														<label class="col-sm-4">Course Specialization Provided</label>

														<div class="col-sm-8"><span id="sprytextfield4">
																<input type="text" class="form-control" id="specialization_provided" name="specialization_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>

														<label class="col-sm-4">Award Provided</label>

														<div class="col-sm-7"><span id="sprytextfield5">
																<input type="text" class="form-control" id="award_provided" name="award_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>


														<label class="col-sm-4">Graduation Date Given</label>

														<div class="col-sm-7"><span id="sprytextfield5">
																<input type="text" class="form-control" id="year_provided" name="year_provided" required />
																<span class="textfieldRequiredMsg">*</span></span></div>

														<br />
														<div class="space-10"></div>


														<label class="col-sm-4">Institution Country</label>

														<div class="col-sm-7"><span id="spryselect1">
																<select class="chosen-select form-control" name="country" id="country" data-placeholder="Choose Country...">
																	<!--     <select name="client_country" id="client_country">-->
																	<option value="<?php echo $row_getdetails['country']; ?>"><?php echo $row_getdetails['country']; ?></option>
																	<option value="000"></option>
																	<?php



																	$query_getcountries2 = "SELECT * FROM pel_countries ORDER BY country_name ASC";
																	$getcountries2 = mysqli_query_ported($query_getcountries2, $connect) or die(mysqli_error($connect));
																	$row_getcountries2 = mysqli_fetch_assoc($getcountries2);
																	$totalRows_getcountries2 = mysqli_num_rows($getcountries2);



																	do { ?>
																		<option value="<?php echo $row_getcountries2['country_name']; ?>"><?php echo $row_getcountries2['country_name']; ?></option>
																	<?php } while ($row_getcountries2 = mysqli_fetch_assoc($getcountries2)); ?>
																</select>

																<span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
														<br />
														<div class="space-10"></div>


														<label class="col-sm-4">Data Source</label>

														<div class="col-sm-7"><span id="sprytextfield8">
																<input type="text" class="form-control" id="data_source_provided" name="data_source_provided" />
																<span class="textfieldRequiredMsg">*</span></span></div>
														<br />
														<div class="space-10"></div>


														<div class="clearfix form-actions">
															<div class="col-md-offset-3 col-md-9">
																<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
																	<i class="ace-icon fa fa-check bigger-110"></i>
																	Save
																</button>

																   
																<button class="btn" type="reset">
																	<i class="ace-icon fa fa-undo bigger-110"></i>
																	Reset </button>
															</div>
														</div>

														<input type="hidden" name="MM_insert" value="newdetails">

													</form>

												</div>
												<div class="modal-footer no-margin-top">
													<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
														<i class="ace-icon fa fa-times"></i>
														Close </button>


												</div>
											</div>
										</div>

									</div><!-- PAGE CONTENT ENDS -->

								</div>

								<?php
								?>

								</div>

							</div>




						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.page-content -->
			</div>
		</div><!-- /.main-content -->

		<div class="footer">
			<div class="footer-inner">
				<!-- #section:basics/footer -->
				<div class="footer-content">
					<span class="bigger-120">
						<span class="blue bolder">Peleza</span>
						Admin &copy; 2018 </span>

					&nbsp;&nbsp;
				</div>

				<!-- /section:basics/footer -->
			</div>
		</div>

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
			<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a>
	</div><!-- /.main-container -->
	<!-- basic scripts -->

	<!--[if !IE]> -->
	<script type="text/javascript">
		window.jQuery || document.write("<script src='../../assets/js/jquery.js'>" + "<" + "/script>");
	</script>

	<!-- <![endif]-->

	<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
	<script type="text/javascript">
		if ('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
	</script>
	<script src="../../assets/js/bootstrap.js"></script>

	<!-- page specific plugin scripts -->

	<!-- page specific plugin scripts -->
	<script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
	<script src="../../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
	<script src="../../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
	<script src="../../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

	<!--[if lte IE 8]>
		  <script src="../../assets/js/excanvas.js"></script>
		<![endif]-->
	<script src="../../assets/js/jquery-ui.custom.js"></script>
	<script src="../../assets/js/jquery.ui.touch-punch.js"></script>
	<script src="../../assets/js/chosen.jquery.js"></script>
	<script src="../../assets/js/fuelux/fuelux.spinner.js"></script>
	<script src="../../assets/js/date-time/bootstrap-datepicker.js"></script>
	<script src="../../assets/js/date-time/bootstrap-timepicker.js"></script>
	<script src="../../assets/js/date-time/moment.js"></script>
	<script src="../../assets/js/date-time/daterangepicker.js"></script>
	<script src="../../assets/js/date-time/bootstrap-datetimepicker.js"></script>
	<script src="../../assets/js/bootstrap-colorpicker.js"></script>
	<script src="../../assets/js/jquery.knob.js"></script>
	<script src="../../assets/js/jquery.autosize.js"></script>
	<script src="../../assets/js/jquery.inputlimiter.1.3.1.js"></script>
	<script src="../../assets/js/jquery.maskedinput.js"></script>
	<script src="../../assets/js/bootstrap-tag.js"></script>


	<script src="../../assets/js/markdown/markdown.js"></script>
	<script src="../../assets/js/markdown/bootstrap-markdown.js"></script>
	<script src="../../assets/js/jquery.hotkeys.js"></script>
	<script src="../../assets/js/bootstrap-wysiwyg.js"></script>
	<script src="../../assets/js/bootbox.js"></script>

	<!-- ace scripts -->
	<script src="../../assets/js/ace/elements.scroller.js"></script>
	<script src="../../assets/js/ace/elements.colorpicker.js"></script>
	<script src="../../assets/js/ace/elements.fileinput.js"></script>
	<script src="../../assets/js/ace/elements.typeahead.js"></script>
	<script src="../../assets/js/ace/elements.wysiwyg.js"></script>
	<script src="../../assets/js/ace/elements.spinner.js"></script>
	<script src="../../assets/js/ace/elements.treeview.js"></script>
	<script src="../../assets/js/ace/elements.wizard.js"></script>
	<script src="../../assets/js/ace/elements.aside.js"></script>
	<script src="../../assets/js/ace/ace.js"></script>
	<script src="../../assets/js/ace/ace.ajax-content.js"></script>
	<script src="../../assets/js/ace/ace.touch-drag.js"></script>
	<script src="../../assets/js/ace/ace.sidebar.js"></script>
	<script src="../../assets/js/ace/ace.sidebar-scroll-1.js"></script>
	<script src="../../assets/js/ace/ace.submenu-hover.js"></script>
	<script src="../../assets/js/ace/ace.widget-box.js"></script>
	<script src="../../assets/js/ace/ace.settings.js"></script>
	<script src="../../assets/js/ace/ace.settings-rtl.js"></script>
	<script src="../../assets/js/ace/ace.settings-skin.js"></script>
	<script src="../../assets/js/ace/ace.widget-on-reload.js"></script>
	<script src="../../assets/js/ace/ace.searchbox-autocomplete.js"></script>

	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			/** ******************************
			 * Simple WYSIWYG
			 ****************************** **/
			$('#editControls a').click(function(e) {
				e.preventDefault();
				switch ($(this).data('role')) {
					case 'h1':
					case 'h2':
					case 'h3':
					case 'h4':
					case 'h5':
					case 'p':
						document.execCommand('formatBlock', false, $(this).data('role'));
						break;
					default:
						document.execCommand($(this).data('role'), false, null);
						break;
				}

				var textval = $("#editor").html();
				$("#editorCopy").val(textval);
			});

			$("#editor").keyup(function() {
				var value = $(this).html();
				$("#editorCopy").val(value);
			}).keyup();

			$('#checkIt').click(function(e) {
				e.preventDefault();
				alert($("#editorCopy").val());
			});
		});
		jQuery(function($) {
			//initiate dataTables plugin
			var oTable1 =
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.dataTable({
					bAutoWidth: false,
					"aoColumns": [
						null, null, null, null, null, null,
						{
							"bSortable": false
						}
					],
					"aaSorting": [],

					//,
					//"sScrollY": "200px",
					//"bPaginate": false,

					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element

					//"iDisplayLength": 50
				});
			//oTable1.fnAdjustColumnSizing();


			//TableTools settings
			TableTools.classes.container = "btn-group btn-overlap";
			TableTools.classes.print = {
				"body": "DTTT_Print",
				"info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
				"message": "tableTools-print-navbar"
			}

			//initiate TableTools extension
			var tableTools_obj = new $.fn.dataTable.TableTools(oTable1, {
				"sSwfPath": "../../assets/js/dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path

				"sRowSelector": "td:not(:last-child)",
				"sRowSelect": "multi",
				"fnRowSelected": function(row) {
					//check checkbox when row is selected
					try {
						$(row).find('input[type=checkbox]').get(0).checked = true
					} catch (e) {}
				},
				"fnRowDeselected": function(row) {
					//uncheck checkbox
					try {
						$(row).find('input[type=checkbox]').get(0).checked = false
					} catch (e) {}
				},

				"sSelectedClass": "success",
				"aButtons": [{
						"sExtends": "copy",
						"sToolTip": "Copy to clipboard",
						"sButtonClass": "btn btn-white btn-primary btn-bold",
						"sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
						"fnComplete": function() {
							this.fnInfo('<h3 class="no-margin-top smaller">Table copied</h3>\
									<p>Copied ' + (oTable1.fnSettings().fnRecordsTotal()) + ' row(s) to the clipboard.</p>',
								1500
							);
						}
					},

					{
						"sExtends": "csv",
						"sToolTip": "Export to CSV",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
					},

					{
						"sExtends": "pdf",
						"sToolTip": "Export to PDF",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
					},

					{
						"sExtends": "print",
						"sToolTip": "Print view",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",

						"sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",

						"sInfo": "<h3 class='no-margin-top'>Print view</h3>\
									  <p>Please use your browser's print function to\
									  print this table.\
									  <br />Press <b>escape</b> when finished.</p>",
					}
				]
			});
			//we put a container before our table and append TableTools element to it
			$(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));

			//also add tooltips to table tools buttons
			//addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
			//so we add tooltips to the "DIV" child after it becomes inserted
			//flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
			setTimeout(function() {
				$(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
					var div = $(this).find('> div');
					if (div.length > 0) div.tooltip({
						container: 'body'
					});
					else $(this).tooltip({
						container: 'body'
					});
				});
			}, 200);



			//ColVis extension
			var colvis = new $.fn.dataTable.ColVis(oTable1, {
				"buttonText": "<i class='fa fa-search'></i>",
				"aiExclude": [0, 6],
				"bShowAll": true,
				//"bRestore": true,
				"sAlign": "right",
				"fnLabel": function(i, title, th) {
					return $(th).text(); //remove icons, etc
				}

			});

			//style it
			$(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')

			//and append it to our table tools btn-group, also add tooltip
			$(colvis.button())
				.prependTo('.tableTools-container .btn-group')
				.attr('title', 'Show/hide columns').tooltip({
					container: 'body'
				});

			//and make the list, buttons and checkboxed Ace-like
			$(colvis.dom.collection)
				.addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
				.find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
				.find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');



			/////////////////////////////////
			//table checkboxes
			$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

			//select/deselect all rows according to table header checkbox
			$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function() {
				var th_checked = this.checked; //checkbox inside "TH" table header

				$(this).closest('table').find('tbody > tr').each(function() {
					var row = this;
					if (th_checked) tableTools_obj.fnSelect(row);
					else tableTools_obj.fnDeselect(row);
				});
			});

			//select/deselect a row when the checkbox is checked/unchecked
			$('#dynamic-table').on('click', 'td input[type=checkbox]', function() {
				var row = $(this).closest('tr').get(0);
				if (!this.checked) tableTools_obj.fnSelect(row);
				else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
			});




			$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
				e.stopImmediatePropagation();

				e.stopPropagation();
				e.preventDefault();
			});


			//And for the first simple table, which doesn't have TableTools or dataTables
			//select/deselect all rows according to table header checkbox
			var active_class = 'active';
			$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function() {
				var th_checked = this.checked; //checkbox inside "TH" table header

				$(this).closest('table').find('tbody > tr').each(function() {
					var row = this;
					if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
					else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
				});
			});

			//select/deselect a row when the checkbox is checked/unchecked
			$('#simple-table').on('click', 'td input[type=checkbox]', function() {
				var $row = $(this).closest('tr');
				if (this.checked) $row.addClass(active_class);
				else $row.removeClass(active_class);
			});


			if (!ace.vars['touch']) {
				$('.chosen-select').chosen({
					allow_single_deselect: true
				});
				//resize the chosen on window resize

				$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							var $this = $(this);
							$this.next().css({
								'width': $this.parent().width()
							});
						})
					}).trigger('resize.chosen');
				//resize chosen on sidebar collapse/expand
				$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
					if (event_name != 'sidebar_collapsed') return;
					$('.chosen-select').each(function() {
						var $this = $(this);
						$this.next().css({
							'width': $this.parent().width()
						});
					})
				});


				$('#chosen-multiple-style .btn').on('click', function(e) {
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
					else $('#form-field-select-4').removeClass('tag-input-style');
				});
			}


			/********************************/
			//add tooltip for small view action buttons in dropdown menu
			$('[data-rel="tooltip"]').tooltip({
				placement: tooltip_placement
			});

			//tooltip placement on right or left
			function tooltip_placement(context, source) {
				var $source = $(source);
				var $parent = $source.closest('table')
				var off1 = $parent.offset();
				var w1 = $parent.width();

				var off2 = $source.offset();
				//var w2 = $source.width();

				if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
				return 'left';
			}

		})
	</script>


	<script type="text/javascript">
		<!--
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {
			validateOn: ["change"]
		});
		//
		-->
	</script>



	<style>
		/* table.negative td a[href='#']:not(.btn),
		table.negative .comment {
			color: #dc3545 !important;
		} */
		table.negative,
		table.negative thead td,
		table.negative tr,
		table.negative tbody {
			background-color: #B32222 !important;
			color: #f4f4f4;
			border-color: #8B0000 !important;
			border-width: 1px;
		}


		table.negative thead:first-of-type td {
			border-width: 1px;
			background: #AE1F1F !important;

		}

		table thead {
			font-weight: 800 !important;
			letter-spacing: .6 px;
		}

		table label {
			color: #232b2b;
		}

		table.negative tr,
		table.negative th,
		table.negative td {
			border-color: #8B0000 !important;
		}

		table.negative a {
			color: #f4f4f4;
		}
	</style>
</body>

</html>
<?php

?>