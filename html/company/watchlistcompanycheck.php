<?php require_once('../../Connections/connect.php'); ?>
<?php
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



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editdetails")) {

	if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
		date_default_timezone_set('Africa/Nairobi');
		$date_insert = date('dmYhis');
		$a = $_POST['company_name'] . "-" . $_POST['search_id'] . "-" . $date_insert;
		$rawname = $_FILES['photo']['name'];
		"Upload: " . $a . "_" . $_FILES["photo"]["name"];
		$file = "watchlistphotos/" . $a . "_" . $_FILES["photo"]["name"];

		move_uploaded_file(
			$_FILES["photo"]["tmp_name"],

			"watchlistphotos/" . $a . "_" . $_FILES["photo"]["name"]
		);

		$filenameuploaded = $a . "_" . $_FILES["photo"]["name"];
	} else {
		$filenameuploaded = $_POST['photo2'];
	}

	$updateSQL = sprintf(
		"UPDATE pel_company_watchlist_data SET company_name=%s, status=%s, address=%s, added_by=%s, search_id=%s, country=%s, data_source=%s, data_notes=%s, watchlist_status=%s, date_added=now(), photo=%s WHERE watchlist_id=%s",
		GetSQLValueString(strtoupper($_POST['company_name']), "text"),
		GetSQLValueString("22", "text"),
		GetSQLValueString(strtoupper($_POST['address']), "text"),
		GetSQLValueString($_POST['added_by'], "text"),
		GetSQLValueString($_POST['search_id'], "text"),
		GetSQLValueString(strtoupper($_POST['country']), "text"),
		GetSQLValueString($_POST['data_source'], "text"),
		GetSQLValueString($_POST['data_notes'], "text"),
		GetSQLValueString($_POST['watchlist_status'], "text"),
		GetSQLValueString($filenameuploaded, "text"),
		GetSQLValueString($_POST['watchlist_id'], "text")
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

											 Details of the Watchlist were not updated succesfully.
											<br />
										</div>';
	} else {

		$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newdetails")) {
	if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
		date_default_timezone_set('Africa/Nairobi');
		$date_insert = date('dmYhis');
		$a = $_POST['company_name'] . "-" . $_POST['search_id'] . "-" . $date_insert;
		$rawname = $_FILES['photo']['name'];
		"Upload: " . $a . "_" . $_FILES["photo"]["name"];
		$file = "watchlistphotos/" . $a . "_" . $_FILES["photo"]["name"];

		move_uploaded_file(
			$_FILES["photo"]["tmp_name"],

			"watchlistphotos/" . $a . "_" . $_FILES["photo"]["name"]
		);

		$filenameuploaded = $a . "_" . $_FILES["photo"]["name"];
	} else {
		$filenameuploaded = "";
	}

	$insertSQL = sprintf(
		"INSERT INTO pel_company_watchlist_data (company_name, status, address, added_by, search_id, country, data_source, data_notes, watchlist_status, date_added, photo) VALUES (%s, %s, %s,%s, %s, %s, %s, %s,  %s, now(), %s)",
		GetSQLValueString(strtoupper($_POST['company_name']), "text"),
		GetSQLValueString("22", "text"),
		GetSQLValueString(strtoupper($_POST['address']), "text"),
		GetSQLValueString($_POST['added_by'], "text"),
		GetSQLValueString($_POST['search_id'], "text"),
		GetSQLValueString(strtoupper($_POST['country']), "text"),
		GetSQLValueString($_POST['data_source'], "text"),
		GetSQLValueString($_POST['data_notes'], "text"),
		GetSQLValueString($_POST['watchlist_status'], "text"),
		GetSQLValueString($filenameuploaded, "text"),
		GetSQLValueString($_POST['watchlist_id'], "text")
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

										 Details of the Watch List havent been added.
											<br />
										</div>';
	} else {
		$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addreject")) {

	$deleteSQLreject = sprintf(
		"UPDATE pel_company_watchlist_data SET status=%s, review_status=%s, verified_by=%s, verified_date=now(), review_notes=%s WHERE search_id=%s and watchlist_id=%s",
		GetSQLValueString('00', "text"),
		GetSQLValueString($_POST['review_status'], "text"),
		GetSQLValueString($_POST['verified_by'], "text"),
		GetSQLValueString($_POST['review_notes'], "text"),
		GetSQLValueString($_POST['search_id'], "int"),
		GetSQLValueString($_POST['watchlist_id'], "int")
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
		$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
		/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
		header(sprintf("Location: %s", $updateGoTo));
	}
}

if ((isset($_GET['watchlist_id'])) && ($_GET['watchlist_id'] != "")) {
	if ($_GET['status'] == '00') {
		$deleteSQL = sprintf(
			"UPDATE pel_company_watchlist_data SET status=%s, added_by=%s, date_added=now() WHERE watchlist_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['watchlist_id'], "int")
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
			$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}

	if ($_GET['status'] == '22') {
		$deleteSQL = sprintf(
			"UPDATE pel_company_watchlist_data SET status=%s, added_by=%s, date_added =now() WHERE watchlist_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['watchlist_id'], "int")
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

										 Details of the data havent been added.
											<br />
										</div>';
		} else {
			$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}
}

//to approve data



if ((isset($_GET['search_id_approve'])) && ($_GET['search_id_approve'] != "")) {
	if ($_GET['status'] == '11') {


		$deleteSQL2 = sprintf(
			"UPDATE pel_company_watchlist_data SET status=%s, review_status=%s, verified_by=%s, verified_date=now() WHERE search_id=%s and watchlist_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString("APPROVED", "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['search_id_approve'], "text"),
			GetSQLValueString($_GET['watchlist_id'], "int")
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
			$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/


			header(sprintf("Location: %s", $updateGoTo));
		}
	}
}


if ((isset($_GET['watchlist_id'])) && ($_GET['watchlist_id'] != "")) {
	if ($_GET['status'] == '00') {
		$deleteSQL = sprintf(
			"UPDATE pel_company_watchlist_data SET status=%s, added_by=%s, date_added=now() WHERE watchlist_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['watchlist_id'], "int")
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

										 Details of the data have not been updated.
											<br />
										</div>';
		} else {
			$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}

	if ($_GET['status'] == '22') {
		$deleteSQL = sprintf(
			"UPDATE pel_company_watchlist_data SET status=%s, added_by=%s, date_added=now() WHERE watchlist_id=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString($_GET['watchlist_id'], "int")
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

										 Details of the data havenot been blocked
											<br />
										</div>';
		} else {
			$updateGoTo = "watchlistcompanycheck.php?request_id=$colname_getrequestid&moduleid=$colname_getmoduleid";
			/* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
			header(sprintf("Location: %s", $updateGoTo));
		}
	}
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Company Watchlist Details Data Management - Peleza Admin</title>

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

	<!-- text fonts -->
	<link rel="stylesheet" href="../../assets/css/ace-fonts.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

	<!--[if lte IE 9]>
			<link rel="stylesheet" href="../../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

	<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../../assets/css/ace-ie.css" />
		<![endif]-->

	<!-- inline styles related to this page -->

	<!-- ace settings handler -->
	<script src="../../assets/js/ace-extra.js"></script>

	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
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
		<div id="sidebar" class="sidebar                  responsive">
			<script type="text/javascript">
				try {
					ace.settings.check('sidebar', 'fixed')
				} catch (e) {}
			</script>
			<?php include('../sidebarmenu2.php'); ?>


			<!-- #section:basics/sidebar.layout.minimize -->
			<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
				<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
			</div>

			<!-- /section:basics/sidebar.layout.minimize -->
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
							<a href="#">Company</a>
						</li>

						<li class="active">Company Watchlist Details</li>
					</ul><!-- /.breadcrumb -->

					<!-- #section:basics/content.searchbox -->
					<div class="nav-search" id="nav-search">
						<!-- <form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>								</span>
							</form> -->
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">


					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<!--		
								<div class="hr hr-18 dotted hr-double"></div>

						<h4 class="pink">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="#modal-table" role="button" class="green" data-toggle="modal"> Table Inside a Modal Box </a>								</h4>

								<div class="hr hr-18 dotted hr-double"></div>
-->
							<div class="row">
								<div class="col-xs-12">


									<h3 align="left" class="header smaller lighter blue">Add Company Watchlist Details</h3>
								</div>
								<!--   <div  class="col-xs-6">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="educationupload.php">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>New Education Data
</button></a>
                                                
                                                  
                   							  </h3>
                           



</div>-->
								<?php


								$query_getstudent = "SELECT * FROM pel_psmt_request WHERE request_id = " . $colname_getrequestid . "";
								$getstudent = mysqli_query_ported($query_getstudent, $connect) or die(mysqli_error($connect));
								$row_getstudent = mysqli_fetch_assoc($getstudent);
								$totalRows_getstudent = mysqli_num_rows($getstudent);

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


									<a href="companydataentry.php?request_id=<?php echo $row_getstudent['request_id']; ?>" role="button" class="green">
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
									$query_getdetails = "SELECT * FROM pel_company_watchlist_data WHERE search_id = '" . $search_ref . "'";
									$getdetails  = mysqli_query_ported($query_getdetails, $connect) or die(mysqli_error($connect));
									$row_getdetails  = mysqli_fetch_assoc($getdetails);
									$totalRows_getdetails  = mysqli_num_rows($getdetails);
									if ($totalRows_getdetails > 0) {
									?>



										<div class="col-lg-12" align="center">
											<h3 align="left" class=" smaller lighter blue"><strong>GLOBAL WATCHLIST CHECK DETAILS: </strong>
											</h3>
											<?php
											do {
											?>

												<table id="simple-table" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>NAME:</th>
															<th>REGISTRATION NUMBER:</th>
															<th>COUNTRY:</th>
															<th>DATA SOURCE:</th>
															<th>WATCHLIST STATUS:</th>
															<th>STATUS:</th>
															<th>ACTION:</th>
														</tr>
													</thead>
													<tr>
														<td>
															<a href="#"><?php echo $row_getdetails['company_name']; ?> </a>
														</td>
														<td><?php echo $row_getdetails['address']; ?></td>
														<td><?php echo $row_getdetails['country']; ?></td>
														<td><?php echo $row_getdetails['data_source']; ?></td>
														<td><?php echo $row_getdetails['watchlist_status']; ?></td>
														<td><?php

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
															?>
														</td>



														<td class="hidden-480">

															<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-editdetails-<?php echo $row_getdetails['watchlist_id']; ?>" role="button" class="green" data-toggle="modal">
																<button class="btn btn-xs btn-info">
																	<i class="ace-icon fa fa-pencil bigger-120"></i> </button></a>

															<div id="modal-editdetails-<?php echo $row_getdetails['watchlist_id']; ?>" class="modal fade" tabindex="-1">
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
																				<input type="hidden" id="watchlist_id" name="watchlist_id" value="<?php echo $row_getdetails['watchlist_id']; ?>" />

																				<input type="hidden" id="status" name="status" value="22" />
																				<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
																				<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />

																				<div class="space-10"></div>

																				<label class="col-sm-4">Name</label>

																				<div class="col-sm-8"><span id="sprytextfield1">
																						<input type="text" id="company_name" name="company_name" value="<?php echo $row_getstudent['bg_dataset_name']; ?>" required />
																						<span class="textfieldRequiredMsg">*</span></span></div>
																				<br />
																				<div class="space-10"></div>
																				<label class="col-sm-4">Address</label>

																				<div class="col-sm-8"><span id="sprytextfield4">
																						<input type="text" value="<?php echo $row_getdetails['address']; ?>" id="address" name="address" />
																						<span class="textfieldRequiredMsg">*</span></span></div>

																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Country</label>

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
																						<input value="<?php echo $row_getdetails['data_source']; ?>" type="text" id="data_source" name="data_source" />
																						<span class="textfieldRequiredMsg">*</span></span></div>
																				<br />
																				<div class="space-10"></div>


																				<label class="col-sm-4">Watchlist Status</label>

																				<div class="col-sm-8">
																					<select class="chosen-select form-control" name="watchlist_status" id="watchlist_status" data-placeholder="Choose Watchlist Status..." required>
																						<!--  <select name="client_industry" id="client_industry">-->
																						<option value="<?php echo $row_getdetails['watchlist_status']; ?>"><?php echo $row_getdetails['watchlist_status']; ?></option>
																						<option value="">Watchlist Status</option>
																						<option value="NO ADVERSE MENTION FOUND">NO ADVERSE MENTION FOUND</option>
																						<option value="ADVERSE MENTION FOUND">ADVERSE MENTION FOUND</option>
																					</select>
																				</div>


																				<br />
																				<div class="space-10"></div>

																				<label class="col-sm-4">Watchlist Photo</label>

																				<div class="col-sm-8">
																					Current Photo <br />
																					<img width="200px" height="200px" src="watchlistphotos/<?php echo $row_getdetails['photo']; ?>" alt="WatchList Photos">

																					<input id="photo2" name="photo2" type="hidden" value="<?php echo $row_getdetails['photo']; ?>" />
																				</div>
																				<br />

																				<div class="space-10"></div>
																				<label class="col-sm-4">Click to Change Image</label>
																				<div class="col-sm-8">
																					<input class="form-control" id="photo" name="photo" type="file" />
																				</div>
																				<br />


																				<br />
																				<div class="space-10"></div>
																				<label class="col-sm-4">Comments</label>
																				<div class="col-sm-12">
																					<div class="widget-box widget-color-green">
																						<div class="widget-header widget-header-small"> </div>

																						<div class="widget-body">
																							<div class="widget-main no-padding">
																								<textarea name="data_notes" data-provide="markdown" data-iconlibrary="fa" rows="10"><?php echo $row_getdetails['data_notes']; ?></textarea>
																							</div>
																						</div>
																					</div>
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
															<?php
															if ($row_getdetails['status'] == '11' || $row_getdetails['status'] == '22') {
															?>
																<a href="watchlistcompanycheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=00&watchlist_id=<?php echo $row_getdetails['watchlist_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>"> <button class="btn btn-xs btn-danger">
																		<i class="ace-icon fa fa-trash-o bigger-120"></i> </button></a> <?php
																																	}
																																	if ($row_getdetails['status'] == '00') {

																																		?>
																<a href="watchlistcompanycheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=22&watchlist_id=<?php echo $row_getdetails['watchlist_id']; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>"> <button class="btn btn-xs btn-success">
																		<i class="ace-icon fa fa-check bigger-120"></i> </button></a>
															<?php
																																	}
															?>
														</td>
													</tr>
												</table>
												<table id="simple-table" class="table  table-striped  table-bordered table-hover">
													<thead>
														<tr>
															<td>
																<strong>WATCH LIST PHOTO:</strong>
															</td>
															<td>
																<strong>NOTES:</strong>
															</td>
														</tr>
													</thead>
													<tr>
														<td width="50%"><img width="200px" src="watchlistphotos/<?php echo $row_getdetails['photo']; ?>">
														</td>
														<td>
															<?php echo $row_getdetails['data_notes']; ?></td>
													</tr>
												</table>


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
																		<span class="label label-sm label-danger">Rejected</span>
																	<?php
																	}
																	if ($row_getdetails['review_status'] == '22') {
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
												}
												?>


												<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="watchlistcompanycheck.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=11&search_id_approve=<?php echo $search_ref; ?>&request_id=<?php echo $colname_getrequestid; ?>&moduleid=<?php echo $colname_getmoduleid; ?>&watchlist_id=<?php echo $row_getdetails['watchlist_id']; ?>"> <button class="btn btn-xs btn-success">
														<i class="ace-icon fa fa-check bigger-120">Approve</i> </button></a>

												<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-addreject-<?php echo $row_getdetails['watchlist_id']; ?>" role="button" class="blue" data-toggle="modal">
													<button class="btn btn-xs btn-danger"> Reject With Reason </button></a>
												<div id="modal-addreject-<?php echo $row_getdetails['watchlist_id']; ?>" class="modal fade" tabindex="-1">
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
																	<input type="hidden" id="watchlist_id" name="watchlist_id" value="<?php echo $row_getdetails['watchlist_id']; ?>" />
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
												<hr />
											<?php

											} while ($row_getdetails = mysqli_fetch_assoc($getdetails));
											?>

										</div>
									<?php


									}

									?>

									<div class="col-lg-12" align="left">

										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-checkinglobalsystem" role="button" class="orange" data-toggle="modal">
											<button class="btn btn-xs btn-warning">
												<i class="ace-icon smaller-80 green"></i>fetch from Global System
											</button></a>
										<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a href="#modal-adddetails" role="button" class="green" data-toggle="modal">
											<button class="btn btn-xs btn-primary">
												<i class="ace-icon smaller-80 green"></i>Add Data
											</button></a>



										<div id="modal-adddetails" class="modal fade" tabindex="-1">
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


															<input type="hidden" id="status" name="status" value="22" />
															<input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
															<input type="hidden" id="date_added" name="date_added" value="<?php echo date('Y-m-d H:m:s'); ?>" />
															<div class="space-10"></div>

															<label class="col-sm-4">Name</label>

															<div class="col-sm-8"><span id="sprytextfield1">
																	<input class="form-control" type="text" id="company_name" name="company_name" value="<?php echo $row_getstudent['bg_dataset_name']; ?>" required />
																	<span class="textfieldRequiredMsg">*</span></span></div>
															<br />
															<div class="space-10"></div>
															<label class="col-sm-4">Address</label>

															<div class="col-sm-8"><span id="sprytextfield4">
																	<input class="form-control" type="text" id="address" name="address" />
																	<span class="textfieldRequiredMsg">*</span></span></div>



															<br />
															<div class="space-10"></div>


															<label class="col-sm-4">Country</label>

															<div class="col-sm-7"><span id="spryselect1">
																	<select class="chosen-select form-control" name="country" id="country" data-placeholder="Choose Country...">
																		<!--     <select name="client_country" id="client_country">-->

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
																	<input class="form-control" type="text" id="data_source" name="data_source" />
																	<span class="textfieldRequiredMsg">*</span></span></div>
															<br />
															<div class="space-10"></div>


															<label class="col-sm-4">Watchlist Status</label>

															<div class="col-sm-8">
																<select class="chosen-select form-control" name="watchlist_status" id="watchlist_status" data-placeholder="Choose Watchlist Status..." required>
																	<!--  <select name="client_industry" id="client_industry">-->
																	<option value="">Watchlist Status</option>
																	<option value="NO ADVERSE MENTION FOUND">NO ADVERSE MENTION FOUND</option>
																	<option value="ADVERSE MENTION FOUND">ADVERSE MENTION FOUND</option>
																</select>
															</div>


															<br />
															<div class="space-10"></div>

															<label class="col-sm-4">Watchlist Photo</label>
															<div class="col-sm-8">
																<input class="form-control" id="photo" name="photo" type="file" />
															</div>
															<br />


															<br />
															<div class="space-10"></div>
															<label class="col-sm-4">Comments</label>
															<div class="col-sm-12">
																<div class="widget-box widget-color-green">
																	<div class="widget-header widget-header-small"> </div>

																	<div class="widget-body">
																		<div class="widget-main no-padding">
																			<textarea name="data_notes" data-provide="markdown" data-iconlibrary="fa" rows="10"></textarea>
																		</div>
																	</div>
																</div>
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
</body>

</html>
<?php

?>