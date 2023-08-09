<?php require_once('Connections/connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect,$theValue) : mysqli_escape_string($connect,$theValue);

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "forgotpassword")) {
  $updateSQL = sprintf("UPDATE pel_users SET USR_STATUS='22', USR_PASSWORD=%s, USR_PIN=%s WHERE USR_STAFF_ID=%s and USR_USERNAME=%s",
                       GetSQLValueString(md5($_POST['USR_PIN']), "text"),
					   GetSQLValueString($_POST['USR_PIN'], "text"),
                       GetSQLValueString($_POST['USR_STAFF_ID'], "text"),
					   GetSQLValueString($_POST['USR_USERNAME'], "text"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}


?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Peleza Admin</title>


		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/font-awesome.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.css" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-rtl.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.css" />
		<![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.js"></script>
		<![endif]-->
          <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
        
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
							<h1>
									<img src="assets/images/PelezaLogo.png" alt="Peleza Logo">								</h1>
								<h4 class="blue" id="id-company-text">Administration Interface</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Please Enter Your Login Information
											</h4>

<?php


if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
 $password=md5($_POST['password']);
  $staffnumber=$_POST['staffnumber'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "html/dashboard/dashboard.php";
  $MM_redirectLoginChangePassword = "cp.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysqli_select_db($connect,$database_connect);
  
  $LoginRS__query=sprintf("SELECT
pel_users.USR_ID,
pel_users.USR_BILLER_ID,
pel_users.USR_CREATED_BY,
pel_users.USR_DATE_CREATED,
pel_users.USR_DATE_MODIFIED,
pel_users.USR_EMAIL,
pel_users.USR_LAST_PASSWORD_CHANGE,
pel_users.USR_MODIFIED_BY,
pel_users.USR_NAME,
pel_users.USR_PASSWORD,
pel_users.USR_PHONE_NO,
pel_users.USR_RETRIES,
pel_users.USR_STAFF_ID,
pel_users.USR_STATUS,
pel_users.USR_USERNAME,
pel_users.FK_INSTITUTION_ID,
pel_users.USR_NATIONAL_ID,
pel_users.USR_PROFILE,
pel_users.USR_PIN,
pel_users.USR_PIN_STATUS
FROM
pel_users
WHERE USR_USERNAME=%s AND USR_PASSWORD=%s AND USR_STAFF_ID=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString($staffnumber, "text")); 
   
  $LoginRS = mysqli_query_ported($LoginRS__query, $connect) or die(mysqli_error($connect));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
	 
	   while($row= mysqli_fetch_array($LoginRS))
 {
   
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
    $_SESSION['MM_USR_EMAIL'] = $row['USR_EMAIL'];
	$_SESSION['MM_USR_NAME'] = $row['USR_NAME'];
	$_SESSION['MM_USR_PHONE_NO'] = $row['USR_PHONE_NO'];
	$_SESSION['MM_USR_STAFF_ID'] = $row['USR_STAFF_ID'];
	$_SESSION['MM_full_names'] = $row['USR_NAME'];
	$_SESSION['MM_USR_ID'] = $row['USR_ID'];    
/*	$_SESSION['MM_UR_ROLE_ID'] = $row['UR_ROLE_ID'];  */
/*	$_SESSION['MM_ROLE_NAME'] = $row['ROLE_NAME'];*/
	$_SESSION['MM_USR_NATIONAL_ID'] =$row['USR_NATIONAL_ID'];
    $_SESSION['MM_USR_PROFILE'] =$row['USR_PROFILE'];
	$_SESSION['MM_USR_STATUS'] =$row['USR_STATUS'];
}
  
if($_SESSION['MM_USR_STATUS']=='11')
{
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
	}
	
if($_SESSION['MM_USR_STATUS']=='22')
{
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginChangePassword );
	}	
if($_SESSION['MM_USR_STATUS']=='00')
{
    ?>
<p style="color:#FF0000" align="center">
											* Your Account Is Blocked *
											</p>  
  
  
  <?php  
	}	
  }
  else {
  
  ?>
<p style="color:#FF0000" align="center">
											* Enter Correct Details *
											</p>  
  
  
  <?php  
   // header("Location: ". $MM_redirectLoginFailed );
  }
}

?>



											<div class="space-6"></div>

												<form name="loginuser" action="<?php echo $loginFormAction; ?>" method="POST"  >
												<fieldset>
                                                	<label class="block clearfix">
														<span class="block input-icon input-icon-right"><span id="sprytextfield1">
														<input  id="staffnumber" name="staffnumber" type="text" class="form-control" placeholder="Staff Number" />
														<span class="textfieldRequiredMsg">*</span></span><i class="ace-icon fa fa-user"></i>														</span>													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right"><span id="sprytextfield2">
														<input  id="username" name="username" type="text" class="form-control" placeholder="Username" />
														<span class="textfieldRequiredMsg">*</span></span><i class="ace-icon fa fa-user"></i>														</span>													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right"><span id="sprytextfield3">
														<input  id="password" name="password" type="password" class="form-control" placeholder="Password" />
														<span class="textfieldRequiredMsg">*</span></span><i class="ace-icon fa fa-lock"></i>														</span>													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> Remember Me</span>														</label>

								<button type="submit" value="submit" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

										<!--	<div class="social-or-login center">
												<span class="bigger-110">Or Login Using</span>
											</div>

											<div class="space-6"></div>

											<div class="social-login center">
												<a class="btn btn-primary">
													<i class="ace-icon fa fa-facebook"></i>
												</a>

												<a class="btn btn-info">
													<i class="ace-icon fa fa-twitter"></i>
												</a>

												<a class="btn btn-danger">
													<i class="ace-icon fa fa-google-plus"></i>
												</a>
											</div>-->
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>

											<div>
												<a href="#" data-target="#signup-box" class="user-signup-link">
													I want to register
													<i class="ace-icon fa fa-arrow-right"></i>
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your staff no & username and check email for Instructions
											</p>

											<form method="POST" action="<?php echo $editFormAction; ?>" name="forgotpassword">
                                            <?php
                                        
		
function GeraHash2($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789£$@abcdefghijklmnopqrstuvwxyz!#@';
$QuantidadeCaracteres = strlen($Caracteres);
$QuantidadeCaracteres--;

$Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

return $Hash;
}


//Here you specify how many characters the returning string must have
  ?>								
                                            
                                            	<input  id="USR_PIN" name="USR_PIN" type="text" class="form-control" value="<?php echo "".GeraHash2(6)."".date('ms'); ?>"/>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right"><span id="sprytextfield4">
														<input  id="USR_STAFF_ID" name="USR_STAFF_ID" type="text" class="form-control" placeholder="Staff Number" />
														<span class="textfieldRequiredMsg">*</span></span><i class="ace-icon fa fa-user"></i>														</span>													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right"><span id="sprytextfield5">
														<input  id="USR_USERNAME" name="USR_USERNAME" type="text" class="form-control" placeholder="Username" />
														<span class="textfieldRequiredMsg">*</span></span><i class="ace-icon fa fa-user"></i>														</span>													</label>
													<div class="clearfix">
                                                    	<button type="submit" value="submit" class="width-35 pull-right btn btn-sm btn-danger">
													
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											    <input type="hidden" name="MM_update" value="forgotpassword">
										  </form>
									  </div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
			
			var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "none", {validateOn:["change"]});
</script>
		</script>
	</body>
</html>
