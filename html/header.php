<?php require_once('/var/www/html/pidva/Connections/connect.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
	session_start();
}


// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
	$logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
	//to fully log out a visitor we need to clear the session varialbles
	$updateSQL = sprintf(
		"UPDATE pel_users SET USR_LAST_LOGIN=%s, USR_PIN_STATUS='0' WHERE USR_USERNAME=%s",
		GetSQLValueString(date('d-m-Y H:m:s'), "text"),
		GetSQLValueString($_SESSION['MM_Username'], "text")
	);

	mysqli_select_db($connect, $database_connect);
	$Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));


	$_SESSION['MM_Username'] = NULL;
	$_SESSION['MM_UserGroup'] = NULL;
	$_SESSION['PrevUrl'] = NULL;
	unset($_SESSION['MM_Username']);
	unset($_SESSION['MM_UserGroup']);
	unset($_SESSION['PrevUrl']);



	$logoutGoTo = "../../index.php";
	if ($logoutGoTo) {
		header("Location: $logoutGoTo");
		exit;
	}
}
?>
<?php
if (!isset($_SESSION)) {
	session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
	// For security, start by assuming the visitor is NOT authorized. 
	$isValid = False;

	// When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
	// Therefore, we know that a user is NOT logged in if that Session variable is blank. 
	if (!empty($UserName)) {
		// Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
		// Parse the strings into arrays. 
		$arrUsers = Explode(",", $strUsers);
		$arrGroups = Explode(",", $strGroups);
		if (in_array($UserName, $arrUsers)) {
			$isValid = true;
		}
		// Or, you may restrict access to only certain users based on their username. 
		if (in_array($UserGroup, $arrGroups)) {
			$isValid = true;
		}
		if (($strUsers == "") && true) {
			$isValid = true;
		}
	}
	return $isValid;
}

$MM_restrictGoTo = "../../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
	$MM_qsChar = "?";
	$MM_referrer = $_SERVER['PHP_SELF'];
	if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
	if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
		$MM_referrer .= "?" . $QUERY_STRING;
	$MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
	header("Location: " . $MM_restrictGoTo);
	exit;
} else {
?>
	<meta http-equiv="Refresh" content="10000; url=../../index.php">

	<?php

}
	?><?php
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



		$checkid = $_SESSION['MM_Username'];

		mysqli_select_db($connect, $database_connect);
		$query_clientstatus = "SELECT USR_STATUS FROM pel_users WHERE USR_USERNAME='$checkid'";
		$clientstatus = mysqli_query_ported($query_clientstatus, $connect) or die(mysqli_error($connect));
		$row_newclients = mysqli_fetch_assoc($clientstatus);
		$totalRows_clientstatus = mysqli_num_rows($clientstatus);

		if ($row_newclients['USR_STATUS'] != '11') {
		?>
	<meta http-equiv="Refresh" content="1; url=../../index.php">

<?php
		}

		$profileid = $_SESSION['MM_USR_PROFILE'];
		mysqli_select_db($connect, $database_connect);
		$query_roles = "SELECT
pel_profile_roles.pr_id,
pel_profile_roles.profile_id,
pel_profile_roles.role_id,
pel_profile_roles.role_code,
pel_profile_roles.role_name,
pel_profile_roles.profile_name,
pel_profile_roles.role_cat,
pel_profile_roles.`status`,
pel_profile.`status`
FROM
pel_profile_roles
Inner Join pel_profile ON pel_profile.profile_id = pel_profile_roles.profile_id
WHERE pel_profile_roles.profile_id='$profileid' and pel_profile_roles.status='11' and pel_profile.status='11' ";
		$roles = mysqli_query_ported($query_roles, $connect) or die(mysqli_error($connect));
		$row_roles = mysqli_fetch_assoc($roles);
		$totalRows_roles = mysqli_num_rows($roles);
		$i = 0;
		$roledata = array();
		do {

			$roledata[$i] = $row_roles['role_code'];
			$i++;
		} while ($row_roles = mysqli_fetch_assoc($roles));


		mysqli_free_result($roles);


		$countclient = 0;
		$countpay = 0;
		$countusers = 0;

		if (in_array('VIEW_DASHBOARD_REPORTS_CLIENTS', $roledata)) {

			mysqli_select_db($connect, $database_connect);
			$query_newclients = "SELECT COUNT(client_id) FROM pel_client WHERE status='00'";
			$newclients = mysqli_query_ported($query_newclients, $connect) or die(mysqli_error($connect));
			$row_newclients = mysqli_fetch_assoc($newclients);
			$totalRows_newclients = mysqli_num_rows($newclients);

			$countclient = $row_newclients['COUNT(client_id)'];
			mysqli_free_result($newclients);
		}

		if (in_array('VIEW_DASHBOARD_REPORTS_PAYMENTS', $roledata)) {
			mysqli_select_db($connect, $database_connect);
			$query_countnewpayments = "SELECT COUNT(payment_id) FROM pel_payments where status='22'";
			$countnewpayments = mysqli_query_ported($query_countnewpayments, $connect) or die(mysqli_error($connect));
			$row_countnewpayments = mysqli_fetch_assoc($countnewpayments);
			$totalRows_countnewpayments = mysqli_num_rows($countnewpayments);
			$countpay = $row_countnewpayments['COUNT(payment_id)'];
			mysqli_free_result($countnewpayments);
		}
		if (in_array('VIEW_DASHBOARD_REPORTS_USERS', $roledata)) {
			mysqli_select_db($connect, $database_connect);
			$query_countunverified = "SELECT COUNT(USR_ID) FROM pel_users where USR_STATUS='22'";
			$countunverified = mysqli_query_ported($query_countunverified, $connect) or die(mysqli_error($connect));
			$row_countunverified = mysqli_fetch_assoc($countunverified);
			$totalRows_countunverified = mysqli_num_rows($countunverified);
			$countusers = $row_countunverified['COUNT(USR_ID)'];
			mysqli_free_result($countunverified);
		}


?>
<script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar navbar-fixed-top">
	<script type="text/javascript">
		try {
			ace.settings.check('navbar', 'fixed')
		} catch (e) {}
	</script>

	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="../dashboard/dashboard.php" class="navbar-brand"><img src="../../assets/images/PelezaLogo.png" height="41px">
			</a>

			<!-- /section:basics/navbar.toggle -->
		</div>


		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">

				<li>

					<!--	<img class="nav-user-photo" src="../../assets/avatars/user.jpg" alt="Jason's Photo" />-->
					<button class="btn btn-white btn-purple btn-bold">
						<i class="ace-icon fa fa-key bigger-120 purple"></i>
						Profile: <?php echo $_SESSION['MM_USR_PROFILE_NAME']; ?>
					</button>

				</li>


				<li class="green">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
						<span class="badge badge-important"><?php echo ($countclient + $countpay + $countusers); ?></span> </a>

					<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">



						<li class="dropdown-header">
							<i class="ace-icon fa fa-exclamation-triangle"></i>
							<?php echo ($countclient + $countpay + $countusers); ?> Notifications
						</li>

						<li class="dropdown-content">


							<ul class="dropdown-menu dropdown-navbar navbar-pink">
								<?php
								if (in_array('VIEW_DASHBOARD_REPORTS_CLIENTS', $roledata)) {

								?> <li>
										<a href="../clients/clientsusers.php">
											<i class="btn btn-xs btn-primary fa fa-user"></i>
											<span class="pull-right badge badge-success"><?php echo $row_newclients['COUNT(client_id)']; ?></span> New Registered Clients </a>
									</li>
								<?php
								}
								?>
								<?php
								if (in_array('VIEW_DASHBOARD_REPORTS_PAYMENTS', $roledata)) {


								?>
									<li>
										<a href="../payments/education.php">
											<div class="clearfix">
												<span class="pull-left">
													<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
													New Unverified Payments </span>
												<span class="pull-right badge badge-success"><?php echo $row_countnewpayments['COUNT(payment_id)']; ?></span>
											</div>
										</a>
									</li>
								<?php
								}
								?>
								<?php
								if (in_array('VIEW_DASHBOARD_REPORTS_USERS', $roledata)) {


								?>
									<li>
										<a href="../user/users.php">
											<div class="clearfix">
												<span class="pull-left">
													<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
													Unverified Users </span>
												<span class="pull-right badge badge-success"><?php echo $countusers; ?></span>
											</div>
										</a>
									</li>
								<?php
								}
								?>

							</ul>
						</li>

						<li class="dropdown-footer">
							<a href="#">
								See all notifications
								<i class="ace-icon fa fa-arrow-right"></i> </a>
						</li>
					</ul>
				</li>



				<!-- #section:basics/navbar.user_menu -->
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<!--	<img class="nav-user-photo" src="../../assets/avatars/user.jpg" alt="Jason's Photo" />-->
						<span class="user-info">
							<small>Welcome,</small>
							<?php echo 	$_SESSION['MM_full_names']; ?> </span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

						<?php
						if (in_array('VIEW_MY_PROFILE', $roledata) || in_array('SUPER_USER', $roledata)) {


						?>

							<li>
								<a href="../user/profile.php">
									<i class="ace-icon fa fa-user"></i>
									Profile </a>
							</li>

						<?php
						}
						?>


						<li class="divider"></li>

						<li>
							<a href="<?php echo $logoutAction ?>">
								<i class="ace-icon fa fa-power-off"></i>
								Logout </a>
						</li>
					</ul>
				</li>

				<!-- /section:basics/navbar.user_menu -->
			</ul>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div><!-- /.navbar-container -->
</div>